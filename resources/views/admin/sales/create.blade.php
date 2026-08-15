<x-admin-layout title="Ventas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Ventas',
        'href' => route('admin.sales.index'),
    ],
    [
        'name' => 'Nueva venta',

    ]
]">

@livewire('admin.sale-create')

</x-admin-layout>