<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes(['register' => false]);

Route::get('/', function () { //mostrar el login
    if (Auth::check()) {
        return view('home');
    }

    return view('auth.login');
});

Route::get('/home', function () { // interfaz de inicio del sistema

    if (Auth::check()) {
        return view('home');
    }

    return view('auth.login');
})->name('home');

Route::get('/graduando', 'GraduandoController@show');
Route::get('/graduando/contacto', 'GraduandoController@getContacto');
Route::put('/graduando/actualizar', 'GraduandoController@update');

Route::get('/escuela', 'EscuelaController@index');

Route::get('/tramites', 'TramiteController@index');

//Expedientes en general
Route::get('/expediente/tramite', 'ExpedienteController@index');

//Expedientes de bachiller automático
Route::post('/expediente_bachiller_automatico', 'ExpedienteBachillerAutomaticoController@store');

//Expedientes de titulo profesional por sustentación de tesis
Route::post('/expediente_titulo_tesis', 'ExpedienteTituloTesisController@store');
Route::get('/expediente_titulo_tesis/titulo/{idexpediente}', 'ExpedienteTituloTesisController@getTitulo');
Route::put('/expediente_titulo_tesis/titulo', 'ExpedienteTituloTesisController@updateTitulo');
Route::get('/expediente_titulo_tesis/asesor/{idexpediente}', 'ExpedienteTituloTesisController@getAsesor');
Route::put('/expediente_titulo_tesis/asesor', 'ExpedienteTituloTesisController@updateAsesor');
Route::delete('/expediente_titulo_tesis/asesor', 'ExpedienteTituloTesisController@deleteAsesor');

Route::get('/procedimiento/actual', 'ProcedimientoController@getProcedimientoActual');
Route::get('/procedimiento/resto', 'ProcedimientoController@getRestoProcedimientos');

Route::get('/movimiento', 'MovimientoController@getMovimientos');
Route::get('/movimiento/ruta', 'MovimientoController@getRutas');
Route::post('/movimiento/mover', 'MovimientoController@mover');

Route::get('/archivo/get', 'ArchivoController@get');
Route::post('/archivo/registrar', 'ArchivoController@store');
Route::delete('/archivo/eliminar', 'ArchivoController@destroy');
Route::get('/archivo/mostrar/{idrecurso}', 'ArchivoController@show');

Route::get('/observaciones/get', 'ObservacionesController@get');

Route::get('/docente/{idexpediente}', 'DocenteController@getDocentes');
Route::get('/docente/getAsesor/{idexpediente}', 'DocenteController@getAsesor');


Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');
