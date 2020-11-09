<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradoProcedimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

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

        $idgrado_procedimiento_actual = DB::table('gt_expediente')
                                        ->select('idgrado_procedimiento AS idgrado_procedimiento_actual')
                                        ->where('id', $idexpediente)
                                        ->first()
                                        ->idgrado_procedimiento_actual;    

        $grado_procedimiento_actual = DB::table('gt_grado_procedimiento AS gt_gp')
            ->join('gt_procedimiento AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
            ->join('gt_rol_area AS gt_ra', 'gt_gp.idrol_area', '=', 'gt_ra.id')
            ->select(
                'gt_gp.id AS idgrado_procedimiento',
                'gt_p.nombre AS nombre_procedimiento',
                'gt_gp.idrol_area',
                'gt_gp.url_formulario',
                'gt_gp.descripcion',
                'gt_ra.nombre AS rol_area',
                'gt_gp.tipo_rol',
                'gt_gp.orden AS nro_orden'
            )
            ->where('gt_gp.id', '=', $idgrado_procedimiento_actual)
            ->first();

        return json_encode($grado_procedimiento_actual);
    }

    public function getRestoGradoProcedimientos(Request $request)
    {
        $idgrado_modalidad = $request->idgrado_modalidad;
        $idgrado_procedimiento_actual = $request->idgrado_procedimiento_actual;

        $nro_orden_proc_actual = DB::table('gt_grado_procedimiento')
            ->select('orden')
            ->where('id', '=', $idgrado_procedimiento_actual)
            ->first()
            ->orden;

        $resto_grado_procedimientos = DB::table('gt_grado_procedimiento AS gt_gp')
            ->join('gt_procedimiento AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
            ->select(
                'gt_gp.id AS idgrado_procedimiento',
                'gt_p.nombre AS nombre_procedimiento',
                'gt_gp.orden AS idrol_area',
                'gt_gp.orden AS nro_orden'
            )
            ->where('idgrado_modalidad', '=', $idgrado_modalidad)
            ->where('orden', '>', $nro_orden_proc_actual)
            ->where('gt_gp.condicion', '=', '1')
            ->orderby('orden', 'asc')
            ->get();

        return $resto_grado_procedimientos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
