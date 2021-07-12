<?php

namespace App\Http\Controllers;

use App\User;
use App\Expediente;
use App\BachillerAutomatico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BachillerAutomaticoController extends Controller
{
    public function store(Request $request)
    {          
        $tramite_id = $request->tramite_id;
        $nues = $request->nues;
        $espe = $request->espe;                     
        
        try {
            DB::beginTransaction();            

            $procedimiento_id = DB::table('gt_procedimiento AS gt_p')
                ->join('gt_ruta AS gt_r', 'gt_p.id', '=', 'gt_r.procedimiento_destino_id')
                ->join('gt_accion AS gt_a', 'gt_a.id', '=', 'gt_r.accion_id')
                ->select('gt_r.procedimiento_destino_id AS procedimiento_id')
                ->where('gt_p.tramite_id', $tramite_id)
                ->where('gt_a.nombre', 'Iniciar')
                ->first()
                ->procedimiento_id;

            $denominacion_id = DB::table('gt_grado_titulo')                
                ->select('denominacion_id')
                ->where('tramite_id', $tramite_id)
                ->where('nuesmen', $nues.$espe)
                ->first()
                ->denominacion_id;           

            $bachiller_automatico = new BachillerAutomatico();
            $bachiller_automatico->save();

            $expediente = new Expediente();
            $expediente->tramite_id = $tramite_id;
            $expediente->expedienteable_id = $bachiller_automatico->id;
            $expediente->procedimiento_id = $procedimiento_id;
            $expediente->nues = $nues;
            $expediente->espe = $espe;
            $expediente->codigo = '';
            $expediente->denominacion_id = $denominacion_id;
            $expediente->estado = 'En trámite';
            $expediente->save();

            $digitos = strlen(strval($expediente->id));
            $codExpediente = 'E-';

            for ($i = 0; $i < 7 - $digitos; $i++) {
                $codExpediente .= '0';
            }

            $codExpediente .= strval($expediente->id);

            DB::table('gt_expediente')
                ->where('id', $expediente->id)
                ->update(['codigo' => $codExpediente]);

            DB::table('gt_graduando_expediente')
                ->insert([
                    'graduando_id' => User::find(Auth::id())->administrado->id,
                    'expediente_id' => $expediente->id,                   
                ]);                                                  
            
            DB::table('gt_graduando')
                ->where('id', User::find(Auth::id())->administrado->id)
                ->update([                    
                    'nombres' => $request->nombres,
                    'apellidos' => $request->apellidos,                    
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
