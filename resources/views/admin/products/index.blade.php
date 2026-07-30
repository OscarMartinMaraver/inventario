<x-admin-layout title="Productos" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
    ],
]">

    {{-- Puedo usar un  @stack('css') en la vista principal para agregar un estilo 
Este estilo sirve para que las imagenes se ajusten al tamaño de la tabla . No lo puedo hacer desde DataTable por conflicto entre Tailwind y DataTables (livewire) --}}
    @push('css')
        <style>
            .image-product {
                width: 8rem !important;
                height: 5rem !important;
                object-fit: cover !important;
                object-position: center !important;
                border-radius: 1rem !important;
            }
        </style>
    @endpush

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.products.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>


    @livewire('admin.datatables.product-table') {{-- Puede usarse product-table para llamar al componente livewire llamado ProductTable.php --}}

    {{-- Nos permite renderizar scripts adicionales --}}
    @push('js')
        <script>
            forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Evita el envío del formulario
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "¡No podrás revertir esto!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "¡Sí, eliminarlo!",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-admin-layout>
