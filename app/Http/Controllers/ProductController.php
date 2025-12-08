<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products with dynamic price sorting functionality
    public function index(Request $request)
    {
        // Start with base query builder
        $query = Product::query();

        // PRICE SORTING LOGIC - supports high-to-low, low-to-high, or default latest
        if ($request->filled('sort')) {
            if ($request->sort === 'high-low') {
                // Sort by price descending (most expensive first)
                $query->orderBy('price', 'desc');
            } elseif ($request->sort === 'low-high') {
                // Sort by price ascending (cheapest first)
                $query->orderBy('price', 'asc');
            }
        } else {
            // Default: newest products first
            $query->latest();
        }

        // Execute query and get results
        $products = $query->get();

        // Return products index view with sorted products
        return view('products.index', compact('products'));
    }

    // Display create product form
    public function create()
    {
        // Simple create form - no data needed
        return view('products.create');
    }

    // Store new product with image upload
    public function store(Request $request)
    {
        // Validate all required fields with image size limit (2MB)
        $request->validate([
            'name'      => 'required',           // Product name required
            'details'   => 'required',           // Description required
            'size'      => 'required',           // Size required
            'color'     => 'required',           // Color required
            'category'  => 'required',           // Category required
            'price'     => 'required|numeric',   // Price must be numeric
            'image'     => 'required|image|max:2048'  // Required image, max 2MB
        ]);

        // Generate unique filename and upload single image
        $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Create new product record in database
        Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'image'     => 'images/' . $imageName,  // Store relative path
        ]);

        // Redirect to products list with success message
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    // Display edit form for specific product
    public function edit(Product $product)
    {
        // Route model binding automatically loads product by ID
        return view('products.edit', compact('product'));
    }

    // Update existing product
    public function update(Request $request, Product $product)
    {
        // Same validation as store, but image is now optional (nullable)
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'image'     => 'nullable|image|max:2048'  // Optional for updates
        ]);

        // Keep existing image path by default
        $imagePath = $product->image;

        // Handle new image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image file from server
            if (file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            // Upload new image with unique name
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            // Update with new image path
            $imagePath = 'images/' . $imageName;
        }

        // Update all product fields in database
        $product->update([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'image'     => $imagePath,
        ]);

        // Redirect to products list with success message
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    // Permanently delete product and its image
    public function destroy(Product $product)
    {
        // Delete associated image file from server if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Permanently remove product from database
        $product->delete();

        // Redirect to products list with success message
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
