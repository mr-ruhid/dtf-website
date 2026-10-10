<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\OrderDownloadController;
use App\Http\Controllers\GalleryController;
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

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/design', [DesignController::class, 'index'])->name('design.index');
Route::post('/design/temp-upload', [DesignController::class, 'tempUpload'])->name('design.temp-upload');
Route::post('/design/admin-save', [DesignController::class, 'adminSave'])->name('design.admin-save');
Route::get('/design/{slug}', [DesignController::class, 'index'])->name('design.product');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/model/{slug}/{category?}', [ModelController::class, 'show'])->name('model.show');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/items', [CartController::class, 'items'])->name('items');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{key}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{key}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('show');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
    Route::post('/receipt/{orderNumber}', [ReceiptController::class, 'upload'])->name('receipt');
});

Route::prefix('track')->name('track.')->group(function () {
    Route::get('/', [TrackController::class, 'form'])->name('form');
    Route::post('/', [TrackController::class, 'lookup'])->name('lookup');
    Route::get('/{token}', [TrackController::class, 'show'])->name('show');
});

Route::prefix('order/{token}')->name('order.download.')->group(function () {
    Route::get('/download', [OrderDownloadController::class, 'page'])->name('page');
    Route::get('/download/file/{designId}', [OrderDownloadController::class, 'file'])->name('file');
    Route::get('/download/zip', [OrderDownloadController::class, 'zip'])->name('zip');
});

Route::get('/{slug}', [ModelController::class, 'show'])->name('model.legacy');