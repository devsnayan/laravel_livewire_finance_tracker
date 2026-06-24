<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/', function(){
    return redirect()->route('login');
});

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

    // links
    Route::livewire('links', 'pages::links.index')->name('links.index');
    Route::livewire('links/create', 'pages::links.create')->name('links.create');
    Route::livewire('links/{link}/show', 'pages::links.show')->name('links.show');
    Route::livewire('links/{link}/edit', 'pages::links.edit')->name('links.edit');
    Route::livewire('links/{link}/delete', 'pages::links.delete')->name('links.delete');

    // link categories
    Route::livewire('link_categories', 'pages::link_categories.index')->name('link_categories.index');
    Route::livewire('link_categories/create', 'pages::link_categories.create')->name('link_categories.create');
    Route::livewire('link_categories/{link_category}/show', 'pages::link_categories.show')->name('link_categories.show');
    Route::livewire('link_categories/{link_category}/edit', 'pages::link_categories.edit')->name('link_categories.edit');
    Route::livewire('link_categories/{link_category}/delete', 'pages::link_categories.delete')->name('link_categories.delete'); 

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

    // transactions
    Route::livewire('transactions', 'pages::transactions.index')->name('transactions.index');
    Route::livewire('transactions/edit/{transaction}','pages::transactions.edit')->name('transactions.edit');
    Route::livewire('transactions/create/{ledger}', 'pages::transactions.create')->name('transactions.create');

    // payment methods
    Route::livewire('payment_methods', 'pages::payment_methods.index')->name('payment_methods.index');

    // 

});

require __DIR__.'/settings.php';
