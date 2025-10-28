<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', \App\Livewire\Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/user-management', \App\Livewire\UserManagement::class)->name('admin.user-management');
    Route::get('/monitoring', \App\Livewire\Admin\Monitoring::class)->name('monitoring');
});

// Staff routes
Route::middleware(['auth'])->group(function () {
    Route::get('/logbook', \App\Livewire\Staff\Logbook::class)->name('logbook');
});

// Reference routes
Route::middleware(['auth'])->group(function () {
    Route::get('/reference/position-division', \App\Livewire\Reference\PositionAndDivision::class)->name('reference.position-division');
});

require __DIR__.'/auth.php';
