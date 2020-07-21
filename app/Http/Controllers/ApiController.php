<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_expediente($cui)
    {
        $expediente = DB::table('gt_expediente AS GT_E')
                        ->select(
                            'GT_E.id AS idexpediente',
                            DB::raw('(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(AC_I.apn, "/", " "), ",", 2), ", ", -1)) AS nombres'),
                            DB::raw('(SUBSTRING_INDEX(REPLACE(AC_I.apn, "/", " "), ",", 1)) AS apellidos'),
                            DB::raw('(SUBSTRING(AC_I.dic, "1", "1")) AS tipo_documento'),
                            DB::raw('(SUBSTRING(AC_I.dic, "2")) AS nro_documento'),
                            'AC_I.sex AS sexo',
                            DB::raw('"Peruana" AS nacionalidad'),
                            'GT_G.telefono_movil',
                            'GT_G.email',
                            'AC_E.nesc AS programa_academico',
                            'AC_F.nfac AS facultad',
                            DB::raw('"INGENIERO EN SISTEMAS" AS grado_titulo_academico'),                            
                            'GT_MO.nombre AS modalidad_obtencion',
                            'GT_E.titulo AS titulo_trabajo_investigacion',
                            //'GT_A.data as tesis'
                        )
                        ->join('gt_usuario_expediente AS GT_UE', 'GT_UE.idexpediente', '=', 'GT_E.id')
                        ->join('gt_archivo AS GT_A', 'GT_A.idexpediente', '=', 'GT_E.id')
                        ->join('gt_usuario AS GT_U', 'GT_U.id', '=', 'GT_UE.idusuario')
                        ->join('gt_graduando AS GT_G', 'GT_G.cui', '=', 'GT_U.codi_usuario')
                        ->join('acdiden AS AC_I', 'AC_I.cui', '=', 'GT_G.cui')
                        ->join('actescu AS AC_E', 'AC_E.nues', '=', 'GT_E.nues')
                        ->join('actfacu AS AC_F', 'AC_F.facu', '=', 'AC_E.facu')
                        ->join('gt_grado_modalidad AS GT_GM', 'GT_GM.id', '=', 'GT_E.idgrado_modalidad')
                        ->join('gt_modalidad_obtencion AS GT_MO', 'GT_MO.id', '=', 'GT_GM.idmodalidad_obtencion')
                        ->where('GT_G.cui', '=', $cui)
                        ->first();   

        $asesores = DB::select(
                        DB::raw("SELECT 
                                 (SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(AC_D.apn, '/', ' '), ',', 2), ', ', -1)) AS nombres,
                                 (SUBSTRING_INDEX(REPLACE(AC_D.apn, '/', ' '), ',', 1)) AS apellidos,
                                 AC_D.dic AS dni
                                 FROM SIAC_DOC AC_D                                                                
                                 INNER JOIN gt_usuario AS GT_U ON GT_U.codi_usuario = AC_D.codper
                                 INNER JOIN gt_usuario_expediente AS GT_UE ON GT_UE.idusuario = GT_U.id
                                 WHERE GT_UE.idexpediente = ( SELECT GT_UE.idexpediente
                                    FROM gt_graduando AS GT_G
                                    INNER JOIN gt_usuario AS GT_U ON GT_U.codi_usuario = GT_G.cui
                                    INNER JOIN gt_usuario_expediente AS GT_UE ON GT_UE.idusuario = GT_U.id
                                    WHERE GT_G.cui = $cui)
                                 AND GT_UE.tipo = 'asesor'"
                        )
                    );
        
        $jurados = DB::select(
                        DB::raw("SELECT 
                                    (SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(AC_D.apn, '/', ' '), ',', 2), ', ', -1)) AS nombres,
                                    (SUBSTRING_INDEX(REPLACE(AC_D.apn, '/', ' '), ',', 1)) AS apellidos,
                                    GT_UE.tipo AS cargo
                                    FROM SIAC_DOC AC_D                                                                
                                    INNER JOIN gt_usuario AS GT_U ON GT_U.codi_usuario = AC_D.codper
                                    INNER JOIN gt_usuario_expediente AS GT_UE ON GT_UE.idusuario = GT_U.id
                                    WHERE GT_UE.idexpediente = ( SELECT GT_UE.idexpediente
                                    FROM gt_graduando AS GT_G
                                    INNER JOIN gt_usuario AS GT_U ON GT_U.codi_usuario = GT_G.cui
                                    INNER JOIN gt_usuario_expediente AS GT_UE ON GT_UE.idusuario = GT_U.id
                                    WHERE GT_G.cui = $cui)
                                    AND GT_UE.tipo IN ('presidente', 'secretario', 'vocal', 'suplente')
                                    ORDER BY GT_UE.tipo"
                        )
        );


        $expediente->asesores = $asesores;        
        $expediente->jurados = $jurados;        

        return json_encode($expediente);
    }

    public function show_file($cui) //url
    {
        $expediente = DB::table('gt_expediente AS GT_E')
                        ->select(
                            'GT_G.cui',
                            'GT_E.titulo AS titulo_trabajo_investigacion',
                            'GT_A.data as file'
                        )
                        ->join('gt_usuario_expediente AS GT_UE', 'GT_UE.idexpediente', '=', 'GT_E.id')
                        ->join('gt_archivo AS GT_A', 'GT_A.idexpediente', '=', 'GT_E.id')
                        ->join('gt_usuario AS GT_U', 'GT_U.id', '=', 'GT_UE.idusuario')
                        ->join('gt_graduando AS GT_G', 'GT_G.cui', '=', 'GT_U.codi_usuario')                                                
                        ->where('GT_G.cui', '=', $cui)
                        ->first();     
                        
        $expediente->file = gzencode($expediente->file, 9);

        dd($expediente);

        return json_encode($expediente);
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
        $url_repositorio = $request->input('url');
        
        $result = array('error' => false);

        $query = DB::table('gt_expediente')
                    ->where('id', '=', $id)
                    ->update([
                        'url_repo' => $url_repositorio,
                        'updated_at' => Carbon::now()
                        ]);       

        if ($query) { 
            $result['message'] = "URL actualizada correctamente.";                
        }   
        else {
            $result['error'] = true;
            $result['message'] = "No se pudo actualizar la URL.";            
        }     

        return $result;
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
