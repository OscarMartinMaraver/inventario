<x-admin-layout 
title="Almacenes" 
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Almacenes',
        'href' => route('admin.warehouses.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">

 <x-wire-card>
        <form action="{{ route('admin.warehouses.update', $warehouse) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <x-wire-input name="name" label="Nombre" placeholder="Nombre del almacén" value="{{ old('name', $warehouse->name) }}" />
            <x-wire-input name="location" label="Localizacion" placeholder="Ubicación del almacén" value="{{ old('location', $warehouse->location) }}" />
            <div class="flex justify-end">
                <x-wire-button type="submit" label="Actualizar" right-icon="check" flat interaction:solid="indigo"/> 
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>