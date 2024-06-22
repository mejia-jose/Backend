<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DialogFlowController;

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

Route::post('/dialogflow-webhook', [DialogFlowController::class, 'webhook'])->name('dialogflow-webhook');


Route::get('/', function () {
    return view('welcome');
});
