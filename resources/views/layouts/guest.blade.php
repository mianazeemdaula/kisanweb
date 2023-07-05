<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisan Stock | Buying and Selling</title>
    <meta name="description"
        content="Kisan Stock app is a comprehensive platform for farmers and agribusinesses to buy and sell agricultural products, access real-time market prices, and stay updated with the latest news and trends in the agricultural sector. With Kisan Stock app, farmers can easily connect with buyers and get the best prices for their produce, while buyers can easily source quality products directly from the farmers. The app also offers a range of tools and resources to help farmers make informed decisions and improve their productivity.">
    <meta name="keywords" content="kisanstock, kisan stock, crop rates">
    <meta name="og:title" property="og:title" content="Kisan Stock">
    <meta name="robots" content="index, follow">
    <link href="URL" rel="canonical">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('components.guest_navbar')
    @yield('body')
    @yield('js')
</body>

</html>
