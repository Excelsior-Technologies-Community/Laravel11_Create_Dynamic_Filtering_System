@extends('layouts.admin')

@section('content')

<div class="container py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                Create Product
            </h1>

            <p class="text-muted mb-0">
                Add a new product to your inventory.
            </p>
        </div>

        <a
            href="{{ route('products.index') }}"
            class="btn btn-secondary"
        >
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- CREATE PRODUCT FORM --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                <i class="fa-solid fa-box me-2"></i>
                Product Information
            </h5>

        </div>


        <div class="card-body">

            <form
                action="{{ route('products.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- PRODUCT NAME --}}
                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Enter product name"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- DETAILS --}}
                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Details
                    </label>

                    <textarea
                        name="details"
                        class="form-control @error('details') is-invalid @enderror"
                        rows="4"
                        placeholder="Enter product details"
                        required
                    >{{ old('details') }}</textarea>

                    @error('details')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- IMAGE --}}
                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Product Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        required
                    >

                    <small class="text-muted">
                        JPG, JPEG, PNG or WEBP. Maximum 2MB.
                    </small>

                    @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="row">

                    {{-- SIZE --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Size
                        </label>

                        <input
                            type="text"
                            name="size"
                            class="form-control @error('size') is-invalid @enderror"
                            value="{{ old('size') }}"
                            placeholder="Example: M, L, XL, 38, 40"
                            required
                        >

                        @error('size')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- COLOR --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Color
                        </label>

                        <input
                            type="text"
                            name="color"
                            class="form-control @error('color') is-invalid @enderror"
                            value="{{ old('color') }}"
                            placeholder="Example: Black, Blue, Red"
                            required
                        >

                        @error('color')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="row">

                    {{-- CATEGORY --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Category
                        </label>

                        <input
                            type="text"
                            name="category"
                            class="form-control @error('category') is-invalid @enderror"
                            value="{{ old('category') }}"
                            placeholder="Example: Books, Clothing, Electronics"
                            required
                        >

                        @error('category')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- PRICE --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Price
                        </label>

                        <input
                            type="number"
                            name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price') }}"
                            min="0"
                            step="0.01"
                            placeholder="Enter price"
                            required
                        >

                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="row">

                    {{-- STOCK --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Stock
                        </label>

                        <input
                            type="number"
                            name="stock"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock', 0) }}"
                            min="0"
                            placeholder="Enter stock quantity"
                            required
                        >

                        @error('stock')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Status
                            </option>

                            <option
                                value="active"
                                {{ old('status') === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                            <option
                                value="draft"
                                {{ old('status') === 'draft' ? 'selected' : '' }}
                            >
                                Draft
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- BUTTONS --}}
                <div class="d-flex gap-2 mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fa-solid fa-plus me-1"></i>
                        Create Product
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection