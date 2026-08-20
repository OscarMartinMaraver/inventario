<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Transfer;
use Livewire\Component;

class TransferCreate extends Component
{

    public $serie = 'T0001'; //Le asigno un valo y en el view lo muestro como disabled para que no se pueda modificar

    public $correlative;

    public $date;

    public $origin_warehouse_id;

    public $destination_warehouse_id;

    public $total=0;

    public $observation;

    public $product_id;

    public $products=[];

    //funcion boot: se ejecuta cuando se carga el componente. Se usa para inicializar las variables . Nos servirá para verificar los errores de validacion
    public function boot()
    {
        //Verificar si hay errores de validacion
        $this->withValidator(function ($validator) {
            if($validator->fails()){
                $errors = $validator->errors()->toArray();

                $html="<ul class='text-left'>";

                foreach($errors as $error)
                {
                    $html.="<li > {$error[0]} </li>";
                }
                $html.="</ul>";

                $this->dispatch ('swal', [
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
        $this->correlative=Transfer::max('correlative') + 1; //obtengo el numero de la cotización mas grande y le sumo 1;
    }


    public function updated($property, $value)
    {
        if($property=='origin_warehouse_id')
            {
                $this->reset('destination_warehouse_id');
            }
        
    }

    public function addProduct()
    {
       $this->Validate([
           'product_id' => 'required|exists:products,id',
           'origin_warehouse_id' => 'required|exists:warehouses,id',
       ],[],[ //El segundo array es para personalizar el mensaje de error en el metodo validate
           'product_id' => 'producto', //El tercer array es para personalizar el nombre de las variables en el metodo validate. 
           'origin_warehouse_id' => 'almacen de origen'
        ]);

        //Comprobar si el producto ya ha sido agregado
        $existing=collect($this->products)
        ->where('id', $this->product_id)
        ->first(); //Transformo el array en una colección. Envuelve el array nativo dentro de un objeto contenedor que viene equipado con decenas de métodos útiles para manipular

        if($existing)
        {
            //emito un eveto de Liverwire (similar a lo que haciamos con session()->flash). Este evento debe ser escuchado, por ejemplo desde js en el Layout Admin
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'El producto ya ha sido agregado',
                'text' => 'No puedes agregar el mismo producto dos veces'
                ]);

            return;//impide que se agregue el producto
           
        }

        $product=Product::find($this->product_id);

        $lastRecord= Kardex::getLastRecord($product->id, $this->origin_warehouse_id); //Utilizamos el provider para obtener el último precio que se le dió al producto


        $this->products[]=[
            'id'=>$product->id,
            'name'=>$product->name,
            'quantity'=>1,
            'price'=>$lastRecord['cost'],
            'subtotal'=>$lastRecord['cost'],
            
        ];
        
        //despues de agregar el elemento se limpia el campo
        $this->reset('product_id');
    }

    /**El metodo Save registra la cotización en la base de datos */
    public function save()
    {
        $this->Validate([
            'serie' => 'required|string|max:100',
            'correlative' => 'required|numeric|min:1|max:1000',
            'date' => 'nullable|date',
            'origin_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'required|different:origin_warehouse_id|exists:warehouses,id',//Destino diferente al origen
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ],[],[
            'origin_warehouse_id' => 'almacén de origen',
            'destination_warehouse_id' => 'almacén de destino',
            'total' => 'total',
            'products.*.id' => 'producto',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'precio',
        ]);

        $transfer=Transfer::create([
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'origin_warehouse_id' => $this->origin_warehouse_id,
            'destination_warehouse_id' => $this->destination_warehouse_id,
            'total' => $this->total,
            'observation' => $this->observation,
        ]);
       
        //A parte de registrar la orden de compra, tambien se deben registrar los productos en la tabala pivote "productables"
        //Recorremos el array de productos de la cotización para registralos de uno en uno mediante la relación polimórfica products(). Se añaden usando el metodo attach
        foreach($this->products as $product)
        {
            $transfer->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price'],
            ]);

            //REGISTRO DE ENTRADA Y SALIDA
            //Utilizo el facade Kardex creado como en Movements, Purchases y Sales

            Kardex::registerExit($transfer, $product, $this->origin_warehouse_id, 'Transferencia');

            Kardex::registerEntry($transfer, $product, $this->destination_warehouse_id, 'Transferencia');

        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La transferencia se ha registrado exitosamente.'
        ]);

        return redirect()->route('admin.transfers.index');
   
    }

    public function render()
    {
        return view('livewire.admin.transfer-create');
    }
}
