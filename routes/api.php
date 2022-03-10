<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FacturaDetallesController;
use App\Http\Controllers\FrecuenciaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProductosController;
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
Route::group(['middleware' => ['auth:sanctum','role:administrador|vendedor|supervisor']], function () {
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
Route::put('update-password/{id}',  [UsuarioController::class, 'updatePassword']);

Route::resource('categorias', CategoriaController::class);
Route::resource('frecuencias', FrecuenciaController::class);
Route::resource('productos', ProductosController::class);
Route::resource('factura-detalle', FacturaDetallesController::class);
Route::resource('facturas', FacturaController::class);
Route::get('pdf/{id}', [PdfController::class,'facturaPago']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
