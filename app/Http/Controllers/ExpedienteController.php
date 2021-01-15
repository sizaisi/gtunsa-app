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
    
    public function store(Request $request)
    {
        $idtramite = $request->idtramite;
        $nues = $request->nues;
        $espe = $request->espe;       

        try {
            DB::beginTransaction();

            $idprocedimiento = DB::table('gt_procedimientos AS gt_p')
                ->join('gt_rutas AS gt_r', 'gt_p.id', '=', 'gt_r.idproc_destino')
                ->select('gt_r.idproc_destino AS idprocedimiento')
                ->where('gt_p.idtramite', '=', $idtramite)
                ->where('gt_r.etiqueta', '=', 'iniciar')
                ->first()
                ->idprocedimiento;

            $mytime = Carbon::now('America/Lima');

            $idexpediente = DB::table('gt_expediente')
                ->insertGetId([
                    'idprocedimiento' => $idprocedimiento,
                    'nues' => $nues,
                    'espe' => $espe,
                    'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'estado' => 'iniciado',
                    'fing' => $mytime->format('Y-m-d H:i:s')
                ]);

            $digitos = strlen(strval($idexpediente));
            $codExpediente = 'E-';

            for ($i = 0; $i < 7 - $digitos; $i++) {
                $codExpediente .= '0';
            }

            $codExpediente .= strval($idexpediente);

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['codigo' => $codExpediente]);

            DB::table('gt_graduando_expediente')
                ->insert([
                    'idgraduando' => Auth::id(),
                    'idexpediente' => $idexpediente,
                ]);           

            DB::commit();
            $result = ['successMessage' => 'Trámite registrado con éxito', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }   

    public function getAsesor($idexpediente) 
    {               
        $asesor = \DB::table('gt_expediente AS gt_e')            
                            ->join('gt_usuario AS gt_u', 'gt_u.id', '=', 'gt_e.idasesor')        
                            ->join('SIAC_DOC AS ac_doc', 'ac_doc.codper', '=', 'gt_u.codi_usuario')
                            ->join('actdepa AS ac_depa', 'ac_depa.depa', '=', 'ac_doc.depend')
                            ->select('ac_doc.correo', 'ac_depa.ndep as departamento',
                                     \DB::raw('(REPLACE(ac_doc.apn, "/", " ")) AS apn')
                                    )
                            ->where('gt_e.id', $idexpediente)                        
                            ->first();       
        

        return json_encode($asesor);
    }

    public function updateAsesor(Request $request) 
    {
        $idexpediente = $request->idexpediente;
        $idasesor = $request->idasesor;

        try {
            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idasesor' => $idasesor]);

            $result = ['successMessage' => 'Asesor propuesto registrado con éxito', 'error' => false];
        } catch (\Exception $e) {            
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteController@updateAsesor, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }

    public function deleteAsesor(Request $request) 
    {
        $idexpediente = $request->idexpediente;        

        try {
            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idasesor' => NULL]);

            $result = ['successMessage' => 'Asesor propuesto eliminado con éxito', 'error' => false];
        } catch (\Exception $e) {            
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteController@deleteAsesor, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }
}
