@extends('layouts.admin')

@section('content')

<style>
    /* =========================================================
       PRODUCTS PAGE
    ========================================================== */

    .products-page {
        background: #f6f8fb;
        min-height: calc(100vh - 70px);
        padding: 28px 0 50px;
    }

    /* Header */
    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.5px;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 14px;
    }

    .btn-add-product {
        border: 0;
        border-radius: 10px;
        padding: 11px 18px;
        font-weight: 600;
        background: #4f46e5;
        color: #fff;
        transition: all .2s ease;
    }

    .btn-add-product:hover {
        background: #4338ca;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, .20);
    }

    /* Cards */
    .modern-card {
        background: #fff;
        border: 1px solid #e8ecf2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    /* Filter card */
    .filter-card {
        padding: 22px;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .filter-subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .form-label-modern {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 7px;
    }

    .modern-input,
    .modern-select {
        height: 42px;
        border: 1px solid #dfe4ea;
        border-radius: 9px;
        font-size: 13px;
        background-color: #fff;
        color: #374151;
        transition: all .2s ease;
    }

    .modern-input:focus,
    .modern-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
        pointer-events: none;
    }

    .search-wrapper input {
        padding-left: 38px;
    }

    .btn-filter {
        height: 42px;
        border-radius: 9px;
        padding: 0 18px;
        font-weight: 600;
        font-size: 13px;
        background: #4f46e5;
        color: white;
        border: 0;
    }

    .btn-filter:hover {
        background: #4338ca;
        color: #fff;
    }

    .btn-reset {
        height: 42px;
        border-radius: 9px;
        padding: 0 18px;
        font-weight: 600;
        font-size: 13px;
        background: #fff;
        border: 1px solid #dfe4ea;
        color: #4b5563;
    }

    .btn-reset:hover {
        background: #f9fafb;
    }

    .btn-export {
        height: 42px;
        border-radius: 9px;
        padding: 0 18px;
        font-weight: 600;
        font-size: 13px;
        background: #047857;
        color: white;
        border: 0;
    }

    .btn-export:hover {
        background: #065f46;
        color: white;
    }

    /* Stats */
    .stat-card {
        background: #fff;
        border: 1px solid #e8ecf2;
        border-radius: 15px;
        padding: 19px;
        height: 100%;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .035);
    }

    .stat-icon {
        width: 43px;
        height: 43px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-bottom: 14px;
    }

    .stat-icon-purple {
        background: #eef2ff;
        color: #4f46e5;
    }

    .stat-icon-blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-icon-green {
        background: #ecfdf5;
        color: #059669;
    }

    .stat-icon-orange {
        background: #fff7ed;
        color: #ea580c;
    }

    .stat-label {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
    }

    /* Table */
    .products-card {
        overflow: hidden;
    }

    .products-header {
        padding: 19px 22px;
        border-bottom: 1px solid #edf0f4;
        background: #fff;
    }

    .products-title {
        font-size: 17px;
        font-weight: 750;
        color: #111827;
    }

    .products-count {
        font-size: 12px;
        color: #6b7280;
    }

    .table-modern {
        margin: 0;
        min-width: 1050px;
    }

    .table-modern thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        border-top: 0;
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 800;
        padding: 13px 16px;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 15px 16px;
        border-bottom: 1px solid #f0f2f5;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .table-modern tbody tr {
        transition: background .15s ease;
    }

    .table-modern tbody tr:hover {
        background: #fafbff;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: 0;
    }

    /* Product */
    .product-image {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .product-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .product-name {
        font-weight: 700;
        color: #111827;
        font-size: 13px;
        margin-bottom: 3px;
    }

    .product-details {
        color: #9ca3af;
        font-size: 11px;
        max-width: 240px;
    }

    .product-id {
        font-weight: 800;
        color: #111827;
        background: #f3f4f6;
        padding: 5px 8px;
        border-radius: 7px;
        font-size: 12px;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        background: #f5f3ff;
        color: #5b21b6;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #ede9fe;
    }

    .price-value {
        font-weight: 800;
        color: #111827;
        white-space: nowrap;
    }

    .size-value,
    .color-value {
        font-weight: 600;
        color: #4b5563;
    }

    /* Status */
    .status-badge,
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-active {
        background: #ecfdf5;
        color: #047857;
    }

    .status-active .status-dot {
        background: #10b981;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #4b5563;
    }

    .status-inactive .status-dot {
        background: #6b7280;
    }

    .status-draft {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-draft .status-dot {
        background: #f97316;
    }

    .stock-good {
        background: #ecfdf5;
        color: #047857;
    }

    .stock-low {
        background: #fffbeb;
        color: #b45309;
    }

    .stock-out {
        background: #fef2f2;
        color: #b91c1c;
    }

    /* Actions */
    .action-group {
        display: inline-flex;
        gap: 5px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all .18s ease;
        background: white;
        font-size: 13px;
    }

    .action-view {
        color: #2563eb;
    }

    .action-view:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .action-edit {
        color: #d97706;
    }

    .action-edit:hover {
        background: #fffbeb;
        border-color: #fde68a;
        color: #b45309;
    }

    .action-delete {
        color: #dc2626;
        cursor: pointer;
    }

    .action-delete:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    /* Empty state */
    .empty-state {
        padding: 70px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 16px;
        border-radius: 18px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .empty-title {
        font-size: 17px;
        font-weight: 800;
        color: #111827;
    }

    .empty-text {
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 20px;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 18px 22px;
        border-top: 1px solid #edf0f4;
        background: #fff;
    }

    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }

    .pagination .page-link {
        border: 1px solid #e5e7eb;
        border-radius: 8px !important;
        color: #4b5563;
        font-size: 12px;
        font-weight: 600;
        min-width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
    }

    .pagination .page-link:hover {
        background: #f5f3ff;
        color: #4f46e5;
        border-color: #c7d2fe;
    }

    .pagination .active .page-link {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }

    .pagination .disabled .page-link {
        color: #cbd5e1;
        background: #f8fafc;
    }

    /* Alerts */
    .modern-alert {
        border: 0;
        border-radius: 11px;
        font-size: 13px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .page-title {
            font-size: 24px;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1;
        }
    }

    @media (max-width: 575px) {
        .products-page {
            padding-top: 18px;
        }

        .page-header-mobile {
            align-items: flex-start !important;
        }

        .btn-add-product {
            width: 100%;
        }

        .filter-card {
            padding: 16px;
        }

        .stat-value {
            font-size: 20px;
        }

        .pagination-wrapper {
            padding: 15px;
        }
    }
</style>


<div class="products-page">

    <div class="container-fluid">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="d-flex flex-wrap justify-content-between gap-3 mb-4 page-header-mobile">

            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="font-size:25px;">📦</span>

                    <h1 class="page-title mb-0">
                        Products
                    </h1>
                </div>

                <div class="page-subtitle">
                    Manage, filter and organize your product catalog
                </div>
            </div>

            <div>
                <a href="{{ route('products.create') }}"
                    class="btn btn-add-product">

                    <span class="me-1">+</span>
                    Add Product

                </a>
            </div>

        </div>


        {{-- =====================================================
             SUCCESS MESSAGE
        ====================================================== --}}

        @if(session('success'))

        <div class="alert alert-success modern-alert alert-dismissible fade show mb-4"
            role="alert">

            <strong>Success!</strong>
            {{ session('success') }}

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        @endif


        {{-- =====================================================
             ERROR MESSAGE
        ====================================================== --}}

        @if(session('error'))

        <div class="alert alert-danger modern-alert alert-dismissible fade show mb-4"
            role="alert">

            <strong>Error!</strong>
            {{ session('error') }}

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        @endif


        {{-- =====================================================
             FILTER CARD
        ====================================================== --}}

        <div class="modern-card filter-card mb-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <div class="filter-title">
                        Product Filters
                    </div>

                    <div class="filter-subtitle">
                        Search and refine your product list
                    </div>
                </div>

                <div class="text-muted" style="font-size:20px;">
                    ⚙️
                </div>

            </div>


            <form method="GET"
                action="{{ route('products.index') }}">

                <div class="row g-3">

                    {{-- SEARCH --}}

                    <div class="col-xl-4 col-lg-6">

                        <label class="form-label-modern">
                            Search Products
                        </label>

                        <div class="search-wrapper">

                            <span class="search-icon">
                                🔍
                            </span>

                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control modern-input"
                                placeholder="Search name, details, category...">

                        </div>

                    </div>


                    {{-- CATEGORY --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Category
                        </label>

                        <select name="category[]"
                            class="form-select modern-select">

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                            <option value="{{ $category }}"
                                {{ in_array(
                                        $category,
                                        (array) request('category', [])
                                    ) ? 'selected' : '' }}>

                                {{ $category }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SIZE --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Size
                        </label>

                        <select name="size[]"
                            class="form-select modern-select">

                            <option value="">
                                All Sizes
                            </option>

                            @foreach($sizes as $size)

                            <option value="{{ $size }}"
                                {{ in_array(
                                        $size,
                                        (array) request('size', [])
                                    ) ? 'selected' : '' }}>

                                {{ $size }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- COLOR --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Color
                        </label>

                        <select name="color[]"
                            class="form-select modern-select">

                            <option value="">
                                All Colors
                            </option>

                            @foreach($colors as $color)

                            <option value="{{ $color }}"
                                {{ in_array(
                                        $color,
                                        (array) request('color', [])
                                    ) ? 'selected' : '' }}>

                                {{ $color }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Status
                        </label>

                        <select name="status"
                            class="form-select modern-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                            <option value="draft"
                                {{ request('status') === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                        </select>

                    </div>


                    {{-- MIN PRICE --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Min Price
                        </label>

                        <input type="number"
                            name="min_price"
                            value="{{ request('min_price') }}"
                            min="0"
                            step="0.01"
                            class="form-control modern-input"
                            placeholder="₹ 0">

                    </div>


                    {{-- MAX PRICE --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Max Price
                        </label>

                        <input type="number"
                            name="max_price"
                            value="{{ request('max_price') }}"
                            min="0"
                            step="0.01"
                            class="form-control modern-input"
                            placeholder="₹ 99999">

                    </div>


                    {{-- STOCK --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Stock
                        </label>

                        <select name="stock_status"
                            class="form-select modern-select">

                            <option value="">
                                All Stock
                            </option>

                            <option value="in_stock"
                                {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>
                                In Stock
                            </option>

                            <option value="low_stock"
                                {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>
                                Low Stock
                            </option>

                            <option value="out_of_stock"
                                {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>
                                Out of Stock
                            </option>

                        </select>

                    </div>


                    {{-- SORT --}}

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <label class="form-label-modern">
                            Sort By
                        </label>

                        <select name="sort"
                            class="form-select modern-select">

                            <option value="">
                                Latest
                            </option>

                            <option value="id_asc"
                                {{ request('sort') === 'id_asc' ? 'selected' : '' }}>
                                ID: Low → High
                            </option>

                            <option value="id_desc"
                                {{ request('sort') === 'id_desc' ? 'selected' : '' }}>
                                ID: High → Low
                            </option>

                            <option value="low-high"
                                {{ request('sort') === 'low-high' ? 'selected' : '' }}>
                                Price: Low → High
                            </option>

                            <option value="high-low"
                                {{ request('sort') === 'high-low' ? 'selected' : '' }}>
                                Price: High → Low
                            </option>

                            <option value="name_asc"
                                {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                                Name: A → Z
                            </option>

                            <option value="name_desc"
                                {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                                Name: Z → A
                            </option>

                            <option value="stock_high"
                                {{ request('sort') === 'stock_high' ? 'selected' : '' }}>
                                Stock: High → Low
                            </option>

                            <option value="stock_low"
                                {{ request('sort') === 'stock_low' ? 'selected' : '' }}>
                                Stock: Low → High
                            </option>

                        </select>

                    </div>


                    {{-- PER PAGE --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label-modern">
                            Items Per Page
                        </label>

                        <select name="per_page"
                            class="form-select modern-select">

                            @foreach([10, 25, 50, 100] as $number)

                            <option value="{{ $number }}"
                                {{ (int) $perPage === $number ? 'selected' : '' }}>

                                {{ $number }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-xl-7 col-lg-12">

                        <label class="form-label-modern d-none d-lg-block">
                            &nbsp;
                        </label>

                        <div class="d-flex flex-wrap gap-2 filter-actions">

                            <button type="submit"
                                class="btn btn-filter">

                                🔎
                                Apply Filters

                            </button>


                            <a href="{{ route('products.index') }}"
                                class="btn btn-reset">

                                ↻
                                Reset

                            </a>


                            <a href="{{ route('products.export', request()->query()) }}"
                                class="btn btn-export">

                                ↓
                                Export CSV

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>


        {{-- =====================================================
             STATISTICS
        ====================================================== --}}

        <div class="row g-3 mb-4">

            {{-- TOTAL --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon stat-icon-purple">
                        📦
                    </div>

                    <div class="stat-label">
                        Total Products
                    </div>

                    <div class="stat-value">
                        {{ number_format($products->total()) }}
                    </div>

                </div>

            </div>


            {{-- CURRENT PAGE --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon stat-icon-blue">
                        📄
                    </div>

                    <div class="stat-label">
                        Products on Current Page
                    </div>

                    <div class="stat-value">
                        {{ $products->count() }}
                    </div>

                </div>

            </div>


            {{-- PAGE --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon stat-icon-green">
                        #️⃣
                    </div>

                    <div class="stat-label">
                        Current Page
                    </div>

                    <div class="stat-value">

                        {{ $products->currentPage() }}

                        <span style="font-size:14px;color:#9ca3af;">
                            / {{ $products->lastPage() }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- PER PAGE --}}

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon stat-icon-orange">
                        ⚙️
                    </div>

                    <div class="stat-label">
                        Items Per Page
                    </div>

                    <div class="stat-value">
                        {{ $perPage }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PRODUCT TABLE
        ====================================================== --}}

        <div class="modern-card products-card">

            {{-- TABLE HEADER --}}

            <div class="products-header">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div>

                        <div class="products-title">
                            Product List
                        </div>

                        <div class="products-count">
                            Manage your products and inventory
                        </div>

                    </div>


                    <div class="products-count">

                        Showing

                        <strong>
                            {{ $products->firstItem() ?? 0 }}
                        </strong>

                        -

                        <strong>
                            {{ $products->lastItem() ?? 0 }}
                        </strong>

                        of

                        <strong>
                            {{ $products->total() }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}

            <div class="table-responsive">

                <table class="table table-modern align-middle">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Product</th>

                            <th>Category</th>

                            <th>Size</th>

                            <th>Color</th>

                            <th>Price</th>

                            <th>Stock</th>

                            <th>Status</th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                        <tr>

                            {{-- ID --}}

                            <td>

                                <span class="product-id">
                                    #{{ $product->id }}
                                </span>

                            </td>


                            {{-- PRODUCT --}}

                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    @if($product->image)

                                    <img src="{{ asset($product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="product-image">

                                    @else

                                    <div class="product-placeholder">
                                        📦
                                    </div>

                                    @endif


                                    <div>

                                        <div class="product-name">
                                            {{ $product->name }}
                                        </div>

                                        <div class="product-details">

                                            {{ \Illuminate\Support\Str::limit(
                                                    $product->details,
                                                    45
                                                ) }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- CATEGORY --}}

                            <td>

                                <span class="category-badge">

                                    {{ $product->category }}

                                </span>

                            </td>


                            {{-- SIZE --}}

                            <td>

                                <span class="size-value">
                                    {{ $product->size }}
                                </span>

                            </td>


                            {{-- COLOR --}}

                            <td>

                                <span class="color-value">
                                    {{ $product->color }}
                                </span>

                            </td>


                            {{-- PRICE --}}

                            <td>

                                <span class="price-value">

                                    ₹{{ number_format(
                                            $product->price,
                                            2
                                        ) }}

                                </span>

                            </td>


                            {{-- STOCK --}}

                            <td>

                                @if($product->stock == 0)

                                <span class="stock-badge stock-out">

                                    <span>●</span>
                                    Out of Stock

                                </span>

                                @elseif($product->stock <= 5)

                                    <span class="stock-badge stock-low">

                                    <span>●</span>
                                    {{ $product->stock }} Low

                                    </span>

                                    @else

                                    <span class="stock-badge stock-good">

                                        <span>●</span>
                                        {{ $product->stock }}

                                    </span>

                                    @endif

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($product->status === 'active')

                                <span class="status-badge status-active">

                                    <span class="status-dot"></span>

                                    Active

                                </span>

                                @elseif($product->status === 'inactive')

                                <span class="status-badge status-inactive">

                                    <span class="status-dot"></span>

                                    Inactive

                                </span>

                                @else

                                <span class="status-badge status-draft">

                                    <span class="status-dot"></span>

                                    Draft

                                </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-end">

                                <div class="action-group">

                                    {{-- VIEW --}}

                                    <a href="{{ route('products.show', $product) }}"
                                        class="action-btn action-view"
                                        title="View Product">

                                        👁

                                    </a>


                                    {{-- EDIT --}}

                                    <a href="{{ route('products.edit', $product) }}"
                                        class="action-btn action-edit"
                                        title="Edit Product">

                                        ✎

                                    </a>


                                    {{-- DELETE --}}

                                    <form action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                            class="action-btn action-delete"
                                            title="Delete Product">

                                            🗑

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="9">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        📦
                                    </div>

                                    <div class="empty-title">
                                        No Products Found
                                    </div>

                                    <div class="empty-text">
                                        No products match your current filters.
                                    </div>

                                    <a href="{{ route('products.create') }}"
                                        class="btn btn-add-product">

                                        + Add First Product

                                    </a>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}

            @if($products->hasPages())

            <div class="pagination-wrapper">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="text-muted small">

                        Showing

                        <strong>
                            {{ $products->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $products->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $products->total() }}
                        </strong>

                        products

                    </div>


                    <div>

                        {{ $products->onEachSide(1)->links() }}

                    </div>

                </div>

            </div>

            @endif

        </div>

    </div>

</div>

@endsection