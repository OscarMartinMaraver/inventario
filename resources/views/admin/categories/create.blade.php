<x-admin-layout title="Categorías" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
        'href' => route('admin.categories.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-wire-input name="name" label="Nombre" placeholder="Nombre de la cartegoria" value="{{ old('name') }}" />
            <x-wire-textarea name="description" label="Descripción" placeholder="Descripción de la cartegoria">
                {{ old('description') }}
            </x-wire-textarea>
            <div class="flex justify-end">
                <x-wire-button type="submit" label="Guardar" right-icon="check" flat interaction:solid="indigo"/> 
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
