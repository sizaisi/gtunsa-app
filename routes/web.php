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

Auth::routes();

Route::get('/', function () { //mostrar el login
    if(Auth::check()){
        return view('home');
    }

    return view('auth.login');
});

Route::get('/home', function () { // interfaz de inicio del sistema

    if(Auth::check()){
        return view('home');
    }

    return view('auth.login');

})->name('home');

Route::get('/graduando', 'GraduandoController@index');
Route::put('/graduando/actualizar/{id}', 'GraduandoController@update');
Route::post('/graduando/registrar_proyecto', 'GraduandoController@registrarProyecto');
Route::post('/graduando/mover', 'GraduandoController@mover');

Route::get('/escuela', 'EscuelaController@index');

Route::get('/grado_titulo', 'GradoModalidadController@getGradoTitulos');
Route::get('/grado_modalidad', 'GradoModalidadController@getGradoModalidades');

Route::get('/tramite', 'ExpedienteController@index');

Route::get('/expediente/registrar/', 'ExpedienteController@store');

Route::get('/grado_procedimiento', 'GradoProcedimientoController@getAllGradoProcedimientos');
Route::get('/grado_procedimiento_actual', 'GradoProcedimientoController@getGradoProcedimientoActual');
Route::get('/resto_grado_procedimiento', 'GradoProcedimientoController@getRestoGradoProcedimientos');


Route::get('/movimiento', 'MovimientoController@getMovimientos');
Route::get('/movimiento/ruta', 'MovimientoController@getRutas');


 Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
 Route::get('/callback/{provider}', 'SocialController@callback');
