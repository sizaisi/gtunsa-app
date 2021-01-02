<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradoModalidadController extends Controller
{
    public function getGrados(Request $request)
    {
        $nive = $request->nive;
        $codigo = $request->codigo;

        $grados = DB::table('gt_grados')
                            ->select('id AS value', 'nombre AS text')
                            ->where('nive', $nive)
                            ->where('codigo', $codigo)                            
                            ->orderby('nombre', 'asc')
                            ->get();

        return $grados;
    }

    public function getModalidades(Request $request)
    {
        $idgrado = $request->idgrado;

        $grados_modalidades = DB::TABLE('gt_grado_modalidades')
            ->join('gt_modalidades', 'gt_grado_modalidades.idmodalidad', '=', 'gt_modalidades.id')
            ->select('gt_grado_modalidades.id AS value', 'gt_modalidades.nombre AS text')
            ->where('gt_grado_modalidades.idgrado', '=', $idgrado)
            ->orderby('gt_modalidades.nombre', 'asc')
            ->get();

        return $grados_modalidades;
    }
}
