<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GraduandoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

            $idusuario = DB::TABLE('gt_usuario')
                ->SELECT('id AS idusuario')
                ->WHERE('codi_usuario', '=', Auth::user()->cui)
                ->FIRST()
                ->idusuario;

            $mytime = Carbon::now('America/Lima');

            DB::table('gt_movimiento')
                ->insert([
                    'idexpediente' => $idexpediente,
                    'idusuario' => $idusuario,
                    'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'idruta' => $idruta,
                ]);

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idgrado_procedimiento' => $idgradproc_destino]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function registrarProyecto(Request $request)
    {
        $titulo = $request->titulo;
        $idexpediente = $request->idexpediente;        
        $idruta = $request->idruta;
        $idgradproc_origen = $request->idgradproc_origen;
        $idgradproc_destino = $request->idgradproc_destino;

        try {
            DB::beginTransaction();

            $idusuario = DB::table('gt_usuario')
                ->select('id AS idusuario')
                ->where('codi_usuario', Auth::user()->cui)
                ->first()
                ->idusuario;

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['titulo' => $titulo]);

            $mytime = Carbon::now('America/Lima');

            $idmovimiento = DB::table('gt_movimiento')
                ->insertGetId([
                    'idexpediente' => $idexpediente,
                    'idusuario' => $idusuario,
                    'fecha' => $mytime->format('Y-m-d H:i:s'),
                    'idruta' => $idruta,
                    'idmov_anterior' => '0'
                ]);

            DB::table('gt_expediente')
                ->where('id', '=', $idexpediente)
                ->update(['idgrado_procedimiento' => $idgradproc_destino]);

            DB::commit();
            $result = ['successMessage' => 'Proyecto de tesis registrado con éxito', 'error' => false];                   
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('GraduandoController@registrarProyecto, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {        
        $graduando = User::join('acdiden', 'gt_graduando.cui', '=', 'acdiden.cui')
                        ->select(                
                            'gt_graduando.cui', 
                            DB::raw('(SUBSTRING(acdiden.dic, 2)) AS dni'),
                            DB::raw('(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 1)) AS apellidos'),
                            DB::raw('(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 2), ",", -1)) AS nombres')
                        )
                        ->where('gt_graduando.id', '=', Auth::id())
                        ->first();        

        return json_encode($graduando);
    }

    public function getContacto()
    {        
        $contacto = User::select('telefono_fijo', 'telefono_movil', 'direccion')
                            ->where('id', Auth::id())
                            ->first();        

        return json_encode($contacto);
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
    public function update(Request $request)
    {        
        $this->validate($request, 
            [
                'telefono_fijo' => 'nullable|digits_between:6, 10',
                'telefono_movil' => 'required|digits_between:9, 15',
                'direccion' => 'required|max:150'
            ]
        );

        try {         
            $user_id = Auth::id();

            DB::table('gt_graduando')
                ->where('id', $user_id)
                ->update(
                    [
                        'telefono_fijo' => $request->telefono_fijo,
                        'telefono_movil' => $request->telefono_movil,
                        'direccion' => $request->direccion
                    ]
                );      

            $result = ['successMessage' => 'Información de contacto actualizado con éxito', 'error' => false]; 
            
        } catch (\Exception $e) {                              
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::warning('GraduandoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
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
