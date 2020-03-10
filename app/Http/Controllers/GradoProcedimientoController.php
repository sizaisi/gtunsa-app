<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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

        $grado_procedimientos = DB::TABLE('GT_GRADO_PROCEDIMIENTO AS gt_gp')
                                ->JOIN('GT_PROCEDIMIENTO AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
                                ->SELECT('gt_gp.id AS idgrado_procedimiento', 'gt_p.nombre AS nombre_procedimiento',
                                         'gt_gp.orden AS idrol_area', 'gt_gp.orden AS nro_orden')
                                ->WHERE('idgrado_modalidad', '=', $idgrado_modalidad)
                                ->ORDERBY('orden', 'asc')
                                ->GET();

        return $grado_procedimientos;
    }

    public function getGradoProcedimientoActual(Request $request)
    {
        $idgrado_procedimiento_actual = $request->idgrado_procedimiento_actual;

        $grado_procedimiento_actual = DB::TABLE('GT_GRADO_PROCEDIMIENTO AS gt_gp')
                                        ->JOIN('GT_PROCEDIMIENTO AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
                                        ->JOIN('GT_ROL_AREA AS gt_ra', 'gt_gp.idrol_area', '=', 'gt_ra.id')
                                        ->SELECT('gt_gp.id AS idgrado_procedimiento', 'gt_p.nombre AS nombre_procedimiento',
                                                 'gt_gp.idrol_area', 'gt_gp.url_formulario', 'gt_p.descripcion',
                                                 'gt_ra.nombre AS rol_area', 'gt_gp.tipo_rol', 'gt_gp.orden AS nro_orden')
                                        ->WHERE('gt_gp.id', '=', $idgrado_procedimiento_actual)
                                        ->FIRST();

        return json_encode($grado_procedimiento_actual);
    }

    public function getRestoGradoProcedimientos(Request $request)
    {
        $idgrado_modalidad = $request->idgrado_modalidad;
        $idgrado_procedimiento_actual = $request->idgrado_procedimiento_actual;

        $nro_orden_proc_actual = DB::TABLE('GT_GRADO_PROCEDIMIENTO')
                                ->SELECT('orden')
                                ->WHERE('id', '=', $idgrado_procedimiento_actual)
                                ->FIRST()
                                ->orden;

        $resto_grado_procedimientos = DB::TABLE('GT_GRADO_PROCEDIMIENTO AS gt_gp')
                                ->JOIN('GT_PROCEDIMIENTO AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
                                ->SELECT('gt_gp.id AS idgrado_procedimiento', 'gt_p.nombre AS nombre_procedimiento',
                                         'gt_gp.orden AS idrol_area', 'gt_gp.orden AS nro_orden')
                                ->WHERE('idgrado_modalidad', '=', $idgrado_modalidad)
                                ->WHERE('orden', '>', $nro_orden_proc_actual)
                                ->WHERE('gt_gp.condicion', '=', '1')
                                ->ORDERBY('orden', 'asc')
                                ->GET();

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
