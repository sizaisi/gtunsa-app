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
                                        ->select('idgrado_procedimiento AS idprocedimiento_actual')
                                        ->where('id', $idexpediente)
                                        ->first()
                                        ->idprocedimiento_actual;    

        $procedimiento_actual = DB::table('gt_procedimientos AS gt_p')            
            ->join('gt_roles AS gt_r', 'gt_p.idrol', '=', 'gt_r.id')
            ->select('gt_p.id', 'gt_p.nombre AS procedimiento',
                     'gt_p.idrol', 'gt_p.componente', 'gt_p.descripcion',
                     'gt_r.nombre AS rol', 'gt_p.tipo_rol')
            ->where('gt_p.id', '=', $idprocedimiento_actual)
            ->first();

        return json_encode($procedimiento_actual);
    }

    public function getRestoProcedimientos(Request $request)
    {
        $idgrado_modalidad = $request->idgrado_modalidad;
        $idprocedimiento_actual = $request->idprocedimiento_actual;

        $nro_orden_proc_actual = DB::table('gt_procedimientos')
            ->select('orden')
            ->where('id', '=', $idprocedimiento_actual)
            ->first()
            ->orden;

        $resto_procedimientos = DB::table('gt_procedimientos')            
            ->select('nombre AS procedimiento')
            ->where('idgradomodalidad', '=', $idgrado_modalidad)
            ->where('orden', '>', $nro_orden_proc_actual)            
            ->orderby('orden', 'asc')
            ->get();

        return $resto_procedimientos;
    }  
}
