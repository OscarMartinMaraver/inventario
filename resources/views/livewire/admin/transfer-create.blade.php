<div x-data="{
    products: @entangle('products'), {{-- Uso Alpine para asociar a la propiedad "products" y "total" del componente livewire  PurchaseOrderCreate--}}

    total: @entangle('total'),
    {{--Método para eliminar un producto--}}
    removeProduct(index) {   
        this.products.splice(index, 1);
    },
    {{--metodo que se mantiene a la escucha para actualizar el total--}}
    init() {
        this.$watch('products', (newProducts) => {

            let total=0;
            newProducts.forEach(product => {
                total += product.quantity * product.price;
            });
            this.total = total;
        });
    }
}">
    <x-wire-card>
        <form wire:submit="save" class="space-y-4"> {{-- cuando se mande el formulario se ejecutara el metodo save. Lo declaro en el componenete livewire  PurchaseOrderCreate--}}
            <div class="grid lg:grid-cols-4 gap-4">
                
                <div class="grid lg:grid-cols-2 gap-4">

                    <x-wire-input label="Serie" wire:model="serie" placeholder="Serie del comprobante" disabled/>

                    <x-wire-input label="Correlativo" wire:model="correlative" placeholder="Correlativo del comprobante" disabled/>
               
                </div>

                <x-wire-input label="Fecha" wire:model="date" type="date" />

                <x-wire-select
                    label="Almacen origen" 
                    placeholder="Seleccione un almacén"
                    wire:model.live="origin_warehouse_id"
                    :async-data="[                  //Agrego la propiedad async-data para llamar a la api que conectara con la base de datos para buscar proveedores
                        'api'=> route('api.warehouses.index'),
                        'method' => 'POST',
                    ]"
                    option-label="name"   {{--Valor que se muestra en las opciones--}}
                    option-value="id"       {{--Valor que se envia en las opciones--}}
                    option-description="description"
                /> 

                <x-wire-select
                    label="Almacen destino" 
                    placeholder="Seleccione un almacén"
                    wire:model="destination_warehouse_id"
                    :async-data="[                  //Agrego la propiedad async-data para llamar a la api que conectara con la base de datos para buscar proveedores
                        'api'=> route('api.warehouses.index'),
                        'method' => 'POST',
                        'params' => ['exclude' => $this->origin_warehouse_id], //mando por parametro el id del almacen origen para que lo excluya
                    ]"
                    option-label="name"   {{--Valor que se muestra en las opciones--}}
                    option-value="id"       {{--Valor que se envia en las opciones--}}
                    option-description="description"
                /> 

            </div>

            

            <div class="lg:flex lg:space-x-4">
                 <x-wire-select
                    label="Producto" 
                    placeholder="Seleccione un producto a agregar"
                    wire:model="product_id"
                    :async-data="[                 
                        'api'=> route('api.products.index'),
                        'method' => 'POST',
                    ]"
                    option-label="name"   
                    option-value="id"  
                    class="flex-1"    
                    /> 

                    <div class="flex-shrink-0">
                        <x-wire-button 
                            wire:click="addProduct" 
                            spinner="addProduct"
                            class="w-full mt-4 lg:mt-6.5" 
                            interaction:solid="indigo">
                            Agregar producto
                        </x-wire-button>
                    </div>
                    
            </div>
            
            <div class="overflow-x-auto w-full">

                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class= "text-gray-700 border-y bg-blue-50">
                            <th class="py-2 px-4">Productos</th>
                            <th class="py-2 px-4">Cantidad</th>
                            <th class="py-2 px-4">Precio</th>
                            <th class="py-2 px-4">Subtotal</th>
                            <th class="py-2 px-4"></th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- Vamos a usar Alpine.js en lugar del código comentado mas abajo. La respuesta en la actualización de los datos (precio, cantidad y sub total) serán mas rápidos pues el cálculo no se hace en el servidor --}}
                        <template x-for="(product, index) in products" :key="product.id">

                            <tr class="border-b">
                                <td class="py-2 px-4" x-text="product.name">
                                </td>
                                <td class="py-2 px-4">
                                <x-wire-input 
                                    x-model="product.quantity"  
                                    type="number"
                                    class="w-20"
                                    />
                                
                                </td>
                                <td class="py-2 px-4">
                                <x-wire-input 
                                    x-model.live="product.price"
                                    type="number"
                                    class="w-20"
                                    step=".01"
                                    />
                                </td>
                                <td class="py-2 px-4" x-text="(product.quantity * product.price).toFixed(2)">
                                </td>
                                <td class="py-2 px-4">
                                    <x-wire-mini-button 
                                    rounded
                                    x-on:click="removeProduct(index)"
                                    icon="trash"
                                    red/>
                                </td>
                            </tr>

                        </template>

                        {{--Mensaje en caso de no haber añadido ningín producto--}}
                        <template x-if="products.length === 0">
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">
                                    No hay productos agregados
                                </td>
                            </tr>
                        </template>
                       
                    </tbody>
                </table>

            </div>

           <div class="flex items-center space-x-4">
                <x-label>Observaciones</x-label>

                <x-wire-input
                wire:model="observation"
                class="flex-1"
                />
                
                <div>
                    Total: €/. <span x-text="total.toFixed(2)"></span>
                </div>

           </div>

           <div class="flex justify-end">
            <x-wire-button 
                type="submit" 
                spinner="save"
                icon="check">
                Guardar
            </x-wire-button>
           </div>

        </form>
    </x-wire-card>
</div>
