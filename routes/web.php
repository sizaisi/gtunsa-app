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
Route::post('/graduando/registrar_proyecto', 'GraduandoController@registrarProyecto');
Route::post('/graduando/mover', 'GraduandoController@mover');

Route::get('/escuela', 'EscuelaController@index');

Route::get('/GradoModalidad/grado_titulo', 'GradoModalidadController@listGradoTitulo');
Route::get('/GradoModalidad/modalidad_obtencion', 'GradoModalidadController@listModalidadObtencion');

Route::get('/expediente/tramite', 'ExpedienteController@index');
Route::get('/expediente/registrar/', 'ExpedienteController@store');

Route::get('/grado_procedimiento', 'GradoProcedimientoController@getAllGradoProcedimientos');
Route::get('/grado_procedimiento/actual', 'GradoProcedimientoController@getGradoProcedimientoActual');
Route::get('/grado_procedimiento/resto', 'GradoProcedimientoController@getRestoGradoProcedimientos');


Route::get('/movimiento', 'MovimientoController@getMovimientos');
Route::get('/movimiento/ruta', 'MovimientoController@getRutas');


Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');
