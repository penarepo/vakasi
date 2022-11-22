<?php

use App\Http\Controllers\VakasiNilaiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [VakasiNilaiController::class, 'index']);
Route::post('/import-excel', [VakasiNilaiController::class, 'importExcel']);
Route::get('/vakasi-nilai', [VakasiNilaiController::class, 'getVakasiNilai']);
Route::get('/data-kelas-all', [VakasiNilaiController::class, 'getDataKelas']);
Route::get('/data-kelas-detail/{id}', [VakasiNilaiController::class, 'getDataDetailKelas']);
Route::put('/update-kelas', [VakasiNilaiController::class, 'updateKelas']);
Route::get('/cetak-vakasi-nilai/{id}', [VakasiNilaiController::class, 'cetakVakasiNilai']);
// Route::get('/cetak-vakasi-nilai/{id}/{prodi}', [VakasiNilaiController::class, 'cetakVakasiNilai']);
Route::get('/mk-vakasi-nilai/{id}', [VakasiNilaiController::class, 'mkVakasiNilai']);
Route::get('/data-kelas', [VakasiNilaiController::class, 'dataKelas']);
