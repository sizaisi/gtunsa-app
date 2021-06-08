<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TituloTesisController extends Controller
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
                
            DB::table('gt_expediente_titulo_tesis')
                ->insert([                    
                    'idexpediente' => $idexpediente,
                ]);        

            DB::commit();
            $result = ['successMessage' => 'Trámite registrado con éxito', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ExpedienteTituloTesisController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }   

    public function getTitulo($idexpediente) 
    {               
        $titulo = \DB::table('gt_expediente_titulo_tesis AS gt_e')                                        
                            ->select('titulo')
                            ->where('gt_e.idexpediente', $idexpediente)                        
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

        $idexpediente = $request->idexpediente;
        $titulo = $request->titulo;       

        try {
            DB::table('gt_expediente_titulo_tesis')
                ->where('idexpediente', '=', $idexpediente)
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
