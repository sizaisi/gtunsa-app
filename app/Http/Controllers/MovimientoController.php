<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    //get movimientos por expediente
    public function getMovimientos(Request $request)
    {
        $idexpediente = $request->idexpediente;

        $movimientos = DB::TABLE('GT_MOVIMIENTO AS gt_m')
                            ->JOIN('GT_RUTA AS gt_r', 'gt_m.idruta', '=', 'gt_r.id')
                            ->JOIN('GT_GRADO_PROCEDIMIENTO AS gt_gp', 'gt_r.idgradproc_origen', '=', 'gt_gp.id')
                            ->JOIN('GT_PROCEDIMIENTO AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
                            ->JOIN('GT_USUARIO AS gt_u', 'gt_m.idusuario', '=', 'gt_u.id')
                            ->JOIN('GT_ROL_AREA AS gt_ra', 'gt_u.idrol_area', '=', 'gt_ra.id')
                            ->SELECT('gt_m.*', 'gt_r.idgradproc_origen', 'gt_r.etiqueta AS estado',
                                     'gt_p.nombre AS nombre_procedimiento', 'gt_gp.descripcion',
                                     'gt_ra.id AS idrol_area', 'gt_ra.nombre AS rol_area', 'gt_gp.tipo_rol',
                                     'gt_gp.url_formulario', 'gt_gp.orden AS nro_orden')
                            ->WHERE('idexpediente', $idexpediente)
                            ->ORDERBY('gt_m.id', 'asc')
                            ->GET();

        return $movimientos;
    }

    //get rutas de un procedimiento cualquiera
    public function getRutas(Request $request)
    {
        $idgrado_procedimiento_actual = $request->idgrado_procedimiento_actual;

        $rutas = DB::TABLE('GT_RUTA')
                            ->SELECT('*')
                            ->WHERE('idgradproc_origen', $idgrado_procedimiento_actual)
                            ->GET();

        return $rutas;
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
