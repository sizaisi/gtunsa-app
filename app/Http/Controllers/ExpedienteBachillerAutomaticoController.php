<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpedienteBachillerAutomaticoController extends Controller
{
    public function store(Request $request)
    {                       
        $tramite_id = $request->tramite_id;
        $nues = $request->nues;
        $espe = $request->espe;       
        
        $dni = $request->graduando['dni'];
        $nombres = $request->graduando['nombres'];
        $apellidos = $request->graduando['apellidos'];

        /*$array_apellidos = explode(" ", $apellidos);
        $ap_paterno = $array_apellidos[0];
        $ap_materno = $array_apellidos[1];*/

        try {
            DB::beginTransaction();

            $procedimiento_id = 2;

            /*$idprocedimiento = DB::table('gt_procedimiento AS gt_p')
                ->join('gt_rutas AS gt_r', 'gt_p.id', '=', 'gt_r.idproc_destino')
                ->join('gt_acciones AS gt_a', 'gt_a.id', '=', 'gt_r.idaccion')
                ->select('gt_r.idproc_destino AS idprocedimiento')
                ->where('gt_p.idtramite', $idtramite)
                ->where('gt_a.nombre', 'Iniciar Expediente')
                ->first()
                ->idprocedimiento;*/

            $mytime = Carbon::now('America/Lima');

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
                    //'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'estado' => 'iniciado',
                    //'fing' => $mytime->format('Y-m-d H:i:s')
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
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,                    
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
