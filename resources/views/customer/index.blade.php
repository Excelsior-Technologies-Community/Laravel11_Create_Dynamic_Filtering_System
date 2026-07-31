@extends('layouts.customer')

@section('content')
<style>
    .product-card {
        height: 480px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-img {
        height: 260px;
        width: 100%;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
    }
    .product-details {
        height: 180px;
        overflow: hidden;
    }
    .filter-sidebar {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .search-wrapper {
        position: relative;
    }
    .suggestions-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }
    .suggestions-dropdown.show {
        display: block;
    }
    .suggestion-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }
    .suggestion-item:hover {
        background: #f8f9fa;
    }
    .recent-chip {
        display: inline-block;
        background: #e9ecef;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin: 2px;
        cursor: pointer;
        text-decoration: none;
        color: #333;
    }
    .recent-chip:hover {
        background: #dee2e6;
    }
    .comparison-badge {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #0d6efd;
        color: #fff;
        padding: 12px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 999;
        text-decoration: none;
    }
    .price-slider {
        width: 100%;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-bold">Products</h4>
            <a href="{{ route('comparison.index') }}" class="btn btn-outline-primary comparison-badge" id="comparisonBadge">
                Compare (<span id="comparisonCount">0</span>)
            </a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="search-wrapper">
            <input type="text" id="liveSearch" class="form-control" placeholder="Search products..." autocomplete="off">
            <div class="suggestions-dropdown" id="suggestionsDropdown"></div>
        </div>
        <div id="recentSearches" class="mt-2">
            @if(!empty($recentSearches))
                <strong>Recent:</strong>
                @foreach($recentSearches as $search)
                    <a href="{{ route('customer.products', ['search' => $search]) }}" class="recent-chip">{{ $search }}</a>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="filter-sidebar">
            <h6 class="fw-bold mb-3">Filters</h6>

            <form id="filterForm" action="{{ route('customer.products') }}" method="GET">
                <input type="hidden" name="search" id="filterSearch" value="{{ request('search') }}">

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" id="filterCategory">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Size</label>
                    <select name="size" class="form-select" id="filterSize">
                        <option value="">All Sizes</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Color</label>
                    <select name="color" class="form-select" id="filterColor">
                        <option value="">All Colors</option>
                        @foreach($colors as $color)
                            <option value="{{ $color }}" {{ request('color') == $color ? 'selected' : '' }}>
                                {{ $color }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price Range</label>
                    <div class="d-flex gap-2">
                        <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-select" id="filterSort">
                        <option value="">Default</option>
                        <option value="low-high" {{ request('sort') == 'low-high' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="high-low" {{ request('sort') == 'high-low' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-9">
        <div id="productsContainer">
            @include('customer.partials.products', ['products' => $products])
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let searchTimeout;
const liveSearch = document.getElementById('liveSearch');
const suggestionsDropdown = document.getElementById('suggestionsDropdown');

liveSearch.addEventListener('input', function() {
    const query = this.value;
    document.getElementById('filterSearch').value = query;

    clearTimeout(searchTimeout);
    if (query.length >= 2) {
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsDropdown.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'suggestion-item';
                            div.innerHTML = `<strong>${item.name}</strong> - ${item.category} - ₹${item.price}`;
                            div.addEventListener('click', () => {
                                window.location.href = item.url;
                            });
                            suggestionsDropdown.appendChild(div);
                        });
                        suggestionsDropdown.classList.add('show');
                    } else {
                        suggestionsDropdown.classList.remove('show');
                    }
                });
        }, 300);
    } else {
        suggestionsDropdown.classList.remove('show');
    }
});

document.addEventListener('click', function(e) {
    if (!liveSearch.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
        suggestionsDropdown.classList.remove('show');
    }
});

$('#filterForm select, #filterForm input').on('change keyup', function() {
    clearTimeout(window.filterTimeout);
    window.filterTimeout = setTimeout(() => {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData).toString();

        fetch(`{{ route('customer.products') }}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newProducts = doc.getElementById('productsContainer');
            if (newProducts) {
                document.getElementById('productsContainer').innerHTML = newProducts.innerHTML;
            }
        });
    }, 500);
});

function updateComparisonCount() {
    fetch(`{{ route('comparison.count') }}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('comparisonCount').textContent = data.count;
        });
}

updateComparisonCount();

function addToComparison(id) {
    fetch(`{{ route('comparison.add', ':id') }}`.replace(':id', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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

function removeFromComparison(id) {
    fetch(`{{ route('comparison.remove', ':id') }}`.replace(':id', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
</script>
@endpush
