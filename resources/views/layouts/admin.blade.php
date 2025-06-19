<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <!-- Główne style -->
        <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    </head>

    <body id="page-top">

        <div id="wrapper">
            @include('layouts.sidebar')

            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">
                    @include('layouts.topbar')

                    <div class="container-fluid py-4">
                        @yield('content')
                    </div>

                </div>
            </div>
        </div>

        <!-- Skrypty -->
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        @stack('scripts')
    </body>
</html>