<x-admin-layout title="Ordenes de compra" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Ordenes de compra',
        'href' => route('admin.purchase-orders.index'),
    ],
    [
        'name' => 'Nueva orden de compra',

    ]
]">

@livewire('admin.purchase-order-create')

</x-admin-layout>