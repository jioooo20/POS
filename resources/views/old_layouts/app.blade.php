<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css') }}"> {{-- Tambahkan CSS jika diperlukan --}}
</head>
<body>
    {{-- Header --}}
    @include('partials.header')

    {{-- Konten Halaman --}}
    <div class="container">
        @yield('content')
    </div>

    {{-- Footer (Opsional) --}}
    {{-- @include('partials.footer') --}}
</body>
</html>
