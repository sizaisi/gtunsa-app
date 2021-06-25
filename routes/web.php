<?php

/*Route::get('/', function () {
    return view('welcome');
});*/
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;


Auth::routes(['register' => true]);

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

Route::get('/api_dni/{dni}', function ($dni) {                
    return file_get_contents("https://dniruc.apisperu.com/api/v1/dni/$dni?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJzaXphQHVuc2EuZWR1LnBlIn0._33jLRFR1pvHFv0z7Lzh6ZysOUfZSYlu7uxxE5Wagwo");
});

Route::get('/graduando', 'GraduandoController@show');
Route::put('/graduando/{graduando}', 'GraduandoController@update');
Route::get('/escuelas', 'GraduandoController@getEscuelas');

Route::get('/tramites', 'TramiteController@index');

Route::get('/expediente/tramite', 'ExpedienteController@index');

Route::post('/bachiller_automatico', 'BachillerAutomaticoController@store');

Route::post('/titulo_tesis', 'TituloTesisController@store');
Route::get('/titulo_tesis/titulo/{idexpediente}', 'TituloTesisController@getTitulo');
Route::put('/titulo_tesis/titulo', 'TituloTesisController@updateTitulo');
Route::get('/titulo_tesis/asesor/{idexpediente}', 'TituloTesisController@getAsesor');
Route::put('/titulo_tesis/asesor', 'TituloTesisController@updateAsesor');
Route::delete('/titulo_tesis/asesor', 'TituloTesisController@deleteAsesor');

Route::get('/procedimiento/actual', 'ProcedimientoController@getProcedimientoActual');
Route::get('/procedimiento/resto', 'ProcedimientoController@getRestoProcedimientos');
Route::get('/procedimiento/rutas', 'ProcedimientoController@getRutas');

Route::get('/movimiento', 'MovimientoController@getMovimientos');
Route::get('/movimiento/ruta', 'MovimientoController@getRutas');
Route::post('/movimiento/mover', 'MovimientoController@mover');

Route::get('/archivo/get', 'ArchivoController@get');
Route::post('/archivo/registrar', 'ArchivoController@store');
Route::delete('/archivo/eliminar', 'ArchivoController@destroy');
Route::get('/archivo/mostrar/{archivo_id}', 'ArchivoController@show');

Route::get('/observaciones/get', 'ObservacionesController@get');

Route::get('/docente/{idexpediente}', 'DocenteController@getDocentes');
Route::get('/docente/getAsesor/{idexpediente}', 'DocenteController@getAsesor');

Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');

Route::post('files/upload-photo/{graduando}', 'GraduandoController@uploadPhoto');

////////////////////////////////////// PDFs /////////////////////////////
Route::get('pdf/bachiller_solicitud_proyecto',[PdfController::class,'bachiller_solicitud_proyecto'])->name('pdf.bachiller_solicitud_proyecto');
Route::get('pdf/bachiller_solicitud_automatico',[PdfController::class,'bachiller_solicitud_automatico'])->name('pdf.bachiller_solicitud_automatico');
Route::get('pdf/titulacion_solicitud_proyecto',[PdfController::class,'titulacion_solicitud_proyecto'])->name('pdf.titulacion_solicitud_proyecto');
Route::get('pdf/titulacion_solicitud_modalidad',[PdfController::class,'titulacion_solicitud_modalidad'])->name('pdf.titulacion_solicitud_modalidad');
