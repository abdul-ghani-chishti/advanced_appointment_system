<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\Portal\ApplicationStatusController;
use App\Http\Controllers\User\Portal\PortalDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Portal\DocumentController;
use Inertia\Inertia;

use App\Http\Controllers\ContactFormController;

Route::get('/', fn () => Inertia::render('UserWelcome'))->name('home');
Route::get('/about', fn () => Inertia::render('About'))->name('about');
Route::get('/services', fn () => Inertia::render('Services'))->name('services');
Route::get('/contact', fn () => Inertia::render('Contact'))->name('contact');
Route::post('/contact', [ContactFormController::class, 'store_contact_form'])->name('contact.store');


Route::middleware(['auth', 'verified'])->prefix('portal')->name('portal.')
    ->group(function () {
        Route::get('/dashboard', PortalDashboardController::class)
            ->name('dashboard');

        Route::get('/documents', [DocumentController::class, 'index'])
            ->name('documents.index');

        Route::get('/documents/upload', [DocumentController::class, 'create'])
            ->name('documents.upload');

        Route::post('/documents', [DocumentController::class, 'store'])
            ->middleware('uploads.available')
            ->name('documents.store');

        Route::get('/application-status', PortalDashboardController::class)
            ->name('application.status');

        Route::get('/appointment-status', function () {
            return Inertia::render('Portal/AppointmentStatus');
        })->name('appointment.status');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
