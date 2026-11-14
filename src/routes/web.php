<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::livewire('/register', 'auth.register-parent')
    ->name('register');

Route::livewire('/parent/dashboard', 'parent.dashboard')
    ->middleware('auth')
    ->name('parent.dashboard');
