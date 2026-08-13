<x-admin-layout title="Compras" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Compras',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.purchases.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.purchase-table')

</x-admin-layout>
