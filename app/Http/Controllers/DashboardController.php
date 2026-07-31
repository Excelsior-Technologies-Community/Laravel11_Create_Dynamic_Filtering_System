<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $inactiveProducts = Product::where('status', 'inactive')->count();
        $draftProducts = Product::where('status', 'draft')->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();

        $categoryStats = Product::select('category', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        try {
            if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
                $recentActivities = \Spatie\Activitylog\Models\Activity::latest()
                    ->where('subject_type', \App\Models\Product::class)
                    ->take(10)
                    ->get();
            } else {
                $recentActivities = collect();
            }
        } catch (\Throwable $e) {
            $recentActivities = collect();
        }

        return view('dashboard', compact(
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'draftProducts',
            'outOfStockProducts',
            'lowStockProducts',
            'categoryStats',
            'recentActivities'
        ));
    }
}
