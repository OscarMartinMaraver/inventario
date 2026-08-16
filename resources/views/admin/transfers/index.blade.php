<x-admin-layout title="Transferencias" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Transferencias',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.transfers.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.transfer-table')

</x-admin-layout>
