<x-admin-layout 
title="Dashboard" 
:breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pruebas',
    ],
    [
        'name' => 'Pruebas 2',
    ],
]">
    {{-- Extiende la plantilla de admin. originalmen admin-layout.components (la he sacado fuera, renombrado admin y en el componente AdminLayout renombrado la vista renderizada) --}}
    {{-- Le paso al componente un array de breadcrumbs, esto se conocde como props (pasarle información a un componente). En la plantilla admin hay que llamar a la directiva @props --}}
    
   <x-slot name="action">
        Crear usuario
    </x-slot> 

    HOLA DESDE PANEL ADMINISTRADOR


</x-admin-layout>
