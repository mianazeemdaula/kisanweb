<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kisan Stock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;800&display=swap"
        rel="stylesheet">
</head>

<body class="font-[Poppins] bg-gray-100">
    <div class="flex">
        <div class="w-1/5 hidden md:block">
            <!-- Side Menu -->
            @include('partials.side-menu')
        </div>
        <div class="flex-1 overflow-x-auto">
            <!-- Top Menu -->
            @include('partials.top-menu')
            <!-- Content -->
            <div class="p-2">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
