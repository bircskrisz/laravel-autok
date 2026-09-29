<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autó nyilvántartás</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('manufacturers.index') }}">Autó nyilvántartás</a>
            <div class="navbar-nav">
                <a class="nav-link {{ request()->routeIs('manufacturers.*') ? 'active' : '' }}" href="{{ route('manufacturers.index') }}">Gyártók</a>
                <a class="nav-link {{ request()->routeIs('car-models.*') ? 'active' : '' }}" href="{{ route('car-models.index') }}">Modellek</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
