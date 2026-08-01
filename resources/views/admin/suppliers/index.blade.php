<x-admin-layout title="Proveedores" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Proveedores',
    ],
]">

    <x-slot name="action">
        <x-wire-button href="{{ route('admin.suppliers.create') }}" light indigo>
            Nuevo
        </x-wire-button>
    </x-slot>


    @livewire('admin.datatables.supplier-table'){{-- Puede usarse supplier-table para llamar al componente livewire llamado CustomerTable.php --}}

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
