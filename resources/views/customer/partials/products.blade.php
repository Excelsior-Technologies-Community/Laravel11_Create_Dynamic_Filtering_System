<div class="row">
    @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 product-card h-100">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" class="product-img">
                @else
                    <img src="https://via.placeholder.com/300x220" class="product-img">
                @endif

                <div class="card-body product-details d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                    <p class="text-muted small">{{ Str::limit($product->details, 70) }}</p>
                    <ul class="list-unstyled mt-auto">
                        <li><strong>Category:</strong> {{ $product->category }}</li>
                        <li><strong>Size:</strong> {{ $product->size }}</li>
                        <li><strong>Color:</strong> {{ $product->color }}</li>
                        <li><strong>Price:</strong> ₹{{ number_format($product->price) }}</li>
                        <li><strong>Stock:</strong> {{ $product->stock }}</li>
                    </ul>
                </div>

                <div class="card-footer bg-white border-0 d-flex justify-content-between">
                    <a href="{{ route('customer.products.show', $product) }}" class="btn btn-sm btn-outline-primary">View</a>
                    <button class="btn btn-sm btn-outline-success" onclick="addToComparison({{ $product->id }})">Add to Compare</button>
                </div>
            </div>
        </div>
    @endforeach
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
