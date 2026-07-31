@extends('layouts.customer')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Product Comparison</h2>

    @if($products->count() < 2)
        <div class="alert alert-info">
            Add at least 2 products to compare. <a href="{{ route('customer.products') }}">Browse Products</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Feature</th>
                        @foreach($products as $product)
                            <th class="text-center">
                                {{ $product->name }}
                                <form action="{{ route('comparison.remove', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger ms-2">Remove</button>
                                </form>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Image</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" width="120" class="rounded">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Price</strong></td>
                        @foreach($products as $product)
                            <td class="text-center fw-bold text-success">₹{{ number_format($product->price) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Category</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">{{ $product->category }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Size</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">{{ $product->size }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Color</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">{{ $product->color }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Stock</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">{{ $product->stock }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Details</strong></td>
                        @foreach($products as $product)
                            <td>{{ $product->details }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
