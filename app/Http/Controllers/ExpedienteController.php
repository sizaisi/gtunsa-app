<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpedienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tramites = DB::table('gt_expediente AS gt_e')
            ->select(
                'gt_e.id AS idexpediente',
                'gt_e.codigo AS codExpediente',
                'gt_e.idgrado_procedimiento AS idgrado_procedimiento_actual',
                'gt_gm.id AS idgrado_modalidad',
                'gt_gt.nombre AS nombre_grado_titulo',
                'gt_mo.nombre AS nombre_modalidad',
                'actescu.nesc'
            )
            ->join('gt_graduando_expediente AS gt_ge', 'gt_e.id', '=', 'gt_ge.idexpediente')
            ->join('gt_grado_procedimiento AS gt_gp', 'gt_e.idgrado_procedimiento', '=', 'gt_gp.id')
            ->join('gt_grado_modalidad AS gt_gm', 'gt_gm.id', '=', 'gt_gp.idgrado_modalidad')
            ->join('gt_grado_titulo AS gt_gt', 'gt_gm.idgrado_titulo', '=', 'gt_gt.id')
            ->join('gt_modalidad_obtencion AS gt_mo', 'gt_gm.idmodalidad_obtencion', '=', 'gt_mo.id')
            ->join('actescu', 'actescu.nues', '=', 'gt_e.nues')
            ->where('gt_ge.idgraduando', '=', Auth::id())
            ->orderby('gt_e.id', 'desc')
            ->get();

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

            $idgrado_procedimiento = DB::table('gt_grado_procedimiento AS gt_gp')
                ->join('gt_ruta AS gt_r', 'gt_gp.id', '=', 'gt_r.idgradproc_destino')
                ->select('gt_r.idgradproc_destino AS idgrado_procedimiento')
                ->where('gt_gp.idgrado_modalidad', '=', $idgrado_modalidad)
                ->where('gt_r.etiqueta', '=', 'iniciar')
                ->first()
                ->idgrado_procedimiento;

            $mytime = Carbon::now('America/Lima');

            $idexpediente = DB::table('gt_expediente')
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
