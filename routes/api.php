<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FrecuenciaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//register new user

Route::post('/create-account', [AuthenticationController::class, 'createAccount']);
//login user
Route::post('/signin', [AuthenticationController::class, 'signin']);

//using middleware
Route::group(['middleware' => ['auth:sanctum','role:admin']], function () {
    Route::post('/sign-out', [AuthenticationController::class, 'signout']);
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    
});

// Route::middleware(
//     [
//         'auth:sanctum',     // autenticate
//         'role:admin'   // role
//     ]
//     )
//     ->prefix('auth')->group(function () {

// });

Route::resource('cliente', ClienteController::class);
Route::resource('roles', RoleController::class);
Route::resource('usuarios', UsuarioController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('frecuencias', FrecuenciaController::class);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
