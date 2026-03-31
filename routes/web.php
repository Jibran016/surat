<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TrixAttachmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/Dashboard', [DashboardController::class, 'chart'])->name('dashboard.chart');
    Route::get('/dashboard/monitoring', [DashboardController::class, 'monitoring'])->name('dashboard.monitoring');

    Route::get('/surat/buat', [SuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');

    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/masuk', [SuratController::class, 'inbox'])->name('surat.inbox');
    Route::get('/surat/keluar', [SuratController::class, 'outbox'])->name('surat.outbox');
    Route::get('/surat/inventori-file', [SuratController::class, 'fileInventory'])->name('surat.inventory');
    Route::get('/surat/inventori-file/upload', [SuratController::class, 'inventoryUploadForm'])->name('surat.inventory.upload');
    Route::post('/surat/inventori-file', [SuratController::class, 'storeInventory'])->name('surat.inventory.store');

    Route::get('/surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
    Route::get('/surat/{surat}/pdf', [SuratController::class, 'downloadPdf'])->name('surat.pdf');
    Route::get('/surat/{surat}/lampiran', [SuratController::class, 'attachment'])->name('surat.attachment');
    Route::post('/surat/{surat}/arsip', [SuratController::class, 'archive'])->name('surat.archive.store');
    Route::post('/surat/{surat}/arsip/batal', [SuratController::class, 'unarchive'])->name('surat.archive.remove');
    Route::post('/surat/{surat}/selesai', [SuratController::class, 'markDone'])->name('surat.done');
    Route::get('/surat/{surat}/balas', [SuratController::class, 'replyForm'])->name('surat.reply');
    Route::post('/surat/{surat}/balas', [SuratController::class, 'replyStore'])->name('surat.reply.store');

    Route::post('/trix/attachments', [TrixAttachmentController::class, 'store'])->name('trix.attachments.store');

    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/{notification}', [NotificationController::class, 'open'])->name('notifications.open');

    Route::get('/akun/tambah', [UserController::class, 'create'])->name('users.create');
    Route::post('/akun', [UserController::class, 'store'])->name('users.store');
    Route::get('/profil', [UserController::class, 'profile'])->name('users.profile');

    Route::middleware('admin')->group(function () {
        Route::get('/akun', [UserController::class, 'index'])->name('users.index');
        Route::get('/akun/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/akun/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/akun/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/divisi', [DivisionController::class, 'index'])->name('divisions.index');
        Route::get('/divisi/tambah', [DivisionController::class, 'create'])->name('divisions.create');
        Route::post('/divisi', [DivisionController::class, 'store'])->name('divisions.store');
        Route::get('/divisi/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
        Route::put('/divisi/{division}', [DivisionController::class, 'update'])->name('divisions.update');
        Route::delete('/divisi/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

        Route::get('/admin/surat', [SuratController::class, 'adminIndex'])->name('admin.surat.index');
    });
});
