<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApCajasController;

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

/*
    Route::group(['prefix' => 'api'], function () {
    Route::get('cajas', [ApCajasController::class, 'index']);
    Route::post('cajas/crear', [ApCajasController::class, 'store']);
    Route::get('cajas/{id}', [ApCajasController::class, 'show']);
    Route::post('cajas/actualizar/{id}', [ApCajasController::class, 'update']);
    Route::post('cajas/eliminar/{id}', [ApCajasController::class, 'desactivar']);
});
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/** **/
// Rutas públicas de autenticación
Route::group(['prefix' => 'auth'], function() {
    Route::post('validate-token', [App\Http\Controllers\Api\AuthController::class, 'validateCreateToken']);
    Route::get('validate-simple', [App\Http\Controllers\Api\AuthController::class, 'validateTokenSimple']);
    Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
});

// Rutas protegidas por autenticación JWT
Route::group(['middleware' => 'auth:api'], function() {
    // Rutas para Camiones
    Route::group(['prefix' => 'camiones'], function() {
        Route::get('/', [App\Http\Controllers\Api\CamionesController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\CamionesController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\CamionesController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\CamionesController::class, 'crearCamion']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\CamionesController::class, 'actualizarCamion']);
        Route::post('desactivar/{id}', [App\Http\Controllers\Api\CamionesController::class, 'desactivarCamion']);
    });

    // Rutas para Cajas
    Route::group(['prefix' => 'cajas'], function() {
        Route::get('/', [App\Http\Controllers\Api\CajasController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\CajasController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\CajasController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\CajasController::class, 'crearCaja']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\CajasController::class, 'actualizarCaja']);
        Route::post('desactivar/{id}', [App\Http\Controllers\Api\CajasController::class, 'desactivarCaja']);
    });

    // Rutas para Choferes
    Route::group(['prefix' => 'choferes'], function() {
        Route::get('/', [App\Http\Controllers\Api\ChoferesController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\ChoferesController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\ChoferesController::class, 'get']);
        Route::get('viajes/{id}', [App\Http\Controllers\Api\ChoferesController::class, 'getViajes']);
        Route::get('extras/{id}', [App\Http\Controllers\Api\ChoferesController::class, 'getExtras']);
        Route::get('pagos/{id}', [App\Http\Controllers\Api\ChoferesController::class, 'getPagos']);
        Route::post('crear', [App\Http\Controllers\Api\ChoferesController::class, 'crearChofer']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\ChoferesController::class, 'actualizarChofer']);
        Route::delete('desactivar/{id}', [App\Http\Controllers\Api\ChoferesController::class, 'desactivarChofer']);
    });

    // Rutas para Clientes
    Route::group(['prefix' => 'clientes'], function() {
        Route::get('/', [App\Http\Controllers\Api\ClientesController::class, 'getAll']);
        Route::get('todos', [App\Http\Controllers\Api\ClientesController::class, 'getTodos']);
        Route::get('activos', [App\Http\Controllers\Api\ClientesController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\ClientesController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\ClientesController::class, 'crearCliente']);
        Route::put('actualizar/{id}', [App\Http\Controllers\Api\ClientesController::class, 'actualizarCliente']);
        Route::post('desactivar/{id}', [App\Http\Controllers\Api\ClientesController::class, 'desactivarCliente']);
    });

    // Rutas para Diesel
    Route::group(['prefix' => 'diesel'], function() {
        Route::get('/', [App\Http\Controllers\Api\DieselController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\DieselController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\DieselController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\DieselController::class, 'crearDiesel']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\DieselController::class, 'actualizarDiesel']);
        Route::post('desactivar/{id}', [App\Http\Controllers\Api\DieselController::class, 'desactivarDiesel']);
    });

    // Rutas para Extras
    Route::group(['prefix' => 'extras'], function() {
        Route::get('/', [App\Http\Controllers\Api\ExtrasController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\ExtrasController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\ExtrasController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\ExtrasController::class, 'crearExtra']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\ExtrasController::class, 'actualizarExtra']);
        Route::delete('desactivar/{id}', [App\Http\Controllers\Api\ExtrasController::class, 'desactivarExtra']);
    });

    // Rutas para Facturas
    Route::group(['prefix' => 'facturas'], function() {
        Route::get('/', [App\Http\Controllers\Api\FacturasController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\FacturasController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\FacturasController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\FacturasController::class, 'crearFactura']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\FacturasController::class, 'actualizarFactura']);
        Route::delete('desactivar/{id}', [App\Http\Controllers\Api\FacturasController::class, 'desactivarFactura']);
    });

    // Rutas para Pagos a Choferes
    Route::group(['prefix' => 'pagochoferes'], function() {
        Route::get('/', [App\Http\Controllers\Api\PagoChoferesController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\PagoChoferesController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\PagoChoferesController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\PagoChoferesController::class, 'crearPagoChofer']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\PagoChoferesController::class, 'actualizarPagoChofer']);
        Route::delete('desactivar/{id}', [App\Http\Controllers\Api\PagoChoferesController::class, 'desactivarPagoChofer']);
    });

    // Rutas para Rutas
    Route::group(['prefix' => 'rutas'], function() {
        Route::get('/', [App\Http\Controllers\Api\RutasController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\RutasController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\RutasController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\RutasController::class, 'crearRuta']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\RutasController::class, 'actualizarRuta']);
        Route::post('desactivar/{id}', [App\Http\Controllers\Api\RutasController::class, 'desactivarRuta']);
    });

    // Rutas para Viajes
    Route::group(['prefix' => 'viajes'], function() {
        Route::get('/', [App\Http\Controllers\Api\ViajesController::class, 'getAll']);
        Route::get('activos', [App\Http\Controllers\Api\ViajesController::class, 'getActivos']);
        Route::get('{id}', [App\Http\Controllers\Api\ViajesController::class, 'get']);
        Route::post('crear', [App\Http\Controllers\Api\ViajesController::class, 'crearViaje']);
        Route::post('actualizar/{id}', [App\Http\Controllers\Api\ViajesController::class, 'actualizarViaje']);
        Route::post('desactivar/{id}', [App\Http\Controllers\Api\ViajesController::class, 'desactivarViaje']);
    });
});
/** **/