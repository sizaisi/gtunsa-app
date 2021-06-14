<?php

namespace App\Http\Controllers;

use App\User;
use App\Matricula;
use App\Graduando;
use Illuminate\Http\Request;
use App\Http\Requests\GraduandoRequest;
use App\Services\VerifyImageService;
use Illuminate\Support\Facades\Storage;

class GraduandoController extends Controller
{
    protected $verify_img_service;

    public function __construct(VerifyImageService $verify_img_service)
    {
        $this->verify_img_service = $verify_img_service;
    }

    public function index()
    {
        //
    }
 
    public function create()
    {
        //
    }
    
    public function store(Request $request)
    {
        //
    }

    public function show()
    {        
        $graduando = User::find(\Auth::id())->administrado()->with('alumno:cui,dic,apn')->first();

        return $graduando;
    }
   
    public function edit($id)
    {
        //
    }
    
    public function update(GraduandoRequest $request, Graduando $graduando)
    {
        try {        
            $graduando->telefono = $request->telefono;
            $graduando->email_personal = $request->email_personal;
            $graduando->direccion = $request->direccion;

            $graduando->update();

            $result = ['successMessage' => 'Información de contacto actualizado con éxito', 'error' => false];             
        } catch (\Exception $e) {                              
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::warning('GraduandoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }

    public function uploadPhoto(Request $request)
    {
        $this->validate($request, [
            'foto' => 'required|file|mimes:jpeg,jpg|max:1024',
            'cui' => 'required'
        ]);

        if($request->hasfile('foto')){
            $file = $request->file('foto');
            $temp_path = $file->getPathName();
            
            // Validar foto
            $validate_foto_result = $this->verify_img_service->getErrors($temp_path/*, $filename*/);
            if(!$validate_foto_result['ok']){
                return response()->json(['errors' => ['foto' => $validate_foto_result['errors']]], 422);
            }
            // guardar la foto en storage
            $name = $request->cui.'.'.$file->extension();
            $path = '/fotos';
            $file->move(storage_path('app/public').'/'.$path, $name);
            $url = Storage::url('app/public/fotos/'.$name);
        } else {
            return response()->json(['errors' => 'No ha subido el archivo correctamente'], 500);
        }
        return response()->json(['errors' => null], 200);
    }
    
    public function destroy($id)
    {
        //
    }

    public function getEscuelas()
    {
        $matriculas = Matricula::select('nues', 'espe')                        
                        ->with('escuela:nesc,nues,nive')
                        ->where('cui', User::find(\Auth::id())->administrado->cui)                        
                        ->orderBy('nues', 'desc')
                        ->get();        

        $escuelas= array();

        foreach ($matriculas as $indice => $matricula) {
            $escuelas[$indice]['text'] = $matricula->escuela->nesc;
            $escuelas[$indice]['value'] = array('nues'=>$matricula->nues, 
                                                 'espe'=>$matricula->espe, 
                                                 'nive'=>$matricula->escuela->nive);            
        }

        return json_encode($escuelas);
    } 
}
