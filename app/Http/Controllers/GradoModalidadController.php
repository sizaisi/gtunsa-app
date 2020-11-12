<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradoModalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function listGradoTitulo(Request $request)
    {
        $nive = $request->nive;
        $codigo = $request->codigo;

        $grado_titulos = DB::table('gt_grado_titulo')
                            ->select('id AS value', 'nombre AS text')
                            ->where('nive', $nive)
                            ->where('codigo', $codigo)
                            ->where('condicion', 1)
                            ->orderby('nombre', 'asc')
                            ->get();

        return $grado_titulos;
    }

    public function listModalidadObtencion(Request $request)
    {
        $idgrado_titulo = $request->idgrado_titulo;

        $grado_modalidades = DB::TABLE('gt_grado_modalidad')
            ->join('gt_modalidad_obtencion', 'gt_grado_modalidad.idmodalidad_obtencion', '=', 'gt_modalidad_obtencion.id')
            ->select('gt_grado_modalidad.id AS value', 'gt_modalidad_obtencion.nombre AS text')
            ->where('gt_grado_modalidad.idgrado_titulo', '=', $idgrado_titulo)
            ->orderby('gt_modalidad_obtencion.nombre', 'asc')
            ->get();

        return $grado_modalidades;
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
