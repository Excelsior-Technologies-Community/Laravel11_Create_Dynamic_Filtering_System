@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">{{ $product->name }}</h2>
        <div>
            <a href="{{ route('customer.products.show', $product) }}" class="btn btn-outline-primary me-2" target="_blank">View as Customer</a>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning me-2">Edit</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow-sm">
                    @else
                        <img src="https://via.placeholder.com/400x300" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table">
                        <tr><th>Name</th><td>{{ $product->name }}</td></tr>
                        <tr><th>Details</th><td>{{ $product->details }}</td></tr>
                        <tr><th>Category</th><td>{{ $product->category }}</td></tr>
                        <tr><th>Size</th><td>{{ $product->size }}</td></tr>
                        <tr><th>Color</th><td>{{ $product->color }}</td></tr>
                        <tr><th>Price</th><td>₹{{ number_format($product->price) }}</td></tr>
                        <tr><th>Stock</th><td>{{ $product->stock }}</td></tr>
                        <tr><th>Status</th><td>{{ ucfirst($product->status) }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
