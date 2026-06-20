<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::livewire('settings/profile', 'pages::settings.profile')->name('profile.edit');

    Route::livewire('users', 'pages::users.index')->name('users.index');
    Route::livewire('users/create', 'pages::users.create')->name('users.create');
    Route::livewire('users/{user}/show', 'pages::users.show')->name('users.show');
    Route::livewire('users/{user}/edit', 'pages::users.edit')->name('users.edit');
    Route::livewire('users/{user}/delete', 'pages::users.delete')->name('users.delete');
    
    Route::livewire('/profile', 'pages::users.show')->name('profile.show');

});

require __DIR__.'/settings.php';
