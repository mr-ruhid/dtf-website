<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/about-us', [PageController::class, 'about'])->name('page.about');
