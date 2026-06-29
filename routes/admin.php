<?php

use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\ContractTemplateController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/health', HealthCheckResultsController::class)->name('health');

        // Contracts
        Route::resource('contract-templates', ContractTemplateController::class);
        Route::resource('contracts', ContractController::class);
        Route::get('contracts/{contract}/preview', [ContractController::class, 'preview'])->name('contracts.preview');
        Route::get('contracts/{contract}/pdf', [ContractController::class, 'pdf'])->name('contracts.pdf');
    });
