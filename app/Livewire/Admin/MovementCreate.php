<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Inventory;
use App\Models\Movement;
use App\Models\Product;
use Livewire\Component;

class MovementCreate extends Component
{
    public $type = 1;

    public $serie = 'M0001'; //Le asigno un valo y en el view lo muestro como disabled para que no se pueda modificar

    public $correlative;

    public $date;

    public $warehouse_id;

    public $reason_id;

    public $total = 0;

    public $observation;

    public $product_id;

    public $products = [];

    //funcion boot: se ejecuta cuando se carga el componente. Se usa para inicializar las variables . Nos servirá para verificar los errores de validacion
    public function boot()
    {
        //Verificar si hay errores de validacion
        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();

                $html = "<ul class='text-left'>";

                foreach ($errors as $error) {
                    $html .= "<li > {$error[0]} </li>";
                }
                $html .= "</ul>";

                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Error de validadción',
                    'html' => $html,
                ]);
            }
        });
    }


    /** El metodo mount se ejecuta cuando se carga el componente. Se usa para inicializar las variables*/

    public function mount()
    {
        $this->correlative = Movement::max('correlative') + 1; //obtengo el numero de la cotización mas grande y le sumo 1;
    }

    public function updated($property, $value)
    {
        if ($property == 'type') {
            $this->reset('reason_id');
        }
    }

    public function addProduct()
    {
        $this->Validate([
            'product_id' => 'required|exists:products,id', //valido que el producto exista
            'warehouse_id' => 'required|exists:warehouses,id', //valido que el almacen exista. DEBO SELECCIONAR UN PRODUCTO DE UN ALMACÉN ESPECIFICO
        ], [], [ //El segundo array es para personalizar el mensaje de error en el metodo validate
            'product_id' => 'producto', //El tercer array es para personalizar el nombre de las variables en el metodo validate. 
            'warehouse_id' => 'almacen'
        ]);

        //Comprobar si el producto ya ha sido agregado
        $existing = collect($this->products)
            ->where('id', $this->product_id)
            ->first(); //Transformo el array en una colección. Envuelve el array nativo dentro de un objeto contenedor que viene equipado con decenas de métodos útiles para manipular

        if ($existing) {
            //emito un eveto de Liverwire (similar a lo que haciamos con session()->flash). Este evento debe ser escuchado, por ejemplo desde js en el Layout Admin
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'El producto ya ha sido agregado',
                'text' => 'No puedes agregar el mismo producto dos veces'
            ]);

            return; //impide que se agregue el producto

        }

        $product = Product::find($this->product_id);
        //Recupero el último registro en inventario en función del producto y el almacen.
        $lastRecord = Inventory::where('product_id', $product->id)
            ->where('warehouse_id', $this->warehouse_id)
            ->latest('id')
            ->first();

        $costBalance = $lastRecord->cost_balance ?? 0;

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'price' => $costBalance,
            'subtotal' => $costBalance,

        ];

        //despues de agregar el elemento se limpia el campo
        $this->reset('product_id');
    }

    /**El metodo Save registra la cotización en la base de datos */
    public function save()
    {
        $this->Validate([
            'type' => 'required|in:1,2',
            'serie' => 'required|string|max:100',
            'correlative' => 'required|numeric|min:1|max:1000',
            'date' => 'nullable|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'reason_id' => 'required|exists:reasons,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ], [], [
            'type' => 'tipo de comprobante', //los que son muy comunes, los añado a lang/es/validation.php, dejando aquí solo los que son muy específicos
            'warehouse_id' => 'almacén',
            'rason_id' => 'motivo',
            'total' => 'total',
            'products.*.id' => 'producto',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'precio',
        ]);

        $movement = Movement::create([
            'type' => $this->type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'warehouse_id' => $this->warehouse_id,
            'total' => $this->total,
            'observation' => $this->observation,
            'reason_id' => $this->reason_id,
        ]);

        //A parte de registrar la orden de compra, tambien se deben registrar los productos en la tabala pivote "productables"
        //Recorremos el array de productos de la cotización para registralos de uno en uno mediante la relación polimórfica products(). Se añaden usando el metodo attach
        foreach ($this->products as $product) {
            $movement->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price'],
            ]);

            //KARDEX (Modificación de inventario)

            //En lugar de crear la logica para el manejo del kardex dentro del controlador, se pasa a un servicio, el cual he inyectado dentro del método save()
            //Esto se llama inyeccion de dependencias. En lugar de instanciar el servicio dentro del metodo, se pasa como parametro 
            //  public function save(KardexService $kardex) y despues se llama el metodo
            
            /*
            if($this->type == 1){
                $kardex->registerEntry($movement, $product, $this->warehouse_id, 'Movimiento');
            }elseif($this->type == 2){
                $kardex->registerExit($movement, $product, $this->warehouse_id, 'Movimiento');
            }
            */

            //También se puede crear una fachada, que se encargue de llamar a los servicios y a los controladores. 
            //Primero hay que registrarolos en el AppServiceProvider, resgistrar la insatnciación del servicio

            if($this->type == 1){
                Kardex::registerEntry($movement, $product, $this->warehouse_id, 'Movimiento');
            }elseif($this->type == 2){
                Kardex::registerExit($movement, $product, $this->warehouse_id, 'Movimiento');
            }
        }



        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El movimiento se ha registrado exitosamente.'
        ]);

        return redirect()->route('admin.movements.index');
    }

    public function render()
    {
        return view('livewire.admin.movement-create');
    }
}
