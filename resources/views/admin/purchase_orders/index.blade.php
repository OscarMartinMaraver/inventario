<x-admin-layout title="Ordenes de compra" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Ordenes de compra',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.purchase-orders.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.purchase-order-table')

</x-admin-layout>
