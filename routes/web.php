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
    Route::livewire('/permissions', 'pages::permissions.index')->name('permissions.index');
    Route::livewire('/roles', 'pages::roles.index')->name('roles.index');

    // ledgers
    Route::livewire('ledgers', 'pages::ledgers.index')->name('ledgers.index');
    Route::livewire('ledgers/create', 'pages::ledgers.create')->name('ledgers.create');
    Route::livewire('ledgers/{ledger}/show', 'pages::ledgers.show')->name('ledgers.show');
    Route::livewire('ledgers/{ledger}/edit', 'pages::ledgers.edit')->name('ledgers.edit');
    Route::livewire('ledgers/{ledger}/delete', 'pages::ledgers.delete')->name('ledgers.delete');

    // ledger types
    Route::livewire('ledger_types', 'pages::ledger_types.index')->name('ledger_types.index');
    Route::livewire('ledger_types/create', 'pages::ledger_types.create')->name('ledger_types.create');
    Route::livewire('ledger_types/{ledger_type}/show', 'pages::ledger_types.show')->name('ledger_types.show');
    Route::livewire('ledger_types/{ledger_type}/edit', 'pages::ledger_types.edit')->name('ledger_types.edit');
    Route::livewire('ledger_types/{ledger_type}/delete', 'pages::ledger_types.delete')->name('ledger_types.delete');

});

require __DIR__.'/settings.php';
