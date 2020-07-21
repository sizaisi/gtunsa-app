<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $movimientos = DB::table('gt_movimiento AS gt_m')
                            ->join('gt_ruta AS gt_r', 'gt_m.idruta', '=', 'gt_r.id')
                            ->join('gt_grado_procedimiento AS gt_gp', 'gt_r.idgradproc_origen', '=', 'gt_gp.id')
                            ->join('gt_procedimiento AS gt_p', 'gt_gp.idprocedimiento', '=', 'gt_p.id')
                            ->join('gt_usuario AS gt_u', 'gt_m.idusuario', '=', 'gt_u.id')
                            ->join('gt_rol_area AS gt_ra', 'gt_u.idrol_area', '=', 'gt_ra.id')
                            ->select('gt_m.*', 'gt_r.idgradproc_origen', 'gt_r.etiqueta AS estado',
                                     'gt_p.nombre AS nombre_procedimiento', 'gt_gp.descripcion',
                                     'gt_ra.id AS idrol_area', 'gt_ra.nombre AS rol_area', 'gt_gp.tipo_rol',
                                     'gt_gp.url_formulario', 'gt_gp.orden AS nro_orden')
                            ->where('idexpediente', $idexpediente)
                            ->orderby('gt_m.id', 'asc')
                            ->get();

        return $movimientos;
    }

    //get rutas de un procedimiento cualquiera
    public function getRutas(Request $request)
    {
        $idgrado_procedimiento_actual = $request->idgrado_procedimiento_actual;

        $rutas = DB::table('gt_ruta')
                    ->select('*')
                    ->where('idgradproc_origen', $idgrado_procedimiento_actual)
                    ->get();

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
