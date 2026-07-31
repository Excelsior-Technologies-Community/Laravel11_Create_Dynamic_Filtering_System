<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        $suggestions = [];

        if ($query) {
            $products = Product::where('name', 'like', "%{$query}%")
                ->orWhere('category', 'like', "%{$query}%")
                ->orWhere('color', 'like', "%{$query}%")
                ->orWhere('details', 'like', "%{$query}%")
                ->select('id', 'name', 'category', 'color', 'price')
                ->limit(8)
                ->get();

            foreach ($products as $product) {
                $suggestions[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'color' => $product->color,
                    'price' => $product->price,
                    'url' => route('customer.products') . '?search=' . urlencode($product->name),
                ];
            }
        }

        return response()->json($suggestions);
    }

    public function recent(Request $request)
    {
        $recent = $request->session()->get('recent_searches', []);
        return response()->json($recent);
    }

    public function saveRecent(Request $request)
    {
        $search = trim($request->get('q'));
        if ($search) {
            $recent = $request->session()->get('recent_searches', []);
            $recent = array_filter($recent, fn($item) => $item !== $search);
            array_unshift($recent, $search);
            $recent = array_slice($recent, 0, 5);
            $request->session()->put('recent_searches', $recent);
        }
        return response()->json(['success' => true]);
    }
}
