<x-admin-layout title="Productos" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'href' => route('admin.products.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">

    {{-- Injectamos el CDN de Dropzone, que sirve para subir imagenes --}}
    @push('css')
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    @endpush

    <div class="mb-4">
        <form action="{{ route('admin.products.dropzone', $product) }}" class="dropzone" id="my-dropzone" method="POST">
            @csrf

        </form>
    </div>

    <x-wire-card>
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <x-wire-input name="name" label="Nombre" placeholder="Nombre del producto"
                value="{{ old('name', $product->name) }}" />

            <x-wire-textarea name="description" label="Descripción" placeholder="Descripción del producto">
                {{ old('description', $product->description) }}
            </x-wire-textarea>

            <x-wire-input type="number" name="price" label="Precio" placeholder="Precio del producto"
                value="{{ old('price', $product->price) }}" />

            <x-wire-native-select name="category_id" label="Categoría" placeholder="Categoría del producto">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </x-wire-native-select>

            <div class="flex justify-end">
                <x-wire-button type="submit" label="Actualizar" right-icon="check" flat interaction:solid="indigo" />
            </div>
        </form>

    </x-wire-card>

    {{-- Inyectamos el script de Dropzone --}}
    @push('js')
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


        {{-- Configuration options go here
               he seguido indicaciones, pero no se javascript. Cuando se inicia Dropzone, se ejecuta la función init en la que existen 2 variables
               En la bariable image se recorre todas las imagenes asociadas a un producto, recupera su información en la variable mockFile y la transforma en json
            Luego se le pasa al dropzone la informacion de las imagenes usando el metodo displayExistingFile --}}

        <script>
            Dropzone.options.myDropzone = {
                //Configuration option go her
                addRemoveLinks: true,
                init: function() {
                    let myDropzone = this;
                    let images = @json($product->images);
                    let storageBaseUrl = "{{ Storage::url('') }}"; // ej: /storage/

                    images.forEach(function(image) {
                        let mockFile = {
                            id: image.id,
                            name: image.path.split('/').pop(),
                            size: image.size,
                        };
                        myDropzone.displayExistingFile(mockFile, storageBaseUrl + image.path);
                        myDropzone.emit('complete', mockFile);
                        myDropzone.files.push(mockFile);
                    });

                    //Si tiene exito la subida, añado el id de la imagen 
                    this.on("success", function(file, response) {
                        file.id = response.id;
                    });

                    //Para eliminar una imagen escuchamos el evento que se emite cuando pulsamos en eliminar
                    this.on("removedfile", function(file) {
                        axios.delete(`/admin/images/${file.id}`)
                    });
                }
            };
        </script>
    @endpush

</x-admin-layout>
