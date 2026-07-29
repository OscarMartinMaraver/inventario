<x-admin-layout title="Categorías" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Categorías',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.categories.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>


    @livewire('admin.datatables.category-table'){{-- Puede usarse category-table para llamar al componente livewire llamado CategoryTable.php --}}

    {{-- Nos permite renderizar scripts adicionales --}}
    @push('js')
        <script>
            forms=document.querySelectorAll('.delete-form');
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
