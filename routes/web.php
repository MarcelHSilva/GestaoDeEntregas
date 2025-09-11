<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('deliveries.index');
});

// Rotas para Entregas
Route::resource('deliveries', DeliveryController::class);

// Rotas para Manutenções
Route::resource('maintenances', MaintenanceController::class);

// Rotas para Relatórios
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom');
