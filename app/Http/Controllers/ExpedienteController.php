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
            ->select(
                'gt_e.id AS idexpediente',
                'gt_e.codigo AS codExpediente',
                'gt_e.idgrado_procedimiento AS idgrado_procedimiento_actual',
                'gt_gm.id AS idgrado_modalidad',
                'gt_g.nombre AS nombre_grado_titulo',
                'gt_m.nombre AS nombre_modalidad',
                'actescu.nesc'
            )
            ->join('gt_graduando_expediente AS gt_ge', 'gt_e.id', '=', 'gt_ge.idexpediente')
            ->join('gt_procedimientos AS gt_p', 'gt_e.idgrado_procedimiento', '=', 'gt_p.id')
            ->join('gt_grado_modalidades AS gt_gm', 'gt_gm.id', '=', 'gt_p.idgradomodalidad')
            ->join('gt_grados AS gt_g', 'gt_gm.idgrado', '=', 'gt_g.id')
            ->join('gt_modalidades AS gt_m', 'gt_gm.idmodalidad', '=', 'gt_m.id')
            ->join('actescu', 'actescu.nues', '=', 'gt_e.nues')
            ->where('gt_ge.idgraduando', '=', Auth::id())
            ->orderby('gt_e.id', 'desc')
            ->get();

        return $tramites;
    }    
    
    public function store(Request $request)
    {
        $idgrado_modalidad = $request->idgrado_modalidad;
        $nues = $request->nues;
        $espe = $request->espe;

        //dd($request->all());

        try {
            DB::beginTransaction();

            $idprocedimiento = DB::table('gt_procedimientos AS gt_p')
                ->join('gt_rutas AS gt_r', 'gt_p.id', '=', 'gt_r.idproc_destino')
                ->select('gt_r.idproc_destino AS idprocedimiento')
                ->where('gt_p.idgradomodalidad', '=', $idgrado_modalidad)
                ->where('gt_r.etiqueta', '=', 'iniciar')
                ->first()
                ->idprocedimiento;

            $mytime = Carbon::now('America/Lima');

            $idexpediente = DB::table('gt_expediente')
                ->insertGetId([
                    'idgrado_procedimiento' => $idprocedimiento,
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
}
