<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ArchivoController extends Controller
{
    public function get(Request $request) 
    {
        $idexpediente = $request->idexpediente;
        $idprocedimiento = $request->idprocedimiento;

        $archivos = DB::table('gt_recurso AS GT_R')
            ->join('gt_archivo AS GT_A', 'GT_R.recurso_dinamico_id', '=', 'GT_A.id')
            ->select('GT_R.id AS idrecurso', 'GT_A.nombre as nombre_asignado', 'GT_A.mime')
            ->where('GT_R.expediente_id', $idexpediente)
            ->where('GT_R.procedimiento_id', $idprocedimiento)
            ->where('GT_R.user_id', Auth::id())            
            ->get();     
        
        return $archivos;
    }

    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    
    public function store(Request $request)
    {         
        try {            
            DB::beginTransaction(); 

            $idusuario = DB::table('gt_usuario')
                            ->select('id AS idusuario')
                            ->where('codi_usuario', Auth::user()->cui)
                            ->first()
                            ->idusuario;                               
                               
            $idrecurso = DB::table('gt_recurso')
                        ->insertGetId([
                            'idexpediente' => $request->idexpediente,
                            'idprocedimiento' => $request->idprocedimiento,
                            'idusuario' => $idusuario,
                            'idmovimiento' => null,
                            'idruta' => $request->idruta
                        ]);      
            
            DB::table('gt_archivo')
                ->insert([
                    'idrecurso' => $idrecurso,
                    'nombre_asignado' => $request->nombre_asignado,
                    'nombre_archivo' => $request->nombre_archivo,
                    'mime' => $request->type,
                    'data' => $request->file,
                ]);
            
            DB::commit();
            $result = ['successMessage' => 'Archivo registrado con éxito', 'error' => false];            
        } catch (\Exception $e) {
            DB::rollBack();            
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::warning('ArchivoController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());                       
        }

        return $result;       
    }
    
    public function destroy(Request $request)
    {
        $idrecurso = $request->idrecurso;       

        try {           
            DB::beginTransaction();             

            DB::table('gt_archivo')->where('idrecurso', $idrecurso)->delete();
            DB::table('gt_recurso')->where('id', $idrecurso)->delete();

            DB::commit();
            $result = ['successMessage' => 'Archivo eliminado con éxito', 'error' => false];                   
        } catch (\Exception $e) {
            DB::rollBack();                        
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ArchivoController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;       
    }

    public function show($idrecurso)
    {
        $archivo = DB::table('gt_archivo')
                        ->select('nombre_asignado','mime', 'data')
                        ->where('idrecurso', $idrecurso)
                        ->first();       
        
        $nombre_asignado = $archivo->nombre_asignado;
        $mime = $archivo->mime;
        $data = $archivo->data;       
        
        switch ($mime) {
            case 'application/pdf':
                $extension = '.pdf';
                break;
            case 'image/png':
                $extension = '.png';
                break;
            case 'image/jpeg':
                $extension = '.jpg';
                break;
        }	

        $data = base64_decode($data);

        return response($data, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $nombre_asignado . $extension . '"',
        ]);
    }
}
