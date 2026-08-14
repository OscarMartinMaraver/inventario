<x-admin-layout title="Cotizaciones" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Cotizaciones',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.quotes.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.quote-table')

</x-admin-layout>
