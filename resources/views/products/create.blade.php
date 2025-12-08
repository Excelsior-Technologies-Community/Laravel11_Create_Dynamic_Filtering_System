@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Product</h1>

    <!-- Main form for creating new product - multipart/form-data for image upload support -->
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf  <!-- Laravel CSRF protection token for security -->

        <!-- PRODUCT NAME INPUT FIELD -->
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control" required>
            <!-- 'required' ensures browser validation before submission -->
        </div>

        <!-- PRODUCT DETAILS TEXTAREA -->
        <div class="mb-3">
            <label class="form-label fw-bold">Details</label>
            <textarea name="details" class="form-control" required></textarea>
            <!-- Textarea allows multi-line product description -->
        </div>

        <!-- SINGLE IMAGE FILE UPLOAD (REQUIRED) -->
        <div class="mb-3">
            <label class="form-label fw-bold">Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
            <!-- accept="image/*" restricts file picker to images only -->
        </div>

        <!-- PRODUCT SIZE INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Size</label>
            <input type="text" name="size" class="form-control" required>
            <!-- Example: "S", "M", "L", "38", "40" etc. -->
        </div>

        <!-- PRODUCT COLOR INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Color</label>
            <input type="text" name="color" class="form-control" required>
            <!-- Example: "Red", "Blue", "Black" etc. -->
        </div>

        <!-- PRODUCT CATEGORY INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Category</label>
            <input type="text" name="category" class="form-control" required>
            <!-- Example: "Shirt", "Jeans", "Shoes" etc. -->
        </div>

        <!-- PRODUCT PRICE NUMBER INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Price</label>
            <input type="number" name="price" class="form-control" required>
            <!-- type="number" ensures only numeric values, no letters -->
        </div>

        <!-- FORM SUBMISSION BUTTONS -->
        <button type="submit" class="btn btn-primary">Create Product</button>
        <!-- Back button links to products listing page -->
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-2">Back</a>
    </form>
</div>
@endsection
