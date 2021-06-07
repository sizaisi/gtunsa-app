<?php

namespace App\Http\Controllers;

use App\User;
use App\Matricula;
use App\Graduando;
use Illuminate\Http\Request;
use App\Http\Requests\GraduandoRequest;

class GraduandoController extends Controller
{    
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
    
    public function destroy($id)
    {
        //
    }

    public function getEscuelas()
    {
        $matriculas = Matricula::select('nues', 'espe')
                        ->with(['escuela' => function($query) {
                            $query->select('nesc', 'nues', 'nive');                                
                        }])                        
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
