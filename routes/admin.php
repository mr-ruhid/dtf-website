<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryRateController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PrintZoneController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\WidgetController;
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

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::put('profile/two-factor', [ProfileController::class, 'toggleTwoFactor'])->name('profile.two-factor');

        Route::get('widgets', [WidgetController::class, 'index'])->name('widgets.index');
        Route::get('widgets/{widget}/edit', [WidgetController::class, 'edit'])->name('widgets.edit');
        Route::put('widgets/{widget}', [WidgetController::class, 'update'])->name('widgets.update');
        Route::put('widgets/{widget}/toggle', [WidgetController::class, 'toggle'])->name('widgets.toggle');
        Route::post('widgets/reorder', [WidgetController::class, 'reorder'])->name('widgets.reorder');

        Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
        Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
        Route::put('menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        Route::put('menus/{menu}/toggle', [MenuController::class, 'toggle'])->name('menus.toggle');
        Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
        Route::put('menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
        Route::delete('menus/{menu}/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
        Route::put('menus/{menu}/items/{item}/toggle', [MenuController::class, 'toggleItem'])->name('menus.items.toggle');
        Route::post('menus/{menu}/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');

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

        Route::get('blog', [PostController::class, 'index'])->name('blog.index');
        Route::get('blog/create', [PostController::class, 'create'])->name('blog.create');
        Route::post('blog', [PostController::class, 'store'])->name('blog.store');
        Route::get('blog/{post}/edit', [PostController::class, 'edit'])->name('blog.edit');
        Route::put('blog/{post}', [PostController::class, 'update'])->name('blog.update');
        Route::delete('blog/{post}', [PostController::class, 'destroy'])->name('blog.destroy');
        Route::put('blog/{post}/toggle', [PostController::class, 'toggleStatus'])->name('blog.toggle');

        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
        Route::put('pages/{page}/toggle', [PageController::class, 'toggleStatus'])->name('pages.toggle');

        Route::get('models', [ModelController::class, 'index'])->name('models.index');
        Route::get('models/create', [ModelController::class, 'create'])->name('models.create');
        Route::post('models', [ModelController::class, 'store'])->name('models.store');
        Route::get('models/{model}/edit', [ModelController::class, 'edit'])->name('models.edit');
        Route::put('models/{model}', [ModelController::class, 'update'])->name('models.update');
        Route::delete('models/{model}', [ModelController::class, 'destroy'])->name('models.destroy');
        Route::put('models/{model}/toggle', [ModelController::class, 'toggleStatus'])->name('models.toggle');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::put('categories/{category}/toggle', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');

        Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
        Route::post('attributes', [AttributeController::class, 'store'])->name('attributes.store');
        Route::put('attributes/{attribute}', [AttributeController::class, 'update'])->name('attributes.update');
        Route::delete('attributes/{attribute}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
        Route::put('attributes/{attribute}/toggle', [AttributeController::class, 'toggleStatus'])->name('attributes.toggle');

        Route::post('attributes/{attribute}/values', [AttributeController::class, 'storeValue'])->name('attributes.values.store');
        Route::put('attributes/{attribute}/values/{value}', [AttributeController::class, 'updateValue'])->name('attributes.values.update');
        Route::delete('attributes/{attribute}/values/{value}', [AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');
        Route::put('attributes/{attribute}/values/{value}/toggle', [AttributeController::class, 'toggleValueStatus'])->name('attributes.values.toggle');

        Route::get('print-zones', [PrintZoneController::class, 'index'])->name('print-zones.index');
        Route::post('print-zones', [PrintZoneController::class, 'store'])->name('print-zones.store');
        Route::put('print-zones/{zone}', [PrintZoneController::class, 'update'])->name('print-zones.update');
        Route::delete('print-zones/{zone}', [PrintZoneController::class, 'destroy'])->name('print-zones.destroy');
        Route::put('print-zones/{zone}/toggle', [PrintZoneController::class, 'toggleStatus'])->name('print-zones.toggle');

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::put('products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
        Route::put('products/{product}/featured', [ProductController::class, 'toggleFeatured'])->name('products.featured');
        Route::delete('products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.destroy');

        Route::get('branches', [BranchController::class, 'index'])->name('branches.index');
        Route::post('branches', [BranchController::class, 'store'])->name('branches.store');
        Route::put('branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
        Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');
        Route::put('branches/{branch}/toggle', [BranchController::class, 'toggleStatus'])->name('branches.toggle');
        Route::put('branches/{branch}/default', [BranchController::class, 'setDefault'])->name('branches.default');

        Route::get('delivery-zones', [DeliveryZoneController::class, 'index'])->name('delivery-zones.index');
        Route::post('delivery-zones', [DeliveryZoneController::class, 'store'])->name('delivery-zones.store');
        Route::put('delivery-zones/{zone}', [DeliveryZoneController::class, 'update'])->name('delivery-zones.update');
        Route::delete('delivery-zones/{zone}', [DeliveryZoneController::class, 'destroy'])->name('delivery-zones.destroy');
        Route::put('delivery-zones/{zone}/toggle', [DeliveryZoneController::class, 'toggleStatus'])->name('delivery-zones.toggle');

        Route::get('delivery-rates', [DeliveryRateController::class, 'index'])->name('delivery-rates.index');
        Route::post('delivery-rates', [DeliveryRateController::class, 'store'])->name('delivery-rates.store');
        Route::put('delivery-rates/{rate}', [DeliveryRateController::class, 'update'])->name('delivery-rates.update');
        Route::delete('delivery-rates/{rate}', [DeliveryRateController::class, 'destroy'])->name('delivery-rates.destroy');
        Route::put('delivery-rates/{rate}/toggle', [DeliveryRateController::class, 'toggleStatus'])->name('delivery-rates.toggle');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/api-docs', [OrderController::class, 'apiDocs'])->name('orders.api-docs');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::put('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::delete('orders/{order}/designs/{design}', [OrderController::class, 'deleteDesign'])->name('orders.designs.destroy');

        Route::get('support', [SupportController::class, 'index'])->name('support.index');
        Route::get('support/{ticket}', [SupportController::class, 'show'])->name('support.show');
        Route::put('support/{ticket}/status', [SupportController::class, 'updateStatus'])->name('support.status');
        Route::put('support/{ticket}/priority', [SupportController::class, 'updatePriority'])->name('support.priority');
        Route::put('support/{ticket}/category', [SupportController::class, 'updateCategory'])->name('support.category');
        Route::put('support/{ticket}/assign', [SupportController::class, 'assign'])->name('support.assign');
        Route::put('support/{ticket}/admin-note', [SupportController::class, 'updateAdminNote'])->name('support.admin-note');
        Route::post('support/{ticket}/reply', [SupportController::class, 'reply'])->name('support.reply');
        Route::delete('support/{ticket}', [SupportController::class, 'destroy'])->name('support.destroy');
        Route::delete('support/{ticket}/attachments/{attachment}', [SupportController::class, 'deleteAttachment'])->name('support.attachments.destroy');

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');

        Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
        Route::post('settings/general', [SettingController::class, 'generalUpdate'])->name('settings.general.update');
        Route::delete('settings/general/logo', [SettingController::class, 'generalRemoveLogo'])->name('settings.general.remove-logo');
        Route::delete('settings/general/favicon', [SettingController::class, 'generalRemoveFavicon'])->name('settings.general.remove-favicon');

        Route::get('settings/contact', [SettingController::class, 'contact'])->name('settings.contact');
        Route::post('settings/contact', [SettingController::class, 'contactUpdate'])->name('settings.contact.update');

        Route::get('settings/seo', [SettingController::class, 'seo'])->name('settings.seo');
        Route::post('settings/seo', [SettingController::class, 'seoUpdate'])->name('settings.seo.update');
        Route::delete('settings/seo/og', [SettingController::class, 'seoRemoveOg'])->name('settings.seo.remove-og');

        Route::get('settings/homepage', [SettingController::class, 'homepage'])->name('settings.homepage');
        Route::post('settings/homepage', [SettingController::class, 'homepageUpdate'])->name('settings.homepage.update');
        Route::delete('settings/homepage/hero-image', [SettingController::class, 'homepageRemoveHero'])->name('settings.homepage.remove-image');

        Route::get('settings/smtp', [SettingController::class, 'smtp'])->name('settings.smtp');
        Route::post('settings/smtp', [SettingController::class, 'smtpUpdate'])->name('settings.smtp.update');
        Route::post('settings/smtp/test', [SettingController::class, 'smtpTest'])->name('settings.smtp.test');

        Route::get('settings/system', [SettingController::class, 'system'])->name('settings.system');

        Route::get('settings/maintenance', [SettingController::class, 'maintenance'])->name('settings.maintenance');
        Route::post('settings/maintenance', [SettingController::class, 'maintenanceUpdate'])->name('settings.maintenance.update');

        Route::get('settings/security', [SettingController::class, 'security'])->name('settings.security');
        Route::post('settings/security/block', [SettingController::class, 'blockIp'])->name('settings.security.block');
        Route::delete('settings/security/unblock/{blockedIp}', [SettingController::class, 'unblockIp'])->name('settings.security.unblock');

        Route::get('settings/update', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/update/install', [UpdateController::class, 'install'])->name('settings.update.install');

        Route::get('settings/backup', [BackupController::class, 'index'])->name('settings.backup');
        Route::post('settings/backup', [BackupController::class, 'create'])->name('settings.backup.create');
        Route::get('settings/backup/download/{fileName}', [BackupController::class, 'download'])->name('settings.backup.download');
        Route::post('settings/backup/restore/{fileName}', [BackupController::class, 'restore'])->name('settings.backup.restore');
        Route::delete('settings/backup/{fileName}', [BackupController::class, 'destroy'])->name('settings.backup.destroy');

        Route::get('settings/cache', [SettingController::class, 'cache'])->name('settings.cache');
        Route::post('settings/cache/clear/{type}', [SettingController::class, 'clearCache'])->name('settings.cache.clear');
    });

});
