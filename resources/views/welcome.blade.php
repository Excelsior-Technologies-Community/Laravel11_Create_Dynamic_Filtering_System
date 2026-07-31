<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .feature-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Dynamic Filter System</h1>
            <p class="lead mb-5">Advanced product filtering, live search, analytics, and comparison features.</p>
            <a href="{{ route('customer.products') }}" class="btn btn-light btn-lg me-3">Browse Products</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
            @endauth
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Dynamic Filtering</h5>
                            <p class="card-text">Filter by category, size, color, price range with AJAX</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Live Search</h5>
                            <p class="card-text">Instant suggestions and search history</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Analytics</h5>
                            <p class="card-text">Charts and statistics dashboard</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Compare Products</h5>
                            <p class="card-text">Side-by-side product comparison</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
