<x-admin-layout title="Entradas y salidas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Entradas y salidas',
        'href' => route('admin.movements.index'),
    ],
    [
        'name' => 'Nuevo movimiento',

    ]
]">

@livewire('admin.movement-create')

</x-admin-layout>