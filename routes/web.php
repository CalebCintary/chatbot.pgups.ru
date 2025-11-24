<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    return redirect()->route('chat');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
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

    Route::get('/text-upload', \App\Livewire\TextUpload::class)->name('text-upload');
    Route::get('/text-upload2', \App\Livewire\TextUpload2::class)->name('text-upload2');
    Route::get('/chat', \App\Livewire\Chat::class)->name('chat');
    Route::get('/scrapper', \App\Livewire\Scrapper::class)->name('scrapper');
    Route::get('/chroma', \App\Livewire\ChromaDBViewer::class)->name('chroma');
});
