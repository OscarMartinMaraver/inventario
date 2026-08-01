<x-admin-layout title="Clientes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Clientes',
        'href' => route('admin.customers.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class= "grid grid-cols-2 gap-4">{{-- Clase para crear dos columnas con un espacion entre ellas --}}

                <x-wire-native-select label="Tipo de documento" name="identity_id">
                    @foreach ($identities as $identity)
                        <option value="{{ $identity->id }}" @selected(old('identity_id') == $identity->id)>
                            {{ $identity->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input name="document_number" label="Número de documento" placeholder="Numero de documento"
                    value="{{ old('document_number') }}" required />
            </div>


            <x-wire-input name="name" label="Nombre" placeholder="Nombre del cliente" value="{{ old('name') }}" />
            <x-wire-input name="address" label="Dirección" placeholder="Dirección del cliente"
                value="{{ old('address') }}" />
            <x-wire-input name="email" type="email" label="Email" placeholder="Correo electrónico del cliente"
                value="{{ old('email') }}" />
            <x-wire-input name="phone" label="Teléfono" placeholder="Teléfono del cliente"
                value="{{ old('phone') }}" />
            <div class="flex justify-end">
                <x-wire-button type="submit" label="Guardar" right-icon="check" flat interaction:solid="indigo" />
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
