<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

class ExpedienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idusuario = DB::TABLE('GT_USUARIO')
                                ->SELECT('id AS idusuario')
                                ->WHERE('codi_usuario', '=', Auth::user()->cui)
                                ->FIRST()
                                ->idusuario;

        $tramites = DB::TABLE('GT_EXPEDIENTE AS gt_e')
                        ->SELECT('gt_e.id AS idexpediente', 'gt_e.codigo AS codExpediente',
                                 'gt_e.idgrado_procedimiento AS idgrado_procedimiento_actual',
                                 'gt_gm.id AS idgrado_modalidad', 'gt_gt.nombre AS nombre_grado_titulo',
                                 'gt_mo.nombre AS nombre_modalidad', 'actescu.nesc')
                        ->JOIN('GT_USUARIO_EXPEDIENTE AS gt_ue', 'gt_e.id', '=', 'gt_ue.idexpediente')
                        ->JOIN('GT_GRADO_PROCEDIMIENTO AS gt_gp', 'gt_e.idgrado_procedimiento', '=', 'gt_gp.id')
                        ->JOIN('GT_GRADO_MODALIDAD AS gt_gm', 'gt_gm.id', '=', 'gt_gp.idgrado_modalidad')
                        ->JOIN('GT_GRADO_TITULO AS gt_gt', 'gt_gm.idgrado_titulo', '=', 'gt_gt.id')
                        ->JOIN('GT_MODALIDAD_OBTENCION AS gt_mo', 'gt_gm.idmodalidad_obtencion', '=', 'gt_mo.id')
                        ->JOIN('actescu', 'actescu.nues', '=', 'gt_e.nues')
                        ->WHERE('idusuario', '=', $idusuario)
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
                                'fecha_inicio' => $mytime->format('Y-m-d H:i:s'),
                                'estado_expediente' => 'En proceso'
                            ]);

            $digitos = strlen(strval($idexpediente));
            $codExpediente = 'GT-UNSA-';

            for($i = 0; $i < 7 - $digitos; $i++) {
                $codExpediente .= '0';
            }

            $codExpediente .= strval($idexpediente);

            DB::table('GT_EXPEDIENTE')
                ->where('id', '=', $idexpediente)
                ->update(['codigo' => $codExpediente]);

            $idusuario = DB::TABLE('GT_USUARIO')
                                ->SELECT('id AS idusuario')
                                ->WHERE('codi_usuario', '=', Auth::user()->cui)
                                ->FIRST()
                                ->idusuario;

            DB::table('GT_USUARIO_EXPEDIENTE')
                ->insert([
                'idexpediente' => $idexpediente,
                'idusuario' => $idusuario,
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
