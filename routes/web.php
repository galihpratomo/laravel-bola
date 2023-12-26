<?php
use App\Http\Controllers\KlubController;
use App\Http\Controllers\HasilController;
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
Route::resource('klub', KlubController::class);
Route::resource('hasil', HasilController::class);
Route::post('/cek-data-hasil', [HasilController::class, 'cek_data']);
Route::get('/hasil-multiple', [HasilController::class, 'create_multiple']);
Route::post('/simpan-hasil', [HasilController::class, 'simpan']);
