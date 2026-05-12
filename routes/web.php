<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Verifikasi Produk Roti
|--------------------------------------------------------------------------
*/

// -------------------------------------------------------
// PUBLIC - Scanner (untuk konsumen, tanpa login)
// -------------------------------------------------------

Route::get('/', function () {
    return view('scanner');
});

Route::get('/scanner', function () {
    return view('scanner');
})->name('scanner');


// -------------------------------------------------------
// AUTH - Login & Logout Admin
// -------------------------------------------------------

Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login.post');

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout');


// -------------------------------------------------------
// ADMIN - Semua route admin wajib login dulu
// -------------------------------------------------------

Route::prefix('admin')->middleware(\App\Http\Middleware\AdminAuth::class)->group(function () {

    // Dashboard
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    // Tambah produk single
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    // Bulk generate
    Route::get('/products/bulk-create', [ProductController::class, 'bulkCreate'])
        ->name('products.bulk-create');

    // Simpan produk single
    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    // Simpan bulk
    Route::post('/products/bulk-store', [ProductController::class, 'bulkStore'])
        ->name('products.bulk-store');

    // Halaman batch
    Route::get('/products/batch/{batchNumber}', [ProductController::class, 'showBatch'])
        ->name('products.batch');

    // Print batch
    Route::get('/products/batch/{batchNumber}/print', [ProductController::class, 'printBatch'])
        ->name('products.batch-print');

    // Download ZIP batch QR
    Route::get('/products/bulk-download/{batchNumber}', [ProductController::class, 'bulkDownloadQr'])
        ->name('products.bulk-download-qr');

    // Download ZIP label batch
    Route::get('/products/bulk-download-label/{batchNumber}', [ProductController::class, 'bulkDownloadLabel'])
        ->name('products.bulk-download-label');

        // Export laporan Excel   ← TAMBAH INI
    Route::get('/products/export-excel', [ProductController::class, 'exportExcel'])
        ->name('products.export-excel');

    // Download QR single
    Route::get('/products/{id}/download-qr', [ProductController::class, 'downloadQr'])
        ->name('products.download-qr');

    // Download Label single
    Route::get('/products/{id}/download-label', [ProductController::class, 'downloadLabel'])
        ->name('products.download-label');

    // Delete produk single
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    // Delete per batch
    Route::delete('/products/batch/{batchNumber}/destroy', [ProductController::class, 'destroyBatch'])
        ->name('products.destroy-batch');

    // Delete semua produk
    Route::delete('/products/destroy-all', [ProductController::class, 'destroyAll'])
        ->name('products.destroy-all');
        // Kelola Template Label
Route::get('/label-templates', [App\Http\Controllers\LabelTemplateController::class, 'index'])
    ->name('label-templates.index');

Route::post('/label-templates', [App\Http\Controllers\LabelTemplateController::class, 'store'])
    ->name('label-templates.store');

Route::delete('/label-templates/{id}', [App\Http\Controllers\LabelTemplateController::class, 'destroy'])
    ->name('label-templates.destroy');

    // Detail produk — HARUS PALING BAWAH karena {id} menangkap semua
    Route::get('/products/{id}', [ProductController::class, 'show'])
        ->name('products.show');
});

// -------------------------------------------------------
// API - Verifikasi QR (untuk konsumen, tanpa login)
// -------------------------------------------------------

Route::prefix('api')->group(function () {
    Route::post('/verify', [ProductController::class, 'verify'])
        ->name('api.verify');
});

// -------------------------------------------------------
// PUBLIC - Halaman verifikasi langsung dari kamera HP
// -------------------------------------------------------

Route::get('/verify/{productId}', [ProductController::class, 'verifyPage'])
    ->name('verify.page');