<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

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

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
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
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Product::select('category')->distinct()->pluck('category');
        $sizes = Product::select('size')->distinct()->pluck('size');
        $colors = Product::select('color')->distinct()->pluck('color');

        $maxPrice = Product::max('price') ?: 1000;

        if ($request->wantsJson()) {
            return response()->json([
                'products' => $products,
                'categories' => $categories,
                'sizes' => $sizes,
                'colors' => $colors,
                'maxPrice' => $maxPrice,
            ]);
        }

        if (Auth::check() && $request->filled('search')) {
            $recent = $request->session()->get('recent_searches', []);
            $recent = array_filter($recent, fn($item) => $item !== $request->search);
            array_unshift($recent, $request->search);
            $recent = array_slice($recent, 0, 5);
            $request->session()->put('recent_searches', $recent);
        }

        $recentSearches = $request->session()->get('recent_searches', []);

        return view('customer.index', compact('products', 'categories', 'sizes', 'colors', 'maxPrice', 'recentSearches'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }
        return view('customer.show', compact('product'));
    }
}
