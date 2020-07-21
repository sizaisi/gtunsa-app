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

    public function listGradoTitulo(Request $request) {
        $nive = $request->nive;

        $grado_titulos = DB::table('gt_grado_titulo')
                            ->select('id AS value', 'nombre AS text')                                                
                            ->where('codigo', '=', $nive)  
                            ->orderby('nombre', 'asc')                      
                            ->get();        

        return $grado_titulos;
    }

    public function listModalidadObtencion(Request $request) {
        $idgrado_titulo = $request->idgrado_titulo;

        $grado_modalidades = DB::TABLE('gt_grado_modalidad')
                                ->join('gt_modalidad_obtencion', 'GT_GRADO_MODALIDAD.idmodalidad_obtencion', '=', 'GT_MODALIDAD_OBTENCION.id')
                                ->select('gt_grado_modalidad.id AS value', 'nombre AS text')                                                
                                ->where('idgrado_titulo', '=', $idgrado_titulo)           
                                ->orderby('nombre', 'asc')             
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
