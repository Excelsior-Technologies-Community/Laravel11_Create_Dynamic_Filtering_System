@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <!-- Header with title and Add New Product button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📦 Products List</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">➕ Add New Product</a>
    </div>

    <!-- Success message display from session flash data -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- PRICE SORTING CONTROLS -->
    <div class="mb-3 row g-3 align-items-end">
        <div class="col-md-4">
            <!-- Sort dropdown with current selection preserved -->
            <select id="sort" class="form-select">
                <option value="">Default Sorting</option>
                <option value="low-high" {{ request('sort') == 'low-high' ? 'selected' : '' }}>
                    Price: Low to High
                </option>
                <option value="high-low" {{ request('sort') == 'high-low' ? 'selected' : '' }}>
                    Price: High to Low
                </option>
            </select>
        </div>
        <div class="col-md-2">
            <!-- Apply sorting button triggers JavaScript -->
            <button id="applySort" class="btn btn-outline-primary w-100">Sort</button>
        </div>
    </div>

    <!-- Main products table card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <!-- Responsive hover table with all product data -->
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th width="20%">Details</th>
                            <th>Image</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Loop through sorted products from controller -->
                        @forelse($products as $product)
                            <tr>
                                <!-- Product name (bold) -->
                                <td class="fw-semibold">{{ $product->name }}</td>

                                <!-- Truncated details with normal line wrapping -->
                                <td style="white-space: normal;">
                                    {{ Str::limit($product->details, 60) }}
                                </td>

                                <!-- Product image preview -->
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" width="60"
                                             class="rounded shadow-sm border">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <!-- Product attributes -->
                                <td>{{ $product->size }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->category }}</td>

                                <!-- Formatted price with Rupee symbol -->
                                <td class="fw-bold text-success">
                                    ₹{{ number_format($product->price) }}
                                </td>

                                <!-- Action buttons (Edit/Delete) -->
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-warning btn-sm me-1">✏ Edit</a>

                                    <!-- Delete form with method spoofing -->
                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this product?')">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <!-- Empty state row -->
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // SIMPLE SORTING FUNCTIONALITY
    // Reloads page with ?sort= parameter when Sort button clicked
    document.getElementById('applySort').onclick = function () {
        let sort = document.getElementById('sort').value;
        // Redirects to same page with sort parameter (handled by controller)
        window.location.href = "?sort=" + sort;
    };
</script>
@endpush
