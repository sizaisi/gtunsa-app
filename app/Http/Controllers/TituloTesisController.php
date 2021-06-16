<?php

namespace App\Http\Controllers;

use App\User;
use App\Expediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TituloTesisController extends Controller
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
            
            $titulo_tesis_id = DB::table('gt_titulo_tesis')
                                            ->insertGetId([
                                                'fecha_sustentacion' => '2021-06-09',                                                
                                            ]);

            $expediente_id = DB::table('gt_expediente')
                ->insertGetId([
                    'tramite_id' => $tramite_id,
                    'expedienteable_id' => $titulo_tesis_id,
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
            \Log::error('TituloTesisController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }  

    public function getTitulo($idexpediente) 
    {                      
        $titulo_tesis_id = Expediente::find($idexpediente)->expedienteable_id;

        $titulo = \DB::table('gt_titulo_tesis AS gt_e')                                        
                            ->select('titulo')
                            ->where('id', $titulo_tesis_id)                        
                            ->first()
                            ->titulo;               

        return $titulo;
    }

    public function updateTitulo(Request $request) 
    {
        $this->validate($request, 
            [
                'titulo' => 'required|min:30|max:250'                
            ]
        );

        $titulo_tesis_id = Expediente::find($request->idexpediente)->expedienteable_id;        
        $titulo = $request->titulo;       

        try {
            DB::table('gt_titulo_tesis')
                ->where('id', $titulo_tesis_id)
                ->update(['titulo' => $titulo]);

            $result = ['successMessage' => 'Título registrado con éxito', 'error' => false];
        } catch (\Exception $e) {            
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteTituloTesisController@updateTitulo, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }

    public function getAsesor($idexpediente) 
    {               
        $asesor = \DB::table('gt_expediente_titulo_tesis AS gt_e')            
                            ->join('gt_usuario AS gt_u', 'gt_u.id', '=', 'gt_e.idasesor_propuesto')        
                            ->join('SIAC_DOC AS ac_doc', 'ac_doc.codper', '=', 'gt_u.codi_usuario')
                            ->join('actdepa AS ac_depa', 'ac_depa.depa', '=', 'ac_doc.depend')
                            ->select('ac_doc.correo', 'ac_depa.ndep as departamento',
                                     \DB::raw('(REPLACE(ac_doc.apn, "/", " ")) AS apn')
                                    )
                            ->where('gt_e.idexpediente', $idexpediente)                        
                            ->first();               

        return json_encode($asesor);
    }

    public function updateAsesor(Request $request) 
    {
        $idexpediente = $request->idexpediente;
        $idasesor = $request->idasesor;

        try {
            DB::table('gt_expediente_titulo_tesis')
                ->where('idexpediente', '=', $idexpediente)
                ->update(['idasesor_propuesto' => $idasesor]);

            $result = ['successMessage' => 'Asesor propuesto registrado con éxito', 'error' => false];
        } catch (\Exception $e) {            
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteTituloTesisController@updateAsesor, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }

    public function deleteAsesor(Request $request) 
    {
        $idexpediente = $request->idexpediente;        

        try {
            DB::table('gt_expediente_titulo_tesis')
                ->where('idexpediente', '=', $idexpediente)
                ->update(['idasesor_propuesto' => NULL]);

            $result = ['successMessage' => 'Asesor propuesto eliminado con éxito', 'error' => false];
        } catch (\Exception $e) {            
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteTituloTesisController@deleteAsesor, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }
}
