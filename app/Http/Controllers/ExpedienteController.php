<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpedienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idgraduando = DB::table('GT_GRADUANDO')
                                ->select('id AS idgraduando')
                                ->where('cui', '=', Auth::user()->cui)
                                ->first()
                                ->idgraduando;

        $tramites = DB::TABLE('GT_EXPEDIENTE AS gt_e')
                        ->SELECT('gt_e.id AS idexpediente', 'gt_e.codigo AS codExpediente',
                                 'gt_e.idgrado_procedimiento AS idgrado_procedimiento_actual',
                                 'gt_gm.id AS idgrado_modalidad', 'gt_gt.nombre AS nombre_grado_titulo',
                                 'gt_mo.nombre AS nombre_modalidad', 'actescu.nesc')
                        ->JOIN('GT_GRADUANDO_EXPEDIENTE AS gt_ge', 'gt_e.id', '=', 'gt_ge.idexpediente')
                        ->JOIN('GT_GRADO_PROCEDIMIENTO AS gt_gp', 'gt_e.idgrado_procedimiento', '=', 'gt_gp.id')
                        ->JOIN('GT_GRADO_MODALIDAD AS gt_gm', 'gt_gm.id', '=', 'gt_gp.idgrado_modalidad')
                        ->JOIN('GT_GRADO_TITULO AS gt_gt', 'gt_gm.idgrado_titulo', '=', 'gt_gt.id')
                        ->JOIN('GT_MODALIDAD_OBTENCION AS gt_mo', 'gt_gm.idmodalidad_obtencion', '=', 'gt_mo.id')
                        ->JOIN('actescu', 'actescu.nues', '=', 'gt_e.nues')
                        ->WHERE('gt_ge.idgraduando', '=', $idgraduando)
                        ->ORDERBY('gt_e.id', 'desc')
                        ->GET();

        return $tramites;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idgrado_modalidad = $request->idgrado_modalidad;
        $nues = $request->nues;
        $espe = $request->espe;

        try {
            DB::beginTransaction();

            $idgrado_procedimiento = DB::TABLE('GT_GRADO_PROCEDIMIENTO AS gt_gp')
                                        ->JOIN('GT_RUTA AS gt_r', 'gt_gp.id', '=', 'gt_r.idgradproc_destino')
                                        ->SELECT('gt_r.idgradproc_destino AS idgrado_procedimiento')
                                        ->WHERE('gt_gp.idgrado_modalidad', '=', $idgrado_modalidad)
                                        ->WHERE('gt_r.etiqueta', '=', 'iniciar')
                                        ->FIRST()
                                        ->idgrado_procedimiento;

            $mytime= Carbon::now('America/Lima');

            $idexpediente = DB::table('GT_EXPEDIENTE')
                                ->insertGetId([
                                'idgrado_procedimiento' => $idgrado_procedimiento,
                                'nues' => $nues,
                                'espe' => $espe,
                                'fecha' => $mytime->format('Y-m-d H:i:s'),
                                'estado' => 'iniciado',
                                'fing' => NULL
                            ]);

            $digitos = strlen(strval($idexpediente));
            $codExpediente = 'E-';

            for($i = 0; $i < 7 - $digitos; $i++) {
                $codExpediente .= '0';
            }

            $codExpediente .= strval($idexpediente);

            DB::table('GT_EXPEDIENTE')
                ->where('id', '=', $idexpediente)
                ->update(['codigo' => $codExpediente]);

            $idgraduando = DB::TABLE('GT_GRADUANDO')
                                ->SELECT('id AS idgraduando')
                                ->WHERE('cui', '=', Auth::user()->cui)
                                ->FIRST()
                                ->idgraduando;

            DB::table('GT_GRADUANDO_EXPEDIENTE')
                ->insert([                
                'idgraduando' => $idgraduando,
                'idexpediente' => $idexpediente,
            ]);

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
