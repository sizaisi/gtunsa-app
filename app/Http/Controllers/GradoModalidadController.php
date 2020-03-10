<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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

    public function getGradoTitulos(Request $request) {
        $nive = $request->nive;

        $grado_titulos = DB::TABLE('GT_GRADO_TITULO')
                        ->SELECT('id AS value', 'nombre AS text')                                                
                        ->WHERE('codigo', '=', $nive)  
                        ->ORDERBY('nombre', 'asc')                      
                        ->GET();        

        return $grado_titulos;
    }

    public function getGradoModalidades(Request $request) {
        $idgrado_titulo = $request->idgrado_titulo;

        $grado_modalidades = DB::TABLE('GT_GRADO_MODALIDAD')
                        ->JOIN('GT_MODALIDAD_OBTENCION', 'GT_GRADO_MODALIDAD.idmodalidad_obtencion', '=', 'GT_MODALIDAD_OBTENCION.id')
                        ->SELECT('GT_GRADO_MODALIDAD.id AS value', 'nombre AS text')                                                
                        ->WHERE('idgrado_titulo', '=', $idgrado_titulo)           
                        ->ORDERBY('nombre', 'asc')             
                        ->GET();        

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
