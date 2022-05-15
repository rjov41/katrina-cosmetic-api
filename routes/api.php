<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DevolucionFacturaController;
use App\Http\Controllers\DevolucionProductoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FacturaDetallesController;
use App\Http\Controllers\FacturaHistorial;
use App\Http\Controllers\FrecuenciaController;
use App\Http\Controllers\LogisticaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\ReciboHistorialContadoController;
use App\Http\Controllers\ReciboHistorialController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScriptController;
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
Route::group(['middleware' => ['auth:sanctum', 'role:administrador|vendedor|supervisor']], function () {
    Route::post('/sign-out', [AuthenticationController::class, 'signout']);
    Route::get('/profile', function (Request $request) {
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

Route::get('cliente/factura/{id}',  [ClienteController::class, 'clienteToFactura']);
Route::get('cliente/abono/{id}',  [ClienteController::class, 'calcularAbono']);
Route::get('cliente/deuda/{id}',  [ClienteController::class, 'calcularDeudaVendedorCliente']);
Route::get('cliente/deuda',  [ClienteController::class, 'calcularDeudaVendedorTodosClientes']);
Route::resource('cliente', ClienteController::class);

Route::resource('roles', RoleController::class);

Route::resource('usuarios', UsuarioController::class);

Route::put('update-password/{id}',  [UsuarioController::class, 'updatePassword']);

Route::resource('categorias', CategoriaController::class);

Route::resource('frecuencias', FrecuenciaController::class);

Route::resource('productos', ProductosController::class);

Route::resource('factura-detalle', FacturaDetallesController::class);

Route::resource('facturas', FacturaController::class);
Route::put('facturas/despachar/{id}', [FacturaController::class, 'despachar']);

Route::resource('abonos', FacturaHistorial::class);

Route::resource('recibos', ReciboController::class);
Route::resource('recibos/historial/contado', ReciboHistorialContadoController::class);
Route::resource('recibos/historial/credito', ReciboHistorialController::class);
Route::get('recibos/number/{id}', [ReciboController::class, 'getNumeroRecibo']);


Route::get('pdf/{id}', [PdfController::class, 'facturaPago']);
Route::post('pdf',);

Route::get('mail/{id}', [PdfController::class, 'SendMail']);

Route::resource('devolucion-factura', DevolucionFacturaController::class);

Route::resource('devoluciones-producto', DevolucionProductoController::class);

Route::get('script/AsignarPrecioPorUnidadGlobal', [ScriptController::class, 'AsignarPrecioPorUnidadGlobal']);
Route::get('script/validarStatusPagadoGlobal', [ScriptController::class, 'validarStatusPagadoGlobal']);
Route::get('script/actualizarPrecioFactura/{id}', [ScriptController::class, 'ActualizarPrecioFactura']);



Route::post('logistica/cartera-date', [LogisticaController::class, 'carteraDate']);
Route::post('logistica/recibo-date', [LogisticaController::class, 'reciboDate']);
Route::post('logistica/mora-30-60', [LogisticaController::class, 'Mora30A60']);
Route::post('logistica/mora-60-90', [LogisticaController::class, 'Mora60A90']);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
