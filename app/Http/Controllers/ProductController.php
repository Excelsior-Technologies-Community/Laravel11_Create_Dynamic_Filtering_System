<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * Display products with search, filters,
     * sorting, pagination and per-page selection.
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
            $search = $request->input('search');

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
        | Category Filter
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
        | Size Filter
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
        | Color Filter
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
            $query->where('status', $request->input('status'));
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */
        if ($request->filled('min_price')) {
            $query->where(
                'price',
                '>=',
                $request->input('min_price')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */
        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                $request->input('max_price')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('stock_status')) {

            switch ($request->input('stock_status')) {

                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;

                case 'low_stock':
                    $query->whereBetween('stock', [1, 5]);
                    break;

                case 'in_stock':
                    $query->where('stock', '>', 5);
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        switch ($request->input('sort')) {

            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;

            case 'id_desc':
                $query->orderBy('id', 'desc');
                break;

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
                $query->orderBy('id', 'desc');
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | AJAX Count
        |--------------------------------------------------------------------------
        */
        if ($request->ajax()) {
            return response()->json([
                'count' => $query->count(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Per Page
        |--------------------------------------------------------------------------
        */
        $allowedPerPage = [5, 10, 25, 50, 100];

        $perPage = (int) $request->input('per_page', 5);

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $products = $query
            ->paginate($perPage)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */
        $categories = Product::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category', 'asc')
            ->pluck('category');

        $sizes = Product::query()
            ->select('size')
            ->whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->orderBy('size', 'asc')
            ->pluck('size');

        $colors = Product::query()
            ->select('color')
            ->whereNotNull('color')
            ->where('color', '!=', '')
            ->distinct()
            ->orderBy('color', 'asc')
            ->pluck('color');

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view('products.index', compact(
            'products',
            'categories',
            'sizes',
            'colors',
            'perPage'
        ));
    }

    /**
     * Export filtered products to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->input('search');

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
        | Category
        |--------------------------------------------------------------------------
        */
        $categories = $request->input('category', []);

        if (!is_array($categories)) {
            $categories = [$categories];
        }

        $categories = array_filter($categories);

        if (!empty($categories)) {
            $query->whereIn('category', $categories);
        }

        /*
        |--------------------------------------------------------------------------
        | Size
        |--------------------------------------------------------------------------
        */
        $sizes = $request->input('size', []);

        if (!is_array($sizes)) {
            $sizes = [$sizes];
        }

        $sizes = array_filter($sizes);

        if (!empty($sizes)) {
            $query->whereIn('size', $sizes);
        }

        /*
        |--------------------------------------------------------------------------
        | Color
        |--------------------------------------------------------------------------
        */
        $colors = $request->input('color', []);

        if (!is_array($colors)) {
            $colors = [$colors];
        }

        $colors = array_filter($colors);

        if (!empty($colors)) {
            $query->whereIn('color', $colors);
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        /*
        |--------------------------------------------------------------------------
        | Price
        |--------------------------------------------------------------------------
        */
        if ($request->filled('min_price')) {
            $query->where(
                'price',
                '>=',
                $request->input('min_price')
            );
        }

        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                $request->input('max_price')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */
        if ($request->filled('stock_status')) {

            switch ($request->input('stock_status')) {

                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;

                case 'low_stock':
                    $query->whereBetween('stock', [1, 5]);
                    break;

                case 'in_stock':
                    $query->where('stock', '>', 5);
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        switch ($request->input('sort')) {

            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;

            case 'id_desc':
                $query->orderBy('id', 'desc');
                break;

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
                $query->orderBy('id', 'desc');
                break;
        }

        $products = $query->get();

        $fileName = 'products_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(
            function () use ($products) {

                $handle = fopen('php://output', 'w');

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */
                fputcsv($handle, [
                    'ID',
                    'Name',
                    'Category',
                    'Size',
                    'Color',
                    'Price',
                    'Stock',
                    'Status',
                    'Created At',
                ]);

                /*
                |--------------------------------------------------------------------------
                | CSV Rows
                |--------------------------------------------------------------------------
                */
                foreach ($products as $product) {

                    fputcsv($handle, [
                        $product->id,
                        $product->name,
                        $product->category,
                        $product->size,
                        $product->color,
                        $product->price,
                        $product->stock,
                        ucfirst($product->status),
                        optional($product->created_at)
                            ->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            },
            $fileName,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /**
     * Bulk delete products.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
            ],
            'ids.*' => [
                'integer',
                'exists:products,id',
            ],
        ]);

        $products = Product::whereIn(
            'id',
            $validated['ids']
        )->get();

        foreach ($products as $product) {

            /*
            |--------------------------------------------------------------------------
            | Delete Physical Image
            |--------------------------------------------------------------------------
            */
            if (
                $product->image &&
                file_exists(public_path($product->image))
            ) {
                @unlink(public_path($product->image));
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
        }

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                count($validated['ids']) .
                    ' product(s) deleted successfully.'
            );
    }

    /**
     * Display a single product.
     */
    public function show(Product $product)
    {
        return view(
            'products.show',
            compact('product')
        );
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
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'details' => [
                'required',
                'string',
            ],
            'size' => [
                'required',
                'string',
                'max:255',
            ],
            'color' => [
                'required',
                'string',
                'max:255',
            ],
            'category' => [
                'required',
                'string',
                'max:255',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'status' => [
                'required',
                'in:active,inactive,draft',
            ],
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Image Directory
        |--------------------------------------------------------------------------
        */
        $imageDirectory = public_path('images');

        if (!is_dir($imageDirectory)) {
            mkdir(
                $imageDirectory,
                0755,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */
        $imageName = time()
            . '_'
            . uniqid()
            . '.'
            . $request->file('image')->extension();

        $request->file('image')->move(
            $imageDirectory,
            $imageName
        );

        $imagePath = 'images/' . $imageName;

        /*
        |--------------------------------------------------------------------------
        | Create Product
        |--------------------------------------------------------------------------
        */
        $product = Product::create([
            'name' => $validated['name'],
            'details' => $validated['details'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'image' => $imagePath,
            'images' => [$imagePath],
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
            ->with(
                'success',
                'Product created successfully.'
            );
    }

    /**
     * Show edit product form.
     */
    public function edit(Product $product)
    {
        return view(
            'products.edit',
            compact('product')
        );
    }

    /**
     * Update product.
     */
    public function update(
        Request $request,
        Product $product
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'details' => [
                'required',
                'string',
            ],
            'size' => [
                'required',
                'string',
                'max:255',
            ],
            'color' => [
                'required',
                'string',
                'max:255',
            ],
            'category' => [
                'required',
                'string',
                'max:255',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'status' => [
                'required',
                'in:active,inactive,draft',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $imagePath = $product->image;

        /*
        |--------------------------------------------------------------------------
        | Replace Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {

            if (
                $product->image &&
                file_exists(public_path($product->image))
            ) {
                @unlink(
                    public_path($product->image)
                );
            }

            $imageDirectory = public_path('images');

            if (!is_dir($imageDirectory)) {
                mkdir(
                    $imageDirectory,
                    0755,
                    true
                );
            }

            $imageName = time()
                . '_'
                . uniqid()
                . '.'
                . $request->file('image')->extension();

            $request->file('image')->move(
                $imageDirectory,
                $imageName
            );

            $imagePath = 'images/' . $imageName;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Product
        |--------------------------------------------------------------------------
        */
        $product->update([
            'name' => $validated['name'],
            'details' => $validated['details'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'image' => $imagePath,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Images JSON
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
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Physical Image
        |--------------------------------------------------------------------------
        */
        if (
            $product->image &&
            file_exists(public_path($product->image))
        ) {
            @unlink(
                public_path($product->image)
            );
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
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }
}
