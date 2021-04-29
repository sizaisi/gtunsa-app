<?php

namespace App\Http\Controllers;

use App\User;
use App\Estudiante;
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
        $dni = Estudiante::select(DB::raw('(SUBSTRING(dic, 2)) AS dni'))
                        ->where('cui', Auth::user()->cui)
                        ->first()
                        ->dni;        

        return $dni;
    }

    public function getContacto()
    {        
        $contacto = User::select('telefono_fijo', 'telefono_movil', 'direccion')
                            ->where('id', Auth::id())
                            ->first();        

        return json_encode($contacto);
    }  
        
    public function update(Request $request)
    {        
        $this->validate($request, 
            [
                'telefono_fijo' => 'nullable|digits_between:6, 10',
                'telefono_movil' => 'required|digits_between:9, 15',
                'direccion' => 'required|max:150'
            ]
        );

        try {         
            $user_id = Auth::id();

            DB::table('gt_graduando')
                ->where('id', $user_id)
                ->update(
                    [
                        'telefono_fijo' => $request->telefono_fijo,
                        'telefono_movil' => $request->telefono_movil,
                        'direccion' => $request->direccion
                    ]
                );      

            $result = ['successMessage' => 'Información de contacto actualizado con éxito', 'error' => false]; 
            
        } catch (\Exception $e) {                              
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::warning('GraduandoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }      
}
