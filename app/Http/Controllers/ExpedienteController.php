<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpedienteController extends Controller
{    
    public function index()
    {
        $tramites = DB::table('gt_expediente AS gt_e')            
            ->join('gt_graduando_expediente AS gt_ge', 'gt_e.id', '=', 'gt_ge.idexpediente')
            ->join('gt_procedimientos AS gt_p', 'gt_e.idprocedimiento', '=', 'gt_p.id')
            ->join('gt_tramites AS gt_t', 'gt_t.id', '=', 'gt_p.idtramite')            
            ->join('actescu', 'actescu.nues', '=', 'gt_e.nues')
            ->select(
                'gt_e.id AS idexpediente',
                'gt_e.codigo AS codExpediente',
                'gt_e.idprocedimiento',
                'gt_t.id AS idtramite',
                'gt_t.nombre AS nombre_tramite',                
                'actescu.nesc'
            )
            ->where('gt_ge.idgraduando', Auth::id())
            ->orderby('gt_e.id', 'desc')
            ->get();

        return $tramites;
    }    
}
