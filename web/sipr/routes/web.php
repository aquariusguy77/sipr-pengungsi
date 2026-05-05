<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\RefugeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\EnsureSiprAbility;
use App\Http\Middleware\EnsureSiprAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(EnsureSiprAuthenticated::class)->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/data-pengungsi', [RefugeeController::class, 'index'])->name('refugees.index');
    Route::get('/data-pengungsi/tambah', [RefugeeController::class, 'create'])->middleware(EnsureSiprAbility::class . ':manage-refugees')->name('refugees.create');
    Route::post('/data-pengungsi', [RefugeeController::class, 'store'])->middleware(EnsureSiprAbility::class . ':manage-refugees')->name('refugees.store');
    Route::get('/data-pengungsi/{refugee}', [RefugeeController::class, 'show'])->name('refugees.show');
    Route::get('/data-pengungsi/{refugee}/edit', [RefugeeController::class, 'edit'])->middleware(EnsureSiprAbility::class . ':manage-refugees')->name('refugees.edit');
    Route::put('/data-pengungsi/{refugee}', [RefugeeController::class, 'update'])->middleware(EnsureSiprAbility::class . ':manage-refugees')->name('refugees.update');
    Route::delete('/data-pengungsi/{refugee}', [RefugeeController::class, 'destroy'])->middleware(EnsureSiprAbility::class . ':full-access')->name('refugees.destroy');
    Route::get('/penempatan', [PlacementController::class, 'index'])->name('placements.index');
    Route::get('/penempatan/tambah', [PlacementController::class, 'create'])->middleware(EnsureSiprAbility::class . ':manage-placements')->name('placements.create');
    Route::post('/penempatan', [PlacementController::class, 'store'])->middleware(EnsureSiprAbility::class . ':manage-placements')->name('placements.store');
    Route::get('/penempatan/{placement}', [PlacementController::class, 'show'])->name('placements.show');
    Route::get('/penempatan/{placement}/edit', [PlacementController::class, 'edit'])->middleware(EnsureSiprAbility::class . ':manage-placements')->name('placements.edit');
    Route::put('/penempatan/{placement}', [PlacementController::class, 'update'])->middleware(EnsureSiprAbility::class . ':manage-placements')->name('placements.update');
    Route::delete('/penempatan/{placement}', [PlacementController::class, 'destroy'])->middleware(EnsureSiprAbility::class . ':full-access')->name('placements.destroy');
    Route::get('/dokumen', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/dokumen/tambah', [DocumentController::class, 'create'])->middleware(EnsureSiprAbility::class . ':manage-documents')->name('documents.create');
    Route::post('/dokumen', [DocumentController::class, 'store'])->middleware(EnsureSiprAbility::class . ':manage-documents')->name('documents.store');
    Route::get('/dokumen/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/dokumen/{document}/edit', [DocumentController::class, 'edit'])->middleware(EnsureSiprAbility::class . ':manage-documents')->name('documents.edit');
    Route::put('/dokumen/{document}', [DocumentController::class, 'update'])->middleware(EnsureSiprAbility::class . ':manage-documents')->name('documents.update');
    Route::delete('/dokumen/{document}', [DocumentController::class, 'destroy'])->middleware(EnsureSiprAbility::class . ':full-access')->name('documents.destroy');
    Route::get('/riwayat-perubahan', [HistoryController::class, 'index'])->middleware(EnsureSiprAbility::class . ':review-changes')->name('history.index');
    Route::get('/laporan', [ReportController::class, 'index'])->middleware(EnsureSiprAbility::class . ':view-reports')->name('reports.index');
    Route::get('/pengaturan', [SettingController::class, 'index'])->middleware(EnsureSiprAbility::class . ':manage-settings')->name('settings.index');
});
