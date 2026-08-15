<x-admin-layout title="Ventas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Ventas',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.sales.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.sale-table')

</x-admin-layout>
