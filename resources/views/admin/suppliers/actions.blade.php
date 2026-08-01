<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.suppliers.edit', $supplier) }}" blue>
        Editar
    </x-wire-button>

    {{-- El boton de eliminar no se puede poner directamente, debe estar en un formulario para hacer petición tipo Delete--}}
    <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="delete-form"> {{--La clase me servirá para configurar la confirmación de la eliminación--}}
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red>{{--Hay que indicar expresamente type=submit para que no se envie el formulario, ya que los componentes tipo button de wireUI no envian el formulario--}}
            Eliminar
        </x-wire-button>
    </form>

</div>
