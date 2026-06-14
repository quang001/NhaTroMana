<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;

// Trang chủ - Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Bất động sản / Nhà trọ
Route::prefix('properties')->name('properties.')->group(function () {
    Route::get('/', [PropertyController::class, 'index'])->name('index');
    Route::get('/create', [PropertyController::class, 'create'])->name('create');
    Route::post('/', [PropertyController::class, 'store'])->name('store');
    Route::get('/{id}', [PropertyController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PropertyController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PropertyController::class, 'update'])->name('update');
    Route::delete('/{id}', [PropertyController::class, 'destroy'])->name('destroy');
    Route::get('/search', [PropertyController::class, 'search'])->name('search');
});

// Khách thuê
Route::prefix('tenants')->name('tenants.')->group(function () {
    Route::get('/', [TenantController::class, 'index'])->name('index');
    Route::get('/create', [TenantController::class, 'create'])->name('create');
    Route::post('/', [TenantController::class, 'store'])->name('store');
    Route::get('/{id}', [TenantController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [TenantController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TenantController::class, 'update'])->name('update');
    Route::delete('/{id}', [TenantController::class, 'destroy'])->name('destroy');
});

// Hợp đồng
Route::prefix('contracts')->name('contracts.')->group(function () {
    Route::get('/', [ContractController::class, 'index'])->name('index');
    Route::get('/create', [ContractController::class, 'create'])->name('create');
    Route::post('/', [ContractController::class, 'store'])->name('store');
    Route::get('/{id}', [ContractController::class, 'show'])->name('show');
});
