<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpedienteBachillerAutomaticoController extends Controller
{
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
                
            DB::table('gt_exp_bachiller_automatico')
                ->insert([                    
                    'idexpediente' => $idexpediente,
                ]);        

            DB::commit();
            $result = ['successMessage' => 'Trámite registrado con éxito', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteBachillerAutomaticoController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }   
}
