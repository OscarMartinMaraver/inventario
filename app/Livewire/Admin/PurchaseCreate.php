<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use Livewire\Component;

class PurchaseCreate extends Component
{
    public $voucher_type = 1;

    public $serie; 

    public $correlative;

    public $date;

    public $purchase_order_id;

    public $supplier_id;

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



    /**Metodo que se activa cada vez que se modifica un campo en el formulario*/
    public function updated($property, $value)
    {

        if ($property == 'purchase_order_id') {
            $purchaseOrder = PurchaseOrder::find($value);


            if ($purchaseOrder) 
            {
                $this->voucher_type = $purchaseOrder->voucher_type;

                $this->supplier_id= $purchaseOrder->supplier_id;

                $this->products = $purchaseOrder->products->map(function ($product) {
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
            'price' => 0,
            'subtotal' => 0,

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
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ], [], [
            'voucher_type' => 'tipo de comprobante', //los que son muy comunes, los añado a lang/es/validation.php, dejando aquí solo los que son muy específicos
            'supplier_id' => 'proveedor',
            'total' => 'total',
            'products.*.id' => 'producto',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'precio',
        ]);

        $purchase = Purchase::create([
            'voucher_type' => $this->voucher_type,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date ?? now(),
            'purchase_order_id' => $this->purchase_order_id,
            'supplier_id' => $this->supplier_id,
            'warehouse_id' => $this->warehouse_id,
            'total' => $this->total,
            'observation' => $this->observation,
        ]);

        //A parte de registrar la orden de compra, tambien se deben registrar los productos en la tabala pivote "productables"
        //Recorremos el array de productos de la orden de compra para registralos de uno en uno mediante la relación polimórfica products(). Se añaden usando el metodo attach
        foreach ($this->products as $product) {
            $purchase->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'subtotal' => $product['quantity'] * $product['price'],
            ]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La  compra se ha registrado exitosamente.'
        ]);

        return redirect()->route('admin.purchases.index');
    }

    public function render()
    {
        return view('livewire.admin.purchase-create');
    }
}
