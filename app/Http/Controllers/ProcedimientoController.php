<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcedimientoController extends Controller
{     
    public function getProcedimientoActual(Request $request)
    {        
        $idexpediente = $request->idexpediente;

        $idprocedimiento_actual = DB::table('gt_expediente')
                                        ->select('procedimiento_id AS idprocedimiento_actual')
                                        ->where('id', $idexpediente)
                                        ->first()
                                        ->idprocedimiento_actual;    

        $procedimiento_actual = DB::table('gt_procedimiento AS gt_p')            
                                    ->join('roles AS gt_r', 'gt_p.rol_id', '=', 'gt_r.id')
                                    ->select('gt_p.id', 'gt_p.nombre AS procedimiento',
                                            'gt_p.rol_id', 'gt_p.descripcion', 'gt_p.componente',
                                            'gt_r.name AS rol', 'gt_p.tipo_rol_docente as tipo_rol')
                                    ->where('gt_p.id', '=', $idprocedimiento_actual)
                                    ->first();

        return json_encode($procedimiento_actual);
    }

    public function getRestoProcedimientos(Request $request)
    {
        $idtramite = $request->idtramite;
        $idprocedimiento_actual = $request->idprocedimiento_actual;

        $nro_orden_proc_actual = DB::table('gt_procedimiento')
                                    ->select('orden')
                                    ->where('id', $idprocedimiento_actual)
                                    ->first()
                                    ->orden;

        $resto_procedimientos = DB::table('gt_procedimiento')            
                                    ->select('nombre AS procedimiento')
                                    ->where('tramite_id', $idtramite)
                                    ->where('orden', '>', $nro_orden_proc_actual)            
                                    ->orderby('orden', 'asc')
                                    ->get();

        return $resto_procedimientos;
    }  
}
