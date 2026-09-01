<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Sale;
use Livewire\Component;

class SaleCreate extends Component
{
    public $voucher_type = 1;

    public $serie = 'F001';

    public $correlative;

    public $date;

    public $quote_id;

    public $customer_id;

    public $warehouse_id;

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

    public function mount()
    {
        $this->correlative = Sale::max('correlative') + 1;
    }


    /**Metodo que se activa cada vez que se modifica un campo en el formulario*/
    public function updated($property, $value)
    {

        if ($property == 'quote_id') {
            $quote = Quote::find($value);


            if ($quote) {
                $this->voucher_type = $quote->voucher_type;

                $this->customer_id = $quote->customer_id;

                $this->products = $quote->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'price' => $product->pivot->price,
                        'subtotal' => $product->pivot->subtotal
                    ];
                })->toArray(); //el metodo map devuelve una colleccion, hay que transformarla en un array para poder usarla en la vista
            }
        }
    }

    public function addProduct()
    {
        $this->Validate([
            'product_id' => 'required|exists:products,id',
        ], [], [ //El segundo array es para personalizar el mensaje de error en el metodo validate
            'product_id' => 'producto', //El tercer array es para personalizar el nombre de las variables en el metodo validate. 
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

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
            'subtotal' => $product->price,

        ];

        //despues de agregar el elemento se limpia el campo
        $this->reset('product_id');
    }

    /**El metodo Save registra la orden de compra en la base de datos */
    public function save()
    {
        $this->Validate([
            'voucher_type' => 'required|in:1,2',
            'serie' => 'required|string|max:10',
            'correlative' => 'required|numeric|max:1000',
            'date' => 'nullable|date',
            'quote_id' => 'nullable|exists:quotes,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'customer_id' => 'required|exists:customers,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ], [], [
            'voucher_type' => 'tipo de comprobante', //los que son muy comunes, los añado a lang/es/validation.php, dejando aquí solo los que son muy específicos
            'customer_id' => 'cliente',
            'total' => 'total',
            'products.*.id' => 'producto',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'precio',
        ]);

        $sale = Sale::create([
            'voucher_type' => $this->voucher_type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'quote_id' => $this->quote_id,
            'customer_id' => $this->customer_id,
            'warehouse_id' => $this->warehouse_id,
            'total' => $this->total,
            'observation' => $this->observation,
        ]);

        //A parte de registrar la orden de compra, tambien se deben registrar los productos en la tabala pivote "productables"
        //Recorremos el array de productos de la orden de compra para registralos de uno en uno mediante la relación polimórfica products(). Se añaden usando el metodo attach
        foreach ($this->products as $product) {
            $sale->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price'],
            ]);

            //KARDEX (Modificación de inventario)

            //En lugar de crear la logia para el manejo del kardex dentro del controlador, se pasa a un servicio, el cual he inyectado dentro del método save()
            //Esto se llama inyeccion de dependencias. En lugar de instanciar el servicio dentro del metodo, se pasa como parametro 
            //  public function save(KardexService $kardex) y despues se llama el metodo

            //$kardex->registerEntry($purchase, $product, $this->warehouse_id, 'Venta');

            //También se puede crear una fachada, que se encargue de llamar a los servicios y a los controladores. 
            //Primero hay que registrarolos en el AppServiceProvider, resgistrar la insatnciación del servicio

            Kardex::registerExit($sale, $product, $this->warehouse_id, 'Venta');

        }
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'La  venta se ha registrado exitosamente.'
            ]);

            return redirect()->route('admin.sales.index');
        
    }
    public function render()
    {
        return view('livewire.admin.sale-create');
    }
}
