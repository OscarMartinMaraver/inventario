@props([
    'title' => config('app.name', 'Laravel'), //Si no existe title, se usa el valor por defecto que es Laravel
    'breadcrumbs' => [],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/310dfe7518.js" crossorigin="anonymous"></script>

    {{-- Wire UI --}}
    <wireui:scripts />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100">

    @include('layouts.includes.admin.navigation')

    @include('layouts.includes.admin.sidebar')


    <div class="p-4 sm:ml-64">
        <div class="mt-14 flex items-center">

            @include('layouts.includes.admin.breadcrumb')
            {{-- Por si quiero poner algo en la parte derecha a la altura del breadcrumbs --}}
            @isset($action)
                <div class="ml-auto">
                    {{ $action }}
                </div>
            @endisset

        </div>

        {{ $slot }}

    </div>


    @stack('modals')

    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
