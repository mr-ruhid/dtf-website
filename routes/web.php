<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DesignController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('page.home');
Route::get('/about-us', [PageController::class, 'about'])->name('page.about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('page.contact');
Route::get('/faq', [PageController::class, 'faq'])->name('page.faq');

Route::get('/terms-conditions', [PageController::class, 'terms'])->name('page.terms');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('page.privacy');
Route::get('/shipping-policy', [PageController::class, 'shipping'])->name('page.shipping');
Route::get('/return-policy', [PageController::class, 'returnPolicy'])->name('page.return');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/design', [DesignController::class, 'index'])->name('design.index');
Route::get('/design/{slug}', [DesignController::class, 'index'])->name('design.product');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/items', [CartController::class, 'items'])->name('items');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{key}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{key}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

Route::get('/{slug}', [ModelController::class, 'show'])->name('model.show');
