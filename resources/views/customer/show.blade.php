@extends('layouts.customer')

@section('content')
<div class="container py-4">
    <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary mb-3">&larr; Back to Products</a>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 400px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/500x400" class="img-fluid rounded shadow-sm w-100">
                    @endif
                </div>
                <div class="col-md-7">
                    <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
                    <p class="text-muted">{{ $product->details }}</p>

                    <table class="table table-borderless mt-3">
                        <tr><th width="150">Category</th><td>{{ $product->category }}</td></tr>
                        <tr><th>Size</th><td>{{ $product->size }}</td></tr>
                        <tr><th>Color</th><td>{{ $product->color }}</td></tr>
                        <tr><th>Price</th><td class="fw-bold text-success fs-5">₹{{ number_format($product->price) }}</td></tr>
                        <tr><th>Stock</th><td>
                            @if($product->stock > 0)
                                <span class="badge bg-success">In Stock ({{ $product->stock }})</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td></tr>
                    </table>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('customer.products', ['search' => $product->name]) }}" class="btn btn-outline-primary">Search Similar</a>
                        <button class="btn btn-outline-success" onclick="addToComparison({{ $product->id }})">Add to Compare</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
