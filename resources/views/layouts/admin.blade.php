<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kisan Stock | Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 h-full flex flex-col overflow-hidden">
    <div class="flex h-full overflow-hidden">
        <!-- Sidebar -->
        <div class="w-80 hidden lg:block h-full flex-shrink-0">
            @include('partials.side-menu')
        </div>
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-full overflow-y-auto">
            <!-- Top Menu -->
            @include('partials.top-menu')
            <!-- Page Content -->
            <div class="p-6 md:p-8 flex-1">
                @yield('content')
            </div>
        </div>
    </div>
    @yield('js')
</body>

</html>
