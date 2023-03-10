<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FullCalenderController;

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

Route::get('fullcalender', [FullCalenderController::class, 'index']);
Route::post('fullcalender', [FullCalenderController::class, 'ajax']);
Route::controller(FullCalenderController::class)->group(function(){
    // Route::get('/','create');
    Route::post('/post','store');
});

Route::get('/', function () {
    return view('home');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

