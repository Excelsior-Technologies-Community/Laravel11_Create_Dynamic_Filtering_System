<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('details', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($request->stock_status == 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
            } elseif ($request->stock_status == 'in_stock') {
                $query->where('stock', '>', 5);
            }
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'high-low') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort === 'low-high') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'name_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($request->sort === 'name_desc') {
                $query->orderBy('name', 'desc');
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(10)->withQueryString();

        $categories = Product::select('category')->distinct()->pluck('category');
        $sizes = Product::select('size')->distinct()->pluck('size');
        $colors = Product::select('color')->distinct()->pluck('color');

        return view('products.index', compact('products', 'categories', 'sizes', 'colors'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'status'    => 'required|in:active,inactive,draft',
            'image'     => 'required|image|max:2048',
        ]);

        $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $product = Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'stock'     => $request->stock,
            'status'    => $request->status,
            'image'     => 'images/' . $imageName,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($product)
            ->log('created');

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'stock'     => 'required|integer|min:0',
            'status'    => 'required|in:active,inactive,draft',
            'image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        }

        $product->update([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'stock'     => $request->stock,
            'status'    => $request->status,
            'image'     => $imagePath,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($product)
            ->log('updated');

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($product)
            ->log('deleted');

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
