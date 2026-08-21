<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display products with search, multi-value filters,
     * sorting and pagination.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Multiple Category Filter
        |--------------------------------------------------------------------------
        */
        $categoriesFilter = $request->input('category', []);

        if (!is_array($categoriesFilter)) {
            $categoriesFilter = [$categoriesFilter];
        }

        $categoriesFilter = array_filter($categoriesFilter);

        if (!empty($categoriesFilter)) {
            $query->whereIn('category', $categoriesFilter);
        }

        /*
        |--------------------------------------------------------------------------
        | Multiple Size Filter
        |--------------------------------------------------------------------------
        */
        $sizesFilter = $request->input('size', []);

        if (!is_array($sizesFilter)) {
            $sizesFilter = [$sizesFilter];
        }

        $sizesFilter = array_filter($sizesFilter);

        if (!empty($sizesFilter)) {
            $query->whereIn('size', $sizesFilter);
        }

        /*
        |--------------------------------------------------------------------------
        | Multiple Color Filter
        |--------------------------------------------------------------------------
        */
        $colorsFilter = $request->input('color', []);

        if (!is_array($colorsFilter)) {
            $colorsFilter = [$colorsFilter];
        }

        $colorsFilter = array_filter($colorsFilter);

        if (!empty($colorsFilter)) {
            $query->whereIn('color', $colorsFilter);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('stock_status')) {

            if ($request->stock_status === 'out_of_stock') {
                $query->where('stock', 0);
            }

            if ($request->stock_status === 'low_stock') {
                $query->whereBetween('stock', [1, 5]);
            }

            if ($request->stock_status === 'in_stock') {
                $query->where('stock', '>', 5);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        switch ($request->sort) {

            case 'high-low':
                $query->orderBy('price', 'desc');
                break;

            case 'low-high':
                $query->orderBy('price', 'asc');
                break;

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            case 'stock_high':
                $query->orderBy('stock', 'desc');
                break;

            case 'stock_low':
                $query->orderBy('stock', 'asc');
                break;

            default:
                $query->latest();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | AJAX Result Count
        |--------------------------------------------------------------------------
        |
        | When the filter form sends an AJAX request, return only the
        | number of matching products instead of loading the page.
        |
        */
        if ($request->ajax()) {
            return response()->json([
                'count' => $query->count(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $products = $query
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */
        $categories = Product::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $sizes = Product::select('size')
            ->distinct()
            ->orderBy('size')
            ->pluck('size');

        $colors = Product::select('color')
            ->distinct()
            ->orderBy('color')
            ->pluck('color');

        return view('products.index', compact(
            'products',
            'categories',
            'sizes',
            'colors'
        ));
    }

    /**
     * Display a single product.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'details'  => 'required|string',
            'size'     => 'required|string|max:255',
            'color'    => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'status'   => 'required|in:active,inactive,draft',
            'image'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create images directory
        |--------------------------------------------------------------------------
        */
        $imageDirectory = public_path('images');

        if (!is_dir($imageDirectory)) {
            mkdir($imageDirectory, 0755, true);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload image
        |--------------------------------------------------------------------------
        */
        $imageName = time() . '_' . uniqid() . '.' .
            $request->file('image')->extension();

        $request->file('image')->move(
            $imageDirectory,
            $imageName
        );

        $imagePath = 'images/' . $imageName;

        /*
        |--------------------------------------------------------------------------
        | Create product
        |--------------------------------------------------------------------------
        */
        $product = Product::create([
            'name'     => $validated['name'],
            'details'  => $validated['details'],
            'size'     => $validated['size'],
            'color'    => $validated['color'],
            'category' => $validated['category'],
            'price'    => $validated['price'],
            'stock'    => $validated['stock'],
            'status'   => $validated['status'],
            'image'    => $imagePath,
            'images'   => [$imagePath],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */
        activity()
            ->causedBy(Auth::user())
            ->performedOn($product)
            ->log('created');

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product form.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'details'  => 'required|string',
            'size'     => 'required|string|max:255',
            'color'    => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'status'   => 'required|in:active,inactive,draft',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $product->image;

        /*
        |--------------------------------------------------------------------------
        | Replace old image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {

            if (
                $product->image &&
                file_exists(public_path($product->image))
            ) {
                unlink(public_path($product->image));
            }

            $imageDirectory = public_path('images');

            if (!is_dir($imageDirectory)) {
                mkdir($imageDirectory, 0755, true);
            }

            $imageName = time() . '_' . uniqid() . '.' .
                $request->file('image')->extension();

            $request->file('image')->move(
                $imageDirectory,
                $imageName
            );

            $imagePath = 'images/' . $imageName;
        }

        /*
        |--------------------------------------------------------------------------
        | Update product
        |--------------------------------------------------------------------------
        */
        $product->update([
            'name'     => $validated['name'],
            'details'  => $validated['details'],
            'size'     => $validated['size'],
            'color'    => $validated['color'],
            'category' => $validated['category'],
            'price'    => $validated['price'],
            'stock'    => $validated['stock'],
            'status'   => $validated['status'],
            'image'    => $imagePath,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Keep images JSON in sync
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {
            $product->update([
                'images' => [$imagePath],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */
        activity()
            ->causedBy(Auth::user())
            ->performedOn($product)
            ->log('updated');

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete physical image
        |--------------------------------------------------------------------------
        */
        if (
            $product->image &&
            file_exists(public_path($product->image))
        ) {
            unlink(public_path($product->image));
        }

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */
        activity()
            ->causedBy(Auth::user())
            ->performedOn($product)
            ->log('deleted');

        /*
        |--------------------------------------------------------------------------
        | Soft Delete
        |--------------------------------------------------------------------------
        */
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}