<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Painel')</title>

    {{-- Bootstrap (opcional, mas recomendado) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    {{-- CSS global --}}
    <style>

        body,.card {
            background: #0b0a0a;
            color: white;
        }

        .navbar-custom {
            background: #000000;
            padding: 15px;
        }

        .navbar-custom a {
            color: #fff;
            font-size: 18px;
            text-decoration: none;
        }

        .content-wrapper {
            padding: 25px;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Navbar --}}
{{--<nav class="navbar navbar-custom mb-4">--}}
{{--    <div class="container">--}}
{{--        <a href="/">🏠 Oliveira Nascimento</a>--}}
{{--    </div>--}}
{{--</nav>--}}

{{-- Conteúdo principal --}}
<div class="container content-wrapper">
    @yield('content')
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
