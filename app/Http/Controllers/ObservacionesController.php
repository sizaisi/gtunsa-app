<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ObservacionesController extends Controller
{
    public function get(Request $request) 
    {
        $idexpediente = $request->idexpediente;        

        $idlastmovimiento = \DB::table('gt_movimiento')                            
                            ->where('idexpediente', $idexpediente)
                            ->orderBy('id','DESC')
                            ->first()
                            ->id;                                    

        $observaciones = \DB::table('gt_recurso AS GT_R')
                            ->join('gt_observaciones AS GT_O', 'GT_R.id', '=', 'GT_O.idrecurso')
                            ->select('GT_O.descripcion')
                            ->where('GT_R.idmovimiento', $idlastmovimiento)                                        
                            ->get();           
                
        return $observaciones;
    }
}
