@extends('layouts.admin')

@section('content')

<div class="container py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            Products List

            <span
                id="productCount"
                class="badge bg-primary rounded-pill ms-2">
                {{ $products->total() }}
            </span>
        </h2>

        <a href="{{ route('products.create') }}"
           class="btn btn-primary">
            Add New Product
        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif


    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">
            {{ session('error') }}
        </div>
    @endif


    {{-- FILTER CARD --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                action="{{ route('products.index') }}"
                method="GET"
                id="filterForm">

                <div class="row g-3">


                    {{-- SEARCH --}}
                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Search products..."
                            value="{{ request('search') }}">

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                            <option value="draft"
                                {{ request('status') == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                        </select>

                    </div>


                    {{-- STOCK --}}
                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Stock
                        </label>

                        <select
                            name="stock_status"
                            id="stock_status"
                            class="form-select">

                            <option value="">
                                All Stock
                            </option>

                            <option value="in_stock"
                                {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>
                                In Stock
                            </option>

                            <option value="low_stock"
                                {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>
                                Low Stock
                            </option>

                            <option value="out_of_stock"
                                {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                Out of Stock
                            </option>

                        </select>

                    </div>


                    {{-- MIN PRICE --}}
                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Min Price
                        </label>

                        <input
                            type="number"
                            name="min_price"
                            id="min_price"
                            class="form-control"
                            placeholder="Min Price"
                            value="{{ request('min_price') }}">

                    </div>


                    {{-- MAX PRICE --}}
                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Max Price
                        </label>

                        <input
                            type="number"
                            name="max_price"
                            id="max_price"
                            class="form-control"
                            placeholder="Max Price"
                            value="{{ request('max_price') }}">

                    </div>


                    {{-- CATEGORIES --}}
                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Categories
                        </label>

                        <div class="border rounded p-3"
                             style="max-height: 180px; overflow-y: auto;">

                            @forelse($categories as $cat)

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input filter-checkbox"
                                        type="checkbox"
                                        name="category[]"
                                        value="{{ $cat }}"
                                        id="category_{{ md5($cat) }}"
                                        {{ in_array($cat, (array) request('category', [])) ? 'checked' : '' }}>

                                    <label
                                        class="form-check-label"
                                        for="category_{{ md5($cat) }}">

                                        {{ $cat }}

                                    </label>

                                </div>

                            @empty

                                <span class="text-muted">
                                    No categories available
                                </span>

                            @endforelse

                        </div>

                    </div>


                    {{-- SIZES --}}
                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Sizes
                        </label>

                        <div class="border rounded p-3"
                             style="max-height: 180px; overflow-y: auto;">

                            @forelse($sizes as $size)

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input filter-checkbox"
                                        type="checkbox"
                                        name="size[]"
                                        value="{{ $size }}"
                                        id="size_{{ md5($size) }}"
                                        {{ in_array($size, (array) request('size', [])) ? 'checked' : '' }}>

                                    <label
                                        class="form-check-label"
                                        for="size_{{ md5($size) }}">

                                        {{ $size }}

                                    </label>

                                </div>

                            @empty

                                <span class="text-muted">
                                    No sizes available
                                </span>

                            @endforelse

                        </div>

                    </div>


                    {{-- COLORS --}}
                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Colors
                        </label>

                        <div class="border rounded p-3"
                             style="max-height: 180px; overflow-y: auto;">

                            @forelse($colors as $color)

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input filter-checkbox"
                                        type="checkbox"
                                        name="color[]"
                                        value="{{ $color }}"
                                        id="color_{{ md5($color) }}"
                                        {{ in_array($color, (array) request('color', [])) ? 'checked' : '' }}>

                                    <label
                                        class="form-check-label"
                                        for="color_{{ md5($color) }}">

                                        {{ $color }}

                                    </label>

                                </div>

                            @empty

                                <span class="text-muted">
                                    No colors available
                                </span>

                            @endforelse

                        </div>

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-12">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fa-solid fa-filter me-1"></i>
                                Apply Filters

                            </button>

                            <a
                                href="{{ route('products.index') }}"
                                class="btn btn-outline-secondary">

                                <i class="fa-solid fa-xmark me-1"></i>
                                Clear All

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ACTIVE FILTERS --}}
    <div
        id="activeFiltersContainer"
        class="mb-4">

        <div class="d-flex align-items-center flex-wrap gap-2">

            <strong class="me-1">
                Active Filters:
            </strong>

            <div
                id="filterChips"
                class="d-flex flex-wrap gap-2">
            </div>

            <button
                type="button"
                id="clearAllFilters"
                class="btn btn-sm btn-outline-danger d-none">

                Clear All

            </button>

        </div>

    </div>


    {{-- LIVE RESULT COUNT --}}
    <div
        id="liveResultMessage"
        class="alert alert-light border shadow-sm mb-4">

        <i class="fa-solid fa-filter me-1"></i>

        Showing
        <strong id="liveResultCount">
            {{ $products->total() }}
        </strong>

        matching product(s).

        <span
            id="countLoading"
            class="spinner-border spinner-border-sm ms-2 d-none"
            role="status">
        </span>

    </div>


    {{-- SORT --}}
    <div class="mb-3 row g-3 align-items-end">

        <div class="col-md-4">

            <label class="form-label fw-bold">
                Sort Products
            </label>

            <select
                id="sort"
                class="form-select">

                <option value="">
                    Default Sorting
                </option>

                <option value="low-high"
                    {{ request('sort') == 'low-high' ? 'selected' : '' }}>
                    Price: Low to High
                </option>

                <option value="high-low"
                    {{ request('sort') == 'high-low' ? 'selected' : '' }}>
                    Price: High to Low
                </option>

                <option value="name_asc"
                    {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                    Name: A-Z
                </option>

                <option value="name_desc"
                    {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                    Name: Z-A
                </option>

                <option value="stock_high"
                    {{ request('sort') == 'stock_high' ? 'selected' : '' }}>
                    Stock: High to Low
                </option>

                <option value="stock_low"
                    {{ request('sort') == 'stock_low' ? 'selected' : '' }}>
                    Stock: Low to High
                </option>

            </select>

        </div>


        <div class="col-md-2">

            <button
                id="applySort"
                class="btn btn-outline-primary w-100">

                <i class="fa-solid fa-sort me-1"></i>
                Sort

            </button>

        </div>

    </div>


    {{-- PRODUCTS TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover mb-0 align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Name</th>

                            <th width="20%">
                                Details
                            </th>

                            <th>
                                Image
                            </th>

                            <th>
                                Size
                            </th>

                            <th>
                                Color
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Stock
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                <td class="fw-semibold">

                                    <a
                                        href="{{ route('products.show', $product) }}"
                                        class="text-decoration-none text-dark">

                                        {{ $product->name }}

                                    </a>

                                </td>


                                <td style="white-space: normal;">

                                    {{ Str::limit($product->details, 60) }}

                                </td>


                                <td>

                                    @if($product->image)

                                        <img
                                            src="{{ asset($product->image) }}"
                                            width="60"
                                            class="rounded shadow-sm border">

                                    @else

                                        <span class="text-muted">
                                            No Image
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $product->size }}
                                </td>


                                <td>
                                    {{ $product->color }}
                                </td>


                                <td>
                                    {{ $product->category }}
                                </td>


                                <td class="fw-bold text-success">

                                    ₹{{ number_format($product->price) }}

                                </td>


                                <td>

                                    @if($product->stock == 0)

                                        <span class="badge bg-danger">
                                            Out of Stock
                                        </span>

                                    @elseif($product->stock <= 5)

                                        <span class="badge bg-warning text-dark">
                                            {{ $product->stock }}
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            {{ $product->stock }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span
                                        class="badge bg-{{
                                            $product->status == 'active'
                                                ? 'success'
                                                : ($product->status == 'inactive'
                                                    ? 'secondary'
                                                    : 'warning')
                                        }}">

                                        {{ ucfirst($product->status) }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route('products.show', $product) }}"
                                        class="btn btn-info btn-sm me-1">

                                        View

                                    </a>


                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="btn btn-warning btn-sm me-1">

                                        Edit

                                    </a>


                                    <button
                                        class="btn btn-outline-success btn-sm me-1"
                                        onclick="addToComparison({{ $product->id }})">

                                        Compare

                                    </button>


                                    <form
                                        action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this product?')">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-4 text-muted">

                                    No products found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- PAGINATION --}}
    <div class="mt-4 d-flex justify-content-center">

        <nav aria-label="Product pagination">

            <ul class="pagination pagination-sm">

                @if($products->onFirstPage())

                    <li class="page-item disabled">

                        <span class="page-link">
                            &laquo;
                        </span>

                    </li>

                @else

                    <li class="page-item">

                        <a
                            class="page-link"
                            href="{{ $products->appends(request()->query())->previousPageUrl() }}">

                            &laquo;

                        </a>

                    </li>

                @endif


                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)

                    <li
                        class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">

                        <a
                            class="page-link"
                            href="{{ $url }}">

                            {{ $page }}

                        </a>

                    </li>

                @endforeach


                @if($products->hasMorePages())

                    <li class="page-item">

                        <a
                            class="page-link"
                            href="{{ $products->appends(request()->query())->nextPageUrl() }}">

                            &raquo;

                        </a>

                    </li>

                @else

                    <li class="page-item disabled">

                        <span class="page-link">
                            &raquo;
                        </span>

                    </li>

                @endif

            </ul>

        </nav>

    </div>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('filterForm');

    const search = document.getElementById('search');

    const status = document.getElementById('status');

    const stockStatus = document.getElementById('stock_status');

    const minPrice = document.getElementById('min_price');

    const maxPrice = document.getElementById('max_price');

    const sort = document.getElementById('sort');

    const applySort = document.getElementById('applySort');

    const filterChips = document.getElementById('filterChips');

    const clearAllFilters =
        document.getElementById('clearAllFilters');

    const liveResultCount =
        document.getElementById('liveResultCount');

    const productCount =
        document.getElementById('productCount');

    const countLoading =
        document.getElementById('countLoading');


    /*
    |--------------------------------------------------------------------------
    | Get all form parameters
    |--------------------------------------------------------------------------
    */

    function getFormParameters() {

        return new URLSearchParams(
            new FormData(form)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Update Active Filter Chips
    |--------------------------------------------------------------------------
    */

    function updateFilterChips() {

        filterChips.innerHTML = '';

        let hasFilters = false;


        /*
        | Search
        */

        if (search.value.trim() !== '') {

            hasFilters = true;

            createChip(
                'Search',
                search.value,
                function () {

                    search.value = '';

                    refreshCount();

                    updateFilterChips();

                }
            );

        }


        /*
        | Categories
        */

        document
            .querySelectorAll('input[name="category[]"]:checked')
            .forEach(function (checkbox) {

                hasFilters = true;

                createChip(
                    'Category',
                    checkbox.value,
                    function () {

                        checkbox.checked = false;

                        refreshCount();

                        updateFilterChips();

                    }
                );

            });


        /*
        | Sizes
        */

        document
            .querySelectorAll('input[name="size[]"]:checked')
            .forEach(function (checkbox) {

                hasFilters = true;

                createChip(
                    'Size',
                    checkbox.value,
                    function () {

                        checkbox.checked = false;

                        refreshCount();

                        updateFilterChips();

                    }
                );

            });


        /*
        | Colors
        */

        document
            .querySelectorAll('input[name="color[]"]:checked')
            .forEach(function (checkbox) {

                hasFilters = true;

                createChip(
                    'Color',
                    checkbox.value,
                    function () {

                        checkbox.checked = false;

                        refreshCount();

                        updateFilterChips();

                    }
                );

            });


        /*
        | Status
        */

        if (status.value !== '') {

            hasFilters = true;

            createChip(
                'Status',
                status.options[status.selectedIndex].text,
                function () {

                    status.value = '';

                    refreshCount();

                    updateFilterChips();

                }
            );

        }


        /*
        | Stock
        */

        if (stockStatus.value !== '') {

            hasFilters = true;

            createChip(
                'Stock',
                stockStatus.options[
                    stockStatus.selectedIndex
                ].text,
                function () {

                    stockStatus.value = '';

                    refreshCount();

                    updateFilterChips();

                }
            );

        }


        /*
        | Min Price
        */

        if (minPrice.value !== '') {

            hasFilters = true;

            createChip(
                'Min Price',
                '₹' + minPrice.value,
                function () {

                    minPrice.value = '';

                    refreshCount();

                    updateFilterChips();

                }
            );

        }


        /*
        | Max Price
        */

        if (maxPrice.value !== '') {

            hasFilters = true;

            createChip(
                'Max Price',
                '₹' + maxPrice.value,
                function () {

                    maxPrice.value = '';

                    refreshCount();

                    updateFilterChips();

                }
            );

        }


        /*
        | Show / hide clear button
        */

        if (hasFilters) {

            clearAllFilters.classList.remove('d-none');

        } else {

            clearAllFilters.classList.add('d-none');

            filterChips.innerHTML =
                '<span class="text-muted">No active filters</span>';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Create Filter Chip
    |--------------------------------------------------------------------------
    */

    function createChip(label, value, removeCallback) {

        const chip = document.createElement('span');

        chip.className =
            'badge rounded-pill bg-primary d-inline-flex align-items-center gap-2 px-3 py-2';

        chip.innerHTML = `
            ${escapeHtml(label)}: ${escapeHtml(value)}
            <button
                type="button"
                class="btn-close btn-close-white"
                style="font-size: 0.55rem;"
                aria-label="Remove filter">
            </button>
        `;

        chip
            .querySelector('button')
            .addEventListener('click', removeCallback);

        filterChips.appendChild(chip);

    }


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /*
    |--------------------------------------------------------------------------
    | AJAX Live Result Count
    |--------------------------------------------------------------------------
    */

    let countTimer = null;

    function refreshCount() {

        clearTimeout(countTimer);

        countTimer = setTimeout(function () {

            countLoading.classList.remove('d-none');

            const params = getFormParameters();

            /*
            | We don't need the page number for the live count.
            */

            params.delete('page');

            /*
            | Send AJAX request to the same products index route.
            */

            fetch(
                "{{ route('products.index') }}?" +
                params.toString(),
                {
                    method: 'GET',

                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            )
            .then(function (response) {

                if (!response.ok) {
                    throw new Error(
                        'Unable to calculate product count.'
                    );
                }

                return response.json();

            })
            .then(function (data) {

                liveResultCount.textContent = data.count;

                productCount.textContent = data.count;

            })
            .catch(function (error) {

                console.error(
                    'Live count error:',
                    error
                );

            })
            .finally(function () {

                countLoading.classList.add('d-none');

            });

        }, 300);

    }


    /*
    |--------------------------------------------------------------------------
    | Checkbox Changes
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.filter-checkbox')
        .forEach(function (checkbox) {

            checkbox.addEventListener(
                'change',
                function () {

                    updateFilterChips();

                    refreshCount();

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Status / Stock Changes
    |--------------------------------------------------------------------------
    */

    status.addEventListener(
        'change',
        function () {

            updateFilterChips();

            refreshCount();

        }
    );


    stockStatus.addEventListener(
        'change',
        function () {

            updateFilterChips();

            refreshCount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Price Changes
    |--------------------------------------------------------------------------
    */

    minPrice.addEventListener(
        'input',
        function () {

            updateFilterChips();

            refreshCount();

        }
    );


    maxPrice.addEventListener(
        'input',
        function () {

            updateFilterChips();

            refreshCount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Search Changes
    |--------------------------------------------------------------------------
    */

    search.addEventListener(
        'input',
        function () {

            updateFilterChips();

            refreshCount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Clear All Filters
    |--------------------------------------------------------------------------
    */

    clearAllFilters.addEventListener(
        'click',
        function () {

            /*
            | Clear text inputs
            */

            search.value = '';

            minPrice.value = '';

            maxPrice.value = '';


            /*
            | Clear selects
            */

            status.value = '';

            stockStatus.value = '';


            /*
            | Clear checkboxes
            */

            document
                .querySelectorAll('.filter-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked = false;

                });


            updateFilterChips();

            refreshCount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

    applySort.addEventListener(
        'click',
        function () {

            const url =
                new URL(window.location.href);

            if (sort.value) {

                url.searchParams.set(
                    'sort',
                    sort.value
                );

            } else {

                url.searchParams.delete('sort');

            }

            /*
            | Preserve current filters.
            */

            const params =
                new URLSearchParams(
                    new FormData(form)
                );

            /*
            | Remove existing filter parameters
            | before applying the form parameters.
            */

            [
                'search',
                'category',
                'size',
                'color',
                'status',
                'min_price',
                'max_price',
                'stock_status'
            ].forEach(function (key) {

                url.searchParams.delete(key);

            });


            /*
            | Add all current form values.
            */

            params.forEach(function (value, key) {

                if (value !== '') {

                    url.searchParams.append(
                        key,
                        value
                    );

                }

            });


            window.location.href =
                url.toString();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Chips
    |--------------------------------------------------------------------------
    */

    updateFilterChips();

});

</script>

@endpush