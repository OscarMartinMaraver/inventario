<x-admin-layout title="Productos" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'href' => route('admin.products.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-wire-input name="name" label="Nombre" placeholder="Nombre del producto" value="{{ old('name') }}" />

            <x-wire-textarea name="description" label="Descripción" placeholder="Descripción del producto">
                {{ old('description') }}
            </x-wire-textarea>

            <x-wire-input type="number" name="price" label="Precio" placeholder="Precio del producto" value="{{ old('price') }}" />

            <x-wire-native-select name="category_id" label="Categoría" placeholder="Categoría del producto">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id) >{{ $category->name }}</option>
                @endforeach
            </x-wire-native-select>

            <div class="flex justify-end">
                <x-wire-button type="submit" label="Guardar" right-icon="check" flat interaction:solid="indigo"/> 
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
