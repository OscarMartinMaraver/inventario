<x-admin-layout title="Cotizaciones" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Cotizaciones',
        'href' => route('admin.quotes.index'),
    ],
    [
        'name' => 'Nueva cotización',

    ]
]">

@livewire('admin.quote-create')

</x-admin-layout>