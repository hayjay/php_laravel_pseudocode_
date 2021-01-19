<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{QueryController, PdfController};

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('country/{country}', [QueryController::class, 'showCountry']);
Route::get('country/{country}/users', [QueryController::class, 'showCountryUsers']);
Route::get('companies', [QueryController::class, 'showCompanies']);
Route::post('pdf', [PdfController::class, 'save']);
