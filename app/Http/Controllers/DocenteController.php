<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function getDocentes($idexpediente) 
    {        
        $departamentos = \DB::table('gt_expediente AS gt_e')            
                            ->join('actescu AS ac_e', 'gt_e.nues', '=', 'ac_e.nues')        
                            ->join('actdepa AS ac_d', 'ac_e.facu', '=', 'ac_d.facu')
                            ->select('ac_d.depa')
                            ->where('gt_e.id', $idexpediente)                        
                            ->get();

        $departamentos = json_decode($departamentos, true);

        $array_depa = array();

        foreach ($departamentos as $departamento) {
            $array_depa[] = $departamento['depa'];
        }                   

        $docentes = \DB::table('gt_usuario')            
                        ->join('SIAC_DOC', 'gt_usuario.codi_usuario', '=', 'SIAC_DOC.codper')            
                        ->select('gt_usuario.id', \DB::raw('(REPLACE(SIAC_DOC.apn, "/", " ")) AS apn'), 'SIAC_DOC.dic AS nro_documento')
                        ->whereIn('SIAC_DOC.depend', $array_depa)
                        ->orderby('apn', 'asc')
                        ->get();        

        return $docentes;
    }

    public function getAsesor($idexpediente) 
    {     
        $asesor = \DB::table('gt_recurso')            
                        ->join('gt_persona', 'gt_persona.idrecurso', '=', 'gt_recurso.id')            
                        ->join('gt_usuario', 'gt_usuario.id', '=', 'gt_persona.iddocente')            
                        ->join('SIAC_DOC', 'SIAC_DOC.codper', '=', 'gt_usuario.codi_usuario')            
                        ->select(\DB::raw('(REPLACE(SIAC_DOC.apn, "/", " ")) AS apn'))
                        ->where('gt_recurso.idexpediente', $idexpediente)
                        ->where('gt_persona.tipo', 'asesor')                        
                        ->first();               

        return json_encode($asesor);
    }
}
