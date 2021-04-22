<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{           
    public function getMovimientos(Request $request)
    {
        $idexpediente = $request->idexpediente;

        $movimientos = DB::table('gt_movimiento AS gt_m')
                            ->join('gt_rutas AS gt_r', 'gt_m.idruta', '=', 'gt_r.id')
                            ->join('gt_acciones AS gt_a', 'gt_r.idaccion', '=', 'gt_a.id')
                            ->join('gt_procedimientos AS gt_p', 'gt_r.idproc_origen', '=', 'gt_p.id')
                            ->join('gt_roles AS gt_ro', 'gt_p.idrol', '=', 'gt_ro.id')
                            ->select('gt_m.*', 'gt_r.idproc_origen', 'gt_a.nombre AS accion', 'gt_a.color AS color',
                                     'gt_p.nombre AS procedimiento', 'gt_p.descripcion',
                                     'gt_ro.nombre AS rol', 'gt_p.tipo_rol', 'gt_p.componente')
                            ->where('gt_m.idexpediente', $idexpediente)
                            ->orderby('gt_m.id', 'asc')
                            ->get();

        return $movimientos;
    }
    
    public function getRutas(Request $request)
    {           
        $idprocedimiento_actual = $request->idprocedimiento_actual;        

        $rutas = DB::table('gt_rutas AS gt_r')
                    ->join('gt_acciones AS gt_a', 'gt_r.idaccion', '=', 'gt_a.id')
                    ->select('gt_r.*', 'gt_a.nombre AS accion')
                    ->where('gt_r.idproc_origen', $idprocedimiento_actual)
                    ->get();
        
        return $rutas;
    }   
    
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

            $lastmovimiento = \DB::table('gt_movimiento')                            
                            ->where('idexpediente', $idexpediente)
                            ->orderBy('id','DESC')
                            ->first();

            if ($lastmovimiento != null) {
                $idlastmovimiento = $lastmovimiento->id;
            }
            else{
                $idlastmovimiento = null;
            }

            $idmovimiento = DB::table('gt_movimiento')
                ->insertGetId([
                    'idexpediente' => $idexpediente,
                    'idusuario' => $idusuario,
                    'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'idruta' => $idruta,
                    'idmov_anterior' => $idlastmovimiento
                ]);

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idprocedimiento' => $idproc_destino]);          
            
            DB::table('gt_recurso')
                ->where([
                    ['idexpediente', '=', $idexpediente],
                    ['idprocedimiento', '=', $idproc_origen],
                    ['idusuario', '=', $idusuario]
                ])                
                ->update([
                    'idmovimiento' => $idmovimiento,
                    'idruta' => $idruta        
                ]);

            DB::commit();
            $result = ['successMessage' => 'El expediente fue derivado satisfactoriamente.', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('GraduandoController@mover, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }
}
