<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TramiteController extends Controller
{
    public function index(Request $request)
    {       
        $codigo = $request->codigo;

        $tramites = DB::table('gt_tramites')
                            ->select('id', 'nombre', 'componente')                            
                            ->where('codigo', $codigo)                            
                            ->orderby('nombre', 'asc')
                            ->get();


        $tramites2= array();

        foreach ($tramites as $indice => $tramite) {
            $tramites2[$indice]['text'] = $tramite->nombre;
            $tramites2[$indice]['value'] = array('id'=>$tramite->id, 'componente'=>$tramite->componente);            
        }

        return json_encode($tramites2);
    }

    /*public function getModalidades(Request $request)
    {
        $idgrado = $request->idgrado;

        $grados_modalidades = DB::TABLE('gt_grado_modalidades')
            ->join('gt_modalidades', 'gt_grado_modalidades.idmodalidad', '=', 'gt_modalidades.id')
            ->select('gt_grado_modalidades.id AS value', 'gt_modalidades.nombre AS text')
            ->where('gt_grado_modalidades.idgrado', '=', $idgrado)
            ->orderby('gt_modalidades.nombre', 'asc')
            ->get();

        return $grados_modalidades;
    }*/
}
