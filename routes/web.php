
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [ProductController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// Route::get('/dashboard', function () {
//     $products = Product::all(); // fetch all products

//     // check if logged-in user is admin or normal user
//     if (Auth::user()->role === 'admin') {
//         return view('admin.dashboard', compact('products'));
//     } else {
//         return view('dashboard', compact('products'));
//     }
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/', [ProductController::class, 'index'])->name('products.list');

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
});
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
// });
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');

    
    Route::resource('products', ProductController::class)->names('admin.products');
});


require __DIR__.'/auth.php';
