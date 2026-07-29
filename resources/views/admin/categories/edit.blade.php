<x-admin-layout 
title="Categorías" 
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'href' => route('admin.categories.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">

 <x-wire-card>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <x-wire-input name="name" label="Nombre" placeholder="Nombre de la cartegoria" value="{{ old('name', $category->name) }}" />
            <x-wire-textarea name="description" label="Descripción" placeholder="Descripción de la cartegoria">
                {{ old('description', $category->description)}}
            </x-wire-textarea>
            <div class="flex justify-end">
                <x-wire-button type="submit" label="Actualizar" right-icon="check" flat interaction:solid="indigo"/> 
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>