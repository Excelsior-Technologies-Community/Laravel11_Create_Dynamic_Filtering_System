<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ComparisonController extends Controller
{
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $comparison = Session::get('product_comparison', []);

        if (!in_array($id, $comparison)) {
            $comparison[] = $id;
            Session::put('product_comparison', $comparison);
        }

        return response()->json(['success' => true, 'count' => count($comparison)]);
    }

    public function remove(Request $request, $id)
    {
        $comparison = Session::get('product_comparison', []);
        $comparison = array_values(array_filter($comparison, fn($item) => $item != $id));
        Session::put('product_comparison', $comparison);

        return response()->json(['success' => true, 'count' => count($comparison)]);
    }

    public function index()
    {
        $ids = Session::get('product_comparison', []);
        $products = Product::whereIn('id', $ids)->get();
        return view('comparison.index', compact('products'));
    }

    public function count()
    {
        $comparison = Session::get('product_comparison', []);
        return response()->json(['count' => count($comparison)]);
    }
}
