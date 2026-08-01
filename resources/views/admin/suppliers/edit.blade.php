<x-admin-layout title="Proveedores" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Proveedores',
        'href' => route('admin.suppliers.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class= "grid grid-cols-2 gap-4">{{-- Clase para crear dos columnas con un espacion entre ellas --}}

                <x-wire-native-select label="Tipo de documento" name="identity_id">
                    @foreach ($identities as $identity)
                        <option value="{{ $identity->id }}" @selected(old('identity_id', $supplier->identity_id) == $identity->id)>
                            {{ $identity->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input name="document_number" label="Número de documento" placeholder="Numero de documento"
                    value="{{ old('document_number', $supplier->document_number) }}" required />
            </div>


            <x-wire-input name="name" label="Nombre" placeholder="Nombre del proveedor"
                value="{{ old('name', $supplier->name) }}" />
            <x-wire-input name="address" label="Dirección" placeholder="Dirección del proveedor"
                value="{{ old('address', $supplier->address) }}" />
            <x-wire-input name="email" type="email" label="Email" placeholder="Correo electrónico del proveedor"
                value="{{ old('email', $supplier->email) }}" />
            <x-wire-input name="phone" label="Teléfono" placeholder="Teléfono del proveedor"
                value="{{ old('phone', $supplier->phone) }}" />
            <div class="flex justify-end">
                <x-wire-button type="submit" label="Actualizar" right-icon="check" flat interaction:solid="indigo" />
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
