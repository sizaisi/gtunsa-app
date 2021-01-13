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
Route::post('/graduando/registrar_proyecto', 'GraduandoController@registrarProyecto');
Route::post('/graduando/registrar_requisitos', 'GraduandoController@registrarRequisitos');
Route::post('/graduando/mover', 'GraduandoController@mover');

Route::get('/escuela', 'EscuelaController@index');

Route::get('/tramites', 'TramiteController@index');
//Route::get('/GradoModalidad/getModalidades', 'GradoModalidadController@getModalidades');

Route::get('/expediente/tramite', 'ExpedienteController@index');
Route::post('/expediente/registrar', 'ExpedienteController@store');

Route::get('/procedimiento/actual', 'ProcedimientoController@getProcedimientoActual');
Route::get('/procedimiento/resto', 'ProcedimientoController@getRestoProcedimientos');

Route::get('/movimiento', 'MovimientoController@getMovimientos');
Route::get('/movimiento/ruta', 'MovimientoController@getRutas');

Route::get('/archivo/get', 'ArchivoController@get');
Route::post('/archivo/registrar', 'ArchivoController@store');
Route::delete('/archivo/eliminar', 'ArchivoController@destroy');

Route::get('/archivo/mostrar/{idrecurso}', 'ArchivoController@show');


Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');
