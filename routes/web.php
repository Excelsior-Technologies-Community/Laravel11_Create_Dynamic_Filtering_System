<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerProductsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ComparisonController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Product CSV Export
    |--------------------------------------------------------------------------
    |
    | Keep this BEFORE the resource route.
    |
    */

    Route::get(
        '/products/export',
        [ProductController::class, 'export']
    )->name('products.export');

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete Products
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/products/bulk-delete',
        [ProductController::class, 'bulkDestroy']
    )->name('products.bulkDelete');

    /*
    |--------------------------------------------------------------------------
    | Products Resource
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'products',
        ProductController::class
    );

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Customer Product Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/customer/products',
    [CustomerProductsController::class, 'index']
)->name('customer.products');

Route::get(
    '/customer/products/{product}',
    [CustomerProductsController::class, 'show']
)->name('customer.products.show');


/*
|--------------------------------------------------------------------------
| Search Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/search/suggestions',
    [SearchController::class, 'suggestions']
)->name('search.suggestions');

Route::get(
    '/search/recent',
    [SearchController::class, 'recent']
)->name('search.recent');

Route::post(
    '/search/recent',
    [SearchController::class, 'saveRecent']
)->name('search.saveRecent');


/*
|--------------------------------------------------------------------------
| Comparison Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::post(
        '/comparison/add/{id}',
        [ComparisonController::class, 'add']
    )->name('comparison.add');

    Route::post(
        '/comparison/remove/{id}',
        [ComparisonController::class, 'remove']
    )->name('comparison.remove');

    Route::get(
        '/comparison',
        [ComparisonController::class, 'index']
    )->name('comparison.index');

    Route::get(
        '/comparison/count',
        [ComparisonController::class, 'count']
    )->name('comparison.count');
});


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
