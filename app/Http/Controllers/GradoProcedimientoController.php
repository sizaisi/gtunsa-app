<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradoProcedimientoController extends Controller
{       
    public function getAllGradoProcedimientos(Request $request)
    {
        $idgrado_modalidad = $request->idgrado_modalidad;

        $grado_procedimientos = DB::table('gt_grado_procedimiento AS gt_gp')
            ->join('gt_procedimiento AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
            ->select(
                'gt_gp.id AS idgrado_procedimiento',
                'gt_p.nombre AS nombre_procedimiento',
                'gt_gp.orden AS idrol_area',
                'gt_gp.orden AS nro_orden'
            )
            ->where('idgrado_modalidad', '=', $idgrado_modalidad)
            ->orderby('orden', 'asc')
            ->get();

        return $grado_procedimientos;
    }

    public function getGradoProcedimientoActual(Request $request)
    {        
        $idexpediente = $request->idexpediente;

        $idprocedimiento_actual = DB::table('gt_expediente')
                                        ->select('idgrado_procedimiento AS idprocedimiento_actual')
                                        ->where('id', $idexpediente)
                                        ->first()
                                        ->idprocedimiento_actual;    

        $procedimiento_actual = DB::table('gt_procedimientos AS gt_p')            
            ->join('gt_roles AS gt_r', 'gt_p.idrol', '=', 'gt_r.id')
            ->select(
                'gt_p.id AS idgrado_procedimiento',
                'gt_p.nombre AS nombre_procedimiento',
                'gt_p.idrol',
                'gt_p.url_formulario',
                'gt_p.descripcion',
                'gt_r.nombre AS rol_area',
                'gt_p.tipo_rol',
                'gt_p.orden AS nro_orden'
            )
            ->where('gt_p.id', '=', $idprocedimiento_actual)
            ->first();

        return json_encode($procedimiento_actual);
    }

    public function getRestoGradoProcedimientos(Request $request)
    {
        $idgrado_modalidad = $request->idgrado_modalidad;
        $idprocedimiento_actual = $request->idgrado_procedimiento_actual;

        $nro_orden_proc_actual = DB::table('gt_procedimientos')
            ->select('orden')
            ->where('id', '=', $idprocedimiento_actual)
            ->first()
            ->orden;

        $resto_procedimientos = DB::table('gt_procedimientos')            
            ->select(
                'id AS idgrado_procedimiento',
                'nombre AS nombre_procedimiento',
                'idrol AS idrol_area',
                'orden AS nro_orden'
            )
            ->where('idgradomodalidad', '=', $idgrado_modalidad)
            ->where('orden', '>', $nro_orden_proc_actual)            
            ->orderby('orden', 'asc')
            ->get();

        return $resto_procedimientos;
    }  
}
