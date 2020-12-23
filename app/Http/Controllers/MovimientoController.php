<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{           
    public function getMovimientos(Request $request)
    {
        $idexpediente = $request->idexpediente;

        $movimientos = DB::table('gt_movimiento AS gt_m')
                            ->join('gt_rutas AS gt_r', 'gt_m.idruta', '=', 'gt_r.id')
                            ->join('gt_procedimientos AS gt_p', 'gt_r.idproc_origen', '=', 'gt_p.id')                            
                            ->join('gt_usuario AS gt_u', 'gt_m.idusuario', '=', 'gt_u.id')
                            ->join('gt_roles AS gt_ro', 'gt_u.idrol_area', '=', 'gt_ro.id')
                            ->select('gt_m.*', 'gt_r.idproc_origen', 'gt_r.etiqueta AS estado',
                                     'gt_p.nombre AS nombre_procedimiento', 'gt_p.descripcion',
                                     'gt_ro.id AS idrol_area', 'gt_ro.nombre AS rol_area', 'gt_p.tipo_rol',
                                     'gt_p.url_formulario', 'gt_p.orden AS nro_orden')
                            ->where('gt_m.idexpediente', $idexpediente)
                            ->orderby('gt_m.id', 'asc')
                            ->get();

        return $movimientos;
    }
    
    public function getRutas(Request $request)
    {
        $idprocedimiento_actual = $request->idprocedimiento_actual;

        $rutas = DB::table('gt_rutas')
                    ->select('*')
                    ->where('idproc_origen', $idprocedimiento_actual)
                    ->get();     
        
        return $rutas;
    }       
}
