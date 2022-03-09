<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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
    abort(404);
    // return view('welcome');
});
Route::get('/pdf', function () {
    
    $data = [
        'titulo' => 'Styde.net'
    ];

    $pdf = PDF::loadView('pdf', $data);

    return $pdf->download('archivo.pdf');
   
});
Route::get('/pdf_vista', function () {
    
    return view('pdf');
   
});
