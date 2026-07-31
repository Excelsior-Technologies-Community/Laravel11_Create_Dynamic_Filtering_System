@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="container">
        <h2 class="fw-bold mb-4">Dashboard</h2>

        @if(session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Total Products</h6>
                        <h2 class="fw-bold text-primary">{{ $totalProducts }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Active Products</h6>
                        <h2 class="fw-bold text-success">{{ $activeProducts }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Out of Stock</h6>
                        <h2 class="fw-bold text-danger">{{ $outOfStockProducts }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Low Stock (1-5)</h6>
                        <h2 class="fw-bold text-warning">{{ $lowStockProducts }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Category Distribution</div>
                    <div class="card-body">
                        <canvas id="categoryChart" height="220"></canvas>
                        @if($categoryStats->isEmpty())
                            <p class="text-muted text-center mt-3">No category data available.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Product Status</div>
                    <div class="card-body">
                        <canvas id="statusChart" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Recent Activity</div>
            <div class="card-body">
                @forelse($recentActivities as $activity)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <strong>{{ $activity->causer->name ?? 'System' }}</strong>
                            {{ $activity->description }}
                            <span class="text-muted small">{{ $activity->subject->name ?? '' }}</span>
                        </div>
                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No recent activity.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const categoryLabels = {!! json_encode($categoryStats->pluck('category') ?? []) !!};
    const categoryData = {!! json_encode($categoryStats->pluck('count') ?? []) !!};

    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx && categoryLabels.length > 0) {
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Products',
                    data: categoryData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Inactive', 'Draft'],
                datasets: [{
                    data: [{{ $activeProducts }}, {{ $inactiveProducts }}, {{ $draftProducts }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ]
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
</script>
@endsection
