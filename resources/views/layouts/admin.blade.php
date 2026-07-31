<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts / Bootstrap CSS -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- PAGE SPECIFIC STYLES (Select2 CSS etc.) -->
    @yield('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    {{-- TOP NAVIGATION --}}
    @include('layouts.navigation')

    {{-- PAGE CONTENT --}}
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- jQuery REQUIRED for Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- PAGE SPECIFIC SCRIPTS (Select2 JS etc.) -->
    @stack('scripts')

    <a href="{{ route('comparison.index') }}" class="btn btn-primary position-fixed bottom-0 end-0 m-3 rounded-pill shadow" id="comparisonBadge" style="z-index: 999; text-decoration: none; color: #fff;">
        Compare (<span id="comparisonCount">0</span>)
    </a>

    <script>
    function updateComparisonCount() {
        fetch(`{{ route('comparison.count') }}`)
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('comparisonCount');
                if (badge) badge.textContent = data.count;
            });
    }

    function addToComparison(id) {
        fetch(`{{ route('comparison.add', ':id') }}`.replace(':id', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateComparisonCount();
                alert('Added to comparison');
            }
        });
    }

    function removeFromComparison(id) {
        fetch(`{{ route('comparison.remove', ':id') }}`.replace(':id', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateComparisonCount();
            }
        });
    }

    updateComparisonCount();
    </script>
</body>
</html>
