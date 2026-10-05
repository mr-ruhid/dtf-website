<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('web')->group(function () {

    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');

        Route::get('2fa', [TwoFactorController::class, 'show'])->name('2fa.show');
        Route::post('2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
        Route::post('2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');
    });

    Route::middleware('admin')->group(function () {

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::put('profile/two-factor', [ProfileController::class, 'toggleTwoFactor'])->name('profile.two-factor');

        Route::resource('sliders', SliderController::class)->except(['show']);
        Route::post('sliders/{slider}/items', [SliderController::class, 'storeItem'])->name('sliders.items.store');
        Route::put('sliders/{slider}/items/{item}', [SliderController::class, 'updateItem'])->name('sliders.items.update');
        Route::delete('sliders/{slider}/items/{item}', [SliderController::class, 'destroyItem'])->name('sliders.items.destroy');

        Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
        Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::put('faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');
        Route::put('faqs/{faq}/toggle', [FaqController::class, 'toggleStatus'])->name('faqs.toggle');

        Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
        Route::put('gallery/{item}', [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('gallery/{item}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::put('gallery/{item}/toggle', [GalleryController::class, 'toggleStatus'])->name('gallery.toggle');

        $placeholder = function (string $title) {
            return view('admin.placeholder', compact('title'));
        };

        Route::get('products', fn() => $placeholder('All Products'))->name('products.index');
        Route::get('products/create', fn() => $placeholder('Create Product'))->name('products.create');
        Route::get('products/{id}/edit', fn() => $placeholder('Edit Product'))->name('products.edit');

        Route::get('models', fn() => $placeholder('Models'))->name('models.index');
        Route::get('models/create', fn() => $placeholder('Create Model'))->name('models.create');
        Route::get('models/{id}/edit', fn() => $placeholder('Edit Model'))->name('models.edit');

        Route::get('categories', fn() => $placeholder('Categories'))->name('categories.index');
        Route::get('categories/create', fn() => $placeholder('Create Category'))->name('categories.create');
        Route::get('categories/{id}/edit', fn() => $placeholder('Edit Category'))->name('categories.edit');

        Route::get('attributes', fn() => $placeholder('Attributes'))->name('attributes.index');
        Route::get('attributes/create', fn() => $placeholder('Create Attribute'))->name('attributes.create');
        Route::get('attributes/{id}/edit', fn() => $placeholder('Edit Attribute'))->name('attributes.edit');

        Route::get('orders', fn() => $placeholder('Orders'))->name('orders.index');
        Route::get('orders/{id}', fn() => $placeholder('Order Details'))->name('orders.show');

        Route::get('blog', fn() => $placeholder('Blog'))->name('blog.index');
        Route::get('blog/create', fn() => $placeholder('Create Post'))->name('blog.create');
        Route::get('blog/{id}/edit', fn() => $placeholder('Edit Post'))->name('blog.edit');

        Route::get('pages', fn() => $placeholder('Pages'))->name('pages.index');
        Route::get('pages/create', fn() => $placeholder('Create Page'))->name('pages.create');
        Route::get('pages/{id}/edit', fn() => $placeholder('Edit Page'))->name('pages.edit');

        Route::get('support', fn() => $placeholder('Technical Support'))->name('support.index');
        Route::get('support/{id}', fn() => $placeholder('Ticket Details'))->name('support.show');

        Route::get('settings', fn() => $placeholder('Settings'))->name('settings.index');
        Route::get('settings/general', fn() => $placeholder('General Settings'))->name('settings.general');
        Route::get('settings/seo', fn() => $placeholder('SEO Settings'))->name('settings.seo');
        Route::get('settings/mail', fn() => $placeholder('Mail Settings'))->name('settings.mail');
        Route::get('settings/payment', fn() => $placeholder('Payment Settings'))->name('settings.payment');
    });

});
