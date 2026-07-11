<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ContactFormController;

Route::get('/', fn () => Inertia::render('UserWelcome'))->name('home');
Route::get('/about', fn () => Inertia::render('About'))->name('about');
Route::get('/services', fn () => Inertia::render('Services'))->name('services');
Route::get('/contact', fn () => Inertia::render('Contact'))->name('contact');
Route::post('/contact', [ContactFormController::class, 'store_contact_form'])->name('contact.store');


Route::get('/dashboard', function () {
    return Inertia::render('portal/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
