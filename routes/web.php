<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Buyers\Index as BuyersIndex;
use App\Livewire\Buyers\Form as BuyersForm;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/buyers', BuyersIndex::class)->name('buyers.index');
    Route::get('/buyers/create', BuyersForm::class)->name('buyers.create');
    Route::get('/buyers/{id}/edit', BuyersForm::class)->name('buyers.edit');
});

require __DIR__.'/settings.php';
