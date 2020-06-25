<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Reports</title>

    <!-- TailwindCSS -->
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    @stack('head')
</head>
<body class="bg-gray-900 text-gray-100">
<nav class="flex px-12 py-8 mb-8 w-full items-center">
    <div class="text-white text-4xl uppercase">
        <h1 class="flex">
            <span>Over</span>
            <span class="font-bold">watch</span>
        </h1>
    </div>
    <div class="flex-grow mx-10">
        <ul class="flex text-xl uppercase">
            <li class="mx-4">Reports</li>
            <li class="mx-4">My votes</li>
            <li class="mx-4">My reports</li>
        </ul>
    </div>
    <div class="flex items-center">
        <i class="mx-3 text-3xl fa fa-envelope-o" aria-hidden="true"></i>
        <i class="mx-3 text-3xl fa fa-search" aria-hidden="true"></i>

        <a class="ml-3" href="#">

            <img src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png" alt="Steam login">
        </a>
    </div>
</nav>
@yield('content')
@stack('modals')
@stack('scripts')
</body>
</html>
