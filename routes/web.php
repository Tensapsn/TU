<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\LetterTypeController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home.page');
})->middleware(['auth', 'verified'])->name('home.page');
Route::get('/data-staff', function() {
    return view('dataStaff');
})->middleware(['auth', 'verified'])->name('dataStaff');
Route::get('/data-guru', function() {
    return view('dataGuru');
})->middleware(['auth', 'verified'])->name('dataGuru');
Route::get('/data-klasifikasi-surat', function() {
    return view('dataKlasifikasi');
})->middleware(['auth', 'verified'])->name('dataKlasifikasi');
Route::get('/data-surat', function() {
    return view('dataSurat');
})->middleware(['auth', 'verified'])->name('dataSurat');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/error-permission', function() {
    return view('errors.permission');
})->name('error.permission');

Route::middleware(['IsLogin'])->group(function() {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/home', function () {
        return view('home');
    })->name('home.page');
});

Route::middleware(['IsGuest'])->group(function() {
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
});

Route::middleware(['IsLogin', 'IsStaff'])->group(function() {
    // Menu Kelola Akun
    Route::prefix('/userStaff')->name('userStaff.')->group(function(){
        Route::get('/create', [UserController::class, 'Staffcreate'])->name('create');
        Route::post('/store', [UserController::class, 'Staffstore'])->name('store');
        Route::get('/', [UserController::class, 'Staffindex'])->name('home');
        Route::get('/{id}', [UserController::class, 'Staffedit'])->name('edit');
        Route::patch('/{id}', [UserController::class, 'Staffupdate'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/userGuru')->name('userGuru.')->group(function(){
        Route::get('/create', [UserController::class, 'Gurucreate'])->name('create');
        Route::post('/store', [UserController::class, 'Gurustore'])->name('store');
        Route::get('/', [UserController::class, 'Guruindex'])->name('home');
        Route::get('/{id}', [UserController::class, 'Guruedit'])->name('edit');
        Route::patch('/{id}', [UserController::class, 'Guruupdate'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/export')->name('export.')->group(function () {
        Route::get('/data', [LetterTypeController::class, 'data'])->name('data');
        Route::get('/export-excel', [LetterTypeController::class, 'fileExport'])->name('export-excel');
        Route::get('/download/{id}', [LetterTypeController::class, 'downloadPDF'])->name('download');

    });
});

Route::middleware(['IsLogin', 'IsStaff'])->group(function() {
    Route::prefix('/KlasifikasiSurat')->name('KlasifikasiSurat.')->group(function(){
        Route::get('/create', [LetterTypeController::class, 'create'])->name('create');
        Route::post('/store', [LetterTypeController::class, 'store'])->name('store');
        Route::get('/', [LetterTypeController::class, 'index'])->name('home');
        Route::get('/{id}', [LetterTypeController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [LetterTypeController::class, 'update'])->name('update');
        Route::delete('/{id}', [LetterTypeController::class, 'destroy'])->name('delete');
    });
});

Route::middleware(['IsLogin', 'IsStaff'])->group(function() {
    // Menu Kelola Surat
    Route::prefix('/dataSurat')->name('dataSurat.')->group(function(){
        Route::get('/create', [LetterController::class, 'create'])->name('create');
        Route::post('/store', [LetterController::class, 'store'])->name('store');
        Route::get('/', [LetterController::class, 'index'])->name('home');
        Route::get('/{id}', [LetterController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [LetterController::class, 'update'])->name('update');
        Route::get('/print/{id}', [LetterController::class, 'show'])->name('print');
        Route::get('/download/{id}', [LetterController::class, 'downloadPDF'])->name('download');
        Route::delete('/{id}', [LetterTypeController::class, 'destroy'])->name('delete');
    });
});

Route::middleware(['IsLogin', 'IsGuru'])->group(function() {
    // Menu Result
    Route::prefix('/result')->name('result.')->group(function(){
       Route::get('/results/{id}', [ResultsController::class, 'create'])->name('results');
       Route::post('/store', [ResultsController::class, 'store'])->name('store');
       Route::get('/show/{id}', [ResultsController::class, 'show'])->name('show');

    });

    Route::prefix('/dataSurat')->name('dataSurat.')->group(function(){
        Route::get('/', [LetterController::class, 'index'])->name('home');
 
     });

    Route::prefix('/export')->name('export.')->group(function () {
        Route::get('/data', [LetterTypeController::class, 'data'])->name('data');
        Route::get('/export-excel', [LetterTypeController::class, 'fileExport'])->name('export-excel');
        Route::get('/download/{id}', [LetterTypeController::class, 'downloadPDF'])->name('download');
    });
});

Route::prefix('/export')->name('export.')->group(function () {
    Route::get('/data', [LetterTypeController::class, 'data'])->name('data');
    Route::get('/export-excel', [LetterTypeController::class, 'fileExport'])->name('export-excel');
    Route::get('/download/{id}', [LetterTypeController::class, 'downloadPDF'])->name('download');
});

Route::prefix('/export-excel')->name('export-excel.')->group(function() {
    Route::get('/export-excel', [LetterController::class, 'fileExport'])->name('export');
});
require __DIR__.'/auth.php';
