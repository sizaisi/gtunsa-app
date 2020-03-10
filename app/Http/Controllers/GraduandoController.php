<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;

class GraduandoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $graduando = User::JOIN('acdiden', 'GT_GRADUANDO.cui', '=', 'acdiden.cui')
                           ->SELECT('GT_GRADUANDO.id', 'GT_GRADUANDO.cui', DB::raw('(SUBSTRING_INDEX(REPLACE(apn, "/", " "), ",", 1)) AS apellidos'),
                                    DB::raw('(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(apn, "/", " "), ",", 2), ",", -1)) AS nombres'),
                                    'email', 'acdiden.dic AS dni', 'telefono_fijo', 'telefono_movil','direccion')
                           ->WHERE('GT_GRADUANDO.cui', '=', Auth::user()->cui)
                           ->FIRST();

        return $graduando;
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
        //
    }

    public function mover(Request $request)
    {
      $idruta = $request->idruta;
      $idexpediente = $request->idexpediente;
      $idgradproc_origen = $request->idgradproc_origen;
      $idgradproc_destino = $request->idgradproc_destino;

      try {

          DB::beginTransaction();

          $idusuario = DB::TABLE('GT_USUARIO')
                              ->SELECT('id AS idusuario')
                              ->WHERE('codi_usuario', '=', Auth::user()->cui)
                              ->FIRST()
                              ->idusuario;

          $mytime= Carbon::now('America/Lima');

          DB::table('GT_MOVIMIENTO')
              ->insert([
              'idexpediente' => $idexpediente,
              'idusuario' => $idusuario,
              'fecha' => $mytime->format('Y-m-d H:i:s'),
              'idruta' => $idruta,
          ]);

          DB::table('GT_EXPEDIENTE')
              ->where('id', '=', $idexpediente)
              ->update(['idgrado_procedimiento' => $idgradproc_destino]);

          DB::commit();
      } catch (Exception $e){
          DB::rollBack();
      }
    }

    public function registrarProyecto(Request $request)
    {
      $titulo = $request->titulo;
      $idexpediente = $request->idexpediente;
      $data = $request->data;
      $extension = $request->extension;
      $idruta = $request->idruta;
      $idgradproc_origen = $request->idgradproc_origen;
      $idgradproc_destino = $request->idgradproc_destino;

      try {

          DB::beginTransaction();

          $idusuario = DB::TABLE('GT_USUARIO')
                              ->SELECT('id AS idusuario')
                              ->WHERE('codi_usuario', '=', Auth::user()->cui)
                              ->FIRST()
                              ->idusuario;

          DB::table('GT_EXPEDIENTE')
              ->where('id', '=', $idexpediente)
              ->update(['titulo' => $titulo]);

          $mytime= Carbon::now('America/Lima');

          DB::table('GT_MOVIMIENTO')
              ->insert([
              'idexpediente' => $idexpediente,
              'idusuario' => $idusuario,
              'fecha' => $mytime->format('Y-m-d H:i:s'),
              'idruta' => $idruta,
          ]);

          DB::table('GT_EXPEDIENTE')
              ->where('id', '=', $idexpediente)
              ->update(['idgrado_procedimiento' => $idgradproc_destino]);

          DB::table('GT_ARCHIVO')
              ->insert([
                'nombre' => 'Plan de tesis',
                'data' => $data,
                'extension' => $extension,
                'idgrado_proc' => $idgradproc_origen,
                'idusuario' => $idusuario,
                'idexpediente' => $idexpediente,
          ]);

          //$archivo_pdf = $request->archivo_pdf;
          /*if (move_uploaded_file($_FILES["file"]["tmp_name"], "pdfs/".$_FILES['file']['name'])) {
            return "done";
          }*/

          //return $_FILES["file"]["tmp_name"];
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
        if (!$request->ajax()) return redirect('/');

        $this->validate($request, [
            'telefono_fijo' => 'nullable|digits_between:6, 10',
            'telefono_movil' => 'required|digits_between:9, 15',
            'direccion' => 'required|max:150'
        ]);

        $graduando = User::findOrFail($id);

        $graduando->telefono_fijo = $request->telefono_fijo;
        $graduando->telefono_movil = $request->telefono_movil;
        $graduando->direccion = $request->direccion;

        $graduando->save();
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
