<?php

namespace App\Http\Controllers;

use App\Expediente;
use App\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{           
    public function getMovimientos(Request $request)
    {
        $idexpediente = $request->idexpediente;

        $movimientos = DB::table('gt_movimiento AS gt_m')
                            ->join('gt_ruta AS gt_r', 'gt_m.ruta_id', '=', 'gt_r.id')
                            ->join('gt_accion AS gt_a', 'gt_r.accion_id', '=', 'gt_a.id')
                            ->join('gt_procedimiento AS gt_p', 'gt_r.procedimiento_origen_id', '=', 'gt_p.id')
                            ->join('roles AS gt_ro', 'gt_p.rol_id', '=', 'gt_ro.id')
                            ->select('gt_m.*', 'gt_r.procedimiento_origen_id as idproc_origen', 
                                     'gt_a.nombre AS accion', 'gt_a.color AS color',
                                     'gt_p.nombre AS procedimiento', 'gt_p.descripcion',
                                     'gt_ro.name AS rol', 'gt_ro.id AS rol_id', 'gt_p.tipo_rol_docente as tipo_rol')
                            ->where('gt_m.expediente_id', $idexpediente)
                            ->orderby('gt_m.id', 'asc')
                            ->get();

        return $movimientos;
    }
    
    public function getRutas(Request $request)
    {           
        $idprocedimiento_actual = $request->idprocedimiento_actual;        

        $rutas = DB::table('gt_ruta AS gt_r')
                    ->join('gt_accion AS gt_a', 'gt_r.accion_id', '=', 'gt_a.id')
                    ->select('gt_r.*', 'gt_a.nombre AS accion')
                    ->where('gt_r.procedimiento_origen_id', $idprocedimiento_actual)
                    ->get();
        
        return $rutas;
    }   
    
    public function mover(Request $request)
    {              
        try {
            DB::beginTransaction();            

            $movimiento = new Movimiento();
            $movimiento->user_id = Auth::id();
            $movimiento->expediente_id = $request->expediente_id;
            $movimiento->ruta_id = $request->ruta['id'];
            $movimiento->save();           

            $expediente = Expediente::find($request->expediente_id);
            $expediente->procedimiento_id = $request->ruta['procedimiento_destino_id'];
            $expediente->update();            

            DB::commit();
            $result = ['successMessage' => 'Expediente derivado con éxito', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('MovimientoController@mover, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }
}
