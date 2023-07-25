<?php

use App\Http\Controllers\CrowlersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\UrlsController;
use App\Models\Crowlers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth','verified'])->group(function(){

    Route::resource('crowler',CrowlersController::class);
    Route::resource('scrap',ScrapingController::class);
    Route::resource('Urls',UrlsController::class);
    Route::post('/dashboard', [ScrapingController::class, 'dashboard'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
