<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>CallAdmin Middleware</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v1.9.8/dist/alpine.js" defer></script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="navbar-top-fixed.css" rel="stylesheet">
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">CallAdmin Middleware</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.index') }}">Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('my-reports.index') }}">My reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('votes.index') }}">Votes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('votes.index') }}">My votes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">Users</a>
            </li>
        </ul>
        @if(auth()->check())
            <div class="mr-4 position-relative">
                <a href="{{ route('notifications.index') }}">
                    @if($notificationCount > 0)
                        <h3 class="text-white">
                            <i class="fas fa-envelope-open-text"></i>
                        </h3>
                        <span class="badge badge-primary position-absolute" style="left: -8px;bottom: -2px;">
                           {{ $notificationCount }}
                        </span>
                    @else
                        <h3 class="text-white-50">
                            <i class="fas fa-envelope"></i>
                        </h3>
                    @endif
                </a>
            </div>
        @endif

        <form class="form-inline mt-2 mt-md-0" method="GET" action="{{ route('search') }}">
            <input class="form-control mr-sm-2" name="search" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

        <a class="btn btn-outline-primary ml-2" href="{{ route('users.settings') }}"><i class="fas fa-user-cog mr-1"></i>Settings</a>

        @guest
            <a class="btn btn-outline-primary my-2 ml-2" href="{{ route('redirect-to-steam') }}">Login</a>
        @endguest

        @auth
            <a class="btn btn-outline-danger my-2 ml-2" href="{{ route('logout') }}">Logout</a>
        @endauth
    </div>
</nav>

<main style="margin-top: 90px" role="main" class="container">
    @if($errors->any())
        @foreach($errors->all() as $error)
            @php
                flash()->error($error)->important();
            @endphp
        @endforeach
    @endif
    @include('flash::message')

    @yield('content')
</main>

@stack('modals')

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
