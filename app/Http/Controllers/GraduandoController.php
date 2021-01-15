<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GraduandoController extends Controller
{    
    public function mover(Request $request)
    {              
        $idruta = $request->idruta;
        $idexpediente = $request->idexpediente;
        $idproc_origen = $request->idproc_origen;
        $idproc_destino = $request->idproc_destino;

        try {

            DB::beginTransaction();

            $idusuario = DB::TABLE('gt_usuario')
                ->SELECT('id AS idusuario')
                ->WHERE('codi_usuario', '=', Auth::user()->cui)
                ->FIRST()
                ->idusuario;

            $mytime = Carbon::now('America/Lima');

            $idlastmovimiento = \DB::table('gt_movimiento')                            
                            ->where('idexpediente', $idexpediente)
                            ->orderBy('id','DESC')
                            ->first()
                            ->id; 

            DB::table('gt_movimiento')
                ->insert([
                    'idexpediente' => $idexpediente,
                    'idusuario' => $idusuario,
                    'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'idruta' => $idruta,
                    'idmov_anterior' => $idlastmovimiento
                ]);

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idprocedimiento' => $idproc_destino]);

            DB::commit();
            $result = ['successMessage' => 'El expediente fue derivado satisfactoriamente.', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('GraduandoController@mover, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }
    }

    public function registrarProyecto(Request $request)
    {
        $titulo = $request->titulo;
        $idexpediente = $request->idexpediente;        
        $idruta = $request->idruta;
        $idproc_origen = $request->idproc_origen;
        $idproc_destino = $request->idproc_destino;

        //dd($request->all());

        try {
            DB::beginTransaction();

            $idusuario = DB::table('gt_usuario')
                ->select('id AS idusuario')
                ->where('codi_usuario', Auth::user()->cui)
                ->first()
                ->idusuario;

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['titulo' => $titulo]);

            $mytime = Carbon::now('America/Lima');

            $idmovimiento = DB::table('gt_movimiento')
                ->insertGetId([
                    'idexpediente' => $idexpediente,
                    'idusuario' => $idusuario,
                    'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'idruta' => $idruta,
                    'idmov_anterior' => '0'
                ]);

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idprocedimiento' => $idproc_destino]);

            DB::commit();
            $result = ['successMessage' => 'Proyecto de tesis registrado con éxito', 'error' => false];                   
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('GraduandoController@registrarProyecto, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }

    public function registrarRequisitos(Request $request)
    {        
        $idexpediente = $request->idexpediente;        
        $idruta = $request->idruta;
        $idproc_origen = $request->idproc_origen;
        $idproc_destino = $request->idproc_destino;        

        try {
            DB::beginTransaction();

            $idusuario = DB::table('gt_usuario')
                ->select('id AS idusuario')
                ->where('codi_usuario', Auth::user()->cui)
                ->first()
                ->idusuario;            

            $mytime = Carbon::now('America/Lima');

            $idmovimiento = DB::table('gt_movimiento')
                ->insertGetId([
                    'idexpediente' => $idexpediente,
                    'idusuario' => $idusuario,
                    'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'idruta' => $idruta,
                    'idmov_anterior' => '0'
                ]);

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idprocedimiento' => $idproc_destino]);

            DB::commit();
            $result = ['successMessage' => 'Requisitos externos registrado con éxito', 'error' => false];                   
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('GraduandoController@registrarRequisitos, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }
    
    public function show()
    {        
        $graduando = User::join('acdiden', 'gt_graduando.cui', '=', 'acdiden.cui')
                        ->select(                
                            'gt_graduando.cui', 
                            DB::raw('(SUBSTRING(acdiden.dic, 2)) AS dni'),
                            DB::raw('(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 1)) AS apellidos'),
                            DB::raw('(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 2), ",", -1)) AS nombres')
                        )
                        ->where('gt_graduando.id', '=', Auth::id())
                        ->first();        

        return json_encode($graduando);
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
