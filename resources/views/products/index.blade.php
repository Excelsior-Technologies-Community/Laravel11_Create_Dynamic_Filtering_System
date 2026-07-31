@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Products List</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 row g-3 align-items-end">
        <div class="col-md-4">
            <select id="sort" class="form-select">
                <option value="">Default Sorting</option>
                <option value="low-high" {{ request('sort') == 'low-high' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="high-low" {{ request('sort') == 'high-low' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="applySort" class="btn btn-outline-primary w-100">Sort</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th width="20%">Details</th>
                            <th>Image</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td style="white-space: normal;">{{ Str::limit($product->details, 60) }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" width="60" class="rounded shadow-sm border">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $product->size }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->category }}</td>
                                <td class="fw-bold text-success">₹{{ number_format($product->price) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->status == 'active' ? 'success' : ($product->status == 'inactive' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm me-1">View</a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                                    <button class="btn btn-outline-success btn-sm me-1" onclick="addToComparison({{ $product->id }})">Compare</button>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <nav aria-label="Product pagination">
            <ul class="pagination pagination-sm">
                @if($products->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                @endif

                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('applySort').onclick = function () {
        let sort = document.getElementById('sort').value;
        let url = new URL(window.location);
        if (sort) {
            url.searchParams.set('sort', sort);
        } else {
            url.searchParams.delete('sort');
        }
        window.location.href = url.toString();
    };
</script>
@endpush
