<?php

namespace App\Http\Controllers;

use App\User;
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
            
            $bachiller_automatico_id = DB::table('gt_bachiller_automatico')
                                            ->insertGetId([
                                                'nro_resolucion' => 'resolucion prueba',                                                
                                            ]);

            $expediente_id = DB::table('gt_expediente')
                ->insertGetId([
                    'tramite_id' => $tramite_id,
                    'expedienteable_id' => $bachiller_automatico_id,
                    'procedimiento_id' => $procedimiento_id,
                    'nues' => $nues,
                    'espe' => $espe,
                    'codigo' => '',                    
                    'estado' => 'En trámite',                    
                ]);

            $digitos = strlen(strval($expediente_id));
            $codExpediente = 'E-';

            for ($i = 0; $i < 7 - $digitos; $i++) {
                $codExpediente .= '0';
            }

            $codExpediente .= strval($expediente_id);

            DB::table('gt_expediente')
                ->where('id', $expediente_id)
                ->update(['codigo' => $codExpediente]);

            DB::table('gt_graduando_expediente')
                ->insert([
                    'graduando_id' => User::find(Auth::id())->administrado->id,
                    'expediente_id' => $expediente_id,
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
