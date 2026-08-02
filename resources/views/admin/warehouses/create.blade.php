<x-admin-layout title="Almacenes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Almacenes',
        'href' => route('admin.warehouses.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.warehouses.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-wire-input name="name" label="Nombre" placeholder="Nombre del almacén" value="{{ old('name') }}" />
            <x-wire-input name="location" label="Localizacion" placeholder="Ubicación del almacén" value="{{ old('location') }}" />
            <div class="flex justify-end">
                <x-wire-button type="submit" label="Guardar" right-icon="check" flat interaction:solid="indigo"/> 
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
