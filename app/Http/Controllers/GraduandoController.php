<?php

namespace App\Http\Controllers;

use App\User;
use App\Estudiante;
use App\Matricula;
use App\Escuela;
use App\Graduando;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GraduandoController extends Controller
{      
    public function show()
    {          
        $graduando = Estudiante::where('cui', Auth::user()->cui)
                                ->select(                                                    
                                    DB::raw('(SUBSTRING(dic, 2)) AS dni'),
                                    DB::raw('(SUBSTRING_INDEX(REPLACE(apn, "/", " "), ",", 1)) AS apellidos'),
                                    DB::raw('(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(apn, "/", " "), ",", 2), ",", -1)) AS nombres')
                                )
                                ->first();    

        return json_encode($graduando);
    }

    public function getDNI()
    {        
        $dni = substr(User::find(Auth::id())->administrado->estudiante->dic, 1);

        return $dni;
    }

    public function getContacto()
    {        
        $contacto = User::with('administrado')->where('id', Auth::id())
                        ->select('tipo_administrado', 'administrado_id')
                        ->first();

        return json_encode($contacto);
    }  

    public function getEscuelas()
    {
        $matriculas = Matricula::select('nues', 'espe')
                        ->with(['escuela' => function($query) {
                            $query->select('nesc', 'nues', 'nive');                                
                        }])                        
                        ->where('cui', User::find(Auth::id())->administrado->cui)                        
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
        
    public function update(Request $request, Graduando $graduando)
    {                      
        
        $this->validate($request, 
            [
                'telefono' => 'required|digits_between:6, 10',
                'email_personal' => 'required|email',
                'direccion' => 'required|max:150'
            ]
        );

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
}
