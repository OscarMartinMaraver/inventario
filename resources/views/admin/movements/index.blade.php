<x-admin-layout title="Entradas y salidas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Entradas y salidas',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.movements.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.movement-table')

</x-admin-layout>
