<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DevolucionFacturaController;
use App\Http\Controllers\DevolucionProductoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FacturaDetallesController;
use App\Http\Controllers\FacturaHistorial;
use App\Http\Controllers\FrecuenciaController;
use App\Http\Controllers\LogisticaController;
use App\Http\Controllers\MetasController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\ReciboHistorialContadoController;
use App\Http\Controllers\ReciboHistorialController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Artisan;

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
Route::get('cliente/deuda/user/{id}',  [ClienteController::class, 'calcularDeudaVendedorTodosClientesPorUsuario']);
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
Route::put('facturas/entregada/{id}', [FacturaController::class, 'entregada']);

Route::resource('abonos', FacturaHistorial::class);

Route::resource('recibos', ReciboController::class);
Route::resource('recibos/historial/contado', ReciboHistorialContadoController::class);
Route::resource('recibos/historial/credito', ReciboHistorialController::class);
Route::get('recibos/number/{id}', [ReciboController::class, 'getNumeroRecibo']);

Route::resource('metas', MetasController::class);

Route::get('pdf/{id}', [PdfController::class, 'facturaPago']);
Route::get('pdf/estado_cuenta/{id}', [PdfController::class, 'estadoCuenta']);
Route::post('pdf/cartera', [PdfController::class, 'cartera']);
// Route::post('pdf', [PdfController::class,'generar']);

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
Route::post('logistica/cliente-new', [LogisticaController::class, 'clienteDate']);
Route::post('logistica/incentivo', [LogisticaController::class, 'incentivo']);
Route::post('logistica/cliente-inactivo', [LogisticaController::class, 'clienteInactivo']);
Route::post('logistica/estado-de-cuenta', [LogisticaController::class, 'estadoCuenta']);
Route::get('logistica/producto-logistica', [LogisticaController::class, 'productoLogistica']);
Route::post('logistica/clientes-reactivados', [LogisticaController::class, 'clientesReactivados']);
Route::post('logistica/ventas', [LogisticaController::class, 'ventasDate']);
Route::post('logistica/recuperacion', [LogisticaController::class, 'recuperacion']);


Route::post('configuracion/migracion', [ConfiguracionController::class, 'migracion']);

Route::get('/clear-cache', function () {
    echo Artisan::call('config:clear');
    echo Artisan::call('config:cache');
    echo Artisan::call('cache:clear');
    echo Artisan::call('route:clear');
 });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
