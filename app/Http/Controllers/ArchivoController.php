<?php

namespace App\Http\Controllers;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Libern\QRCodeReader\QRCodeReader;

class ArchivoController extends Controller
{
    public function get(Request $request)
    {
        $idexpediente = $request->idexpediente;
        $idprocedimiento = $request->idprocedimiento;

        $archivos = DB::table('gt_recurso AS GT_R')
            ->join('gt_archivo AS GT_A', 'GT_R.recurso_dinamico_id', '=', 'GT_A.id')
            ->select('GT_R.id AS idrecurso', 'GT_A.id AS archivo_id', 'GT_A.nombre_asignado', 'GT_A.nombre_archivo', 'GT_A.mime')
            ->where('GT_R.expediente_id', $idexpediente)
            ->where('GT_R.procedimiento_id', $idprocedimiento)
            ->where('GT_R.user_id', Auth::id())
            ->get();

        return $archivos;
    }

    function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    public function store(Request $request)
    {   
        $pdf = $request->file;
        $image = new Imagick;

        try {
            DB::beginTransaction();

            $archivo_id = DB::table('gt_archivo')
                ->insertGetId([
                    'nombre_asignado' => $request->nombre_asignado,
                    'nombre_archivo' => $request->nombre_archivo,
                    'mime' => $request->type,
                    'file' => $request->file,
                ]);

            DB::table('gt_recurso')
                ->insertGetId([
                    'tipo_recurso' => 'Archivo',
                    'recurso_dinamico_id' => $archivo_id,
                    'user_id' => Auth::id(),
                    'procedimiento_id' => $request->idprocedimiento,
                    'expediente_id' => $request->idexpediente,
                ]);
            DB::commit();

           

            $result = ['successMessage' => 'Archivo registrado con éxito', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::warning('ArchivoController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return $result;
    }

    public function destroy(Request $request)
    {
        $archivo_id = $request->archivo_id;

        try {
            DB::beginTransaction();

            DB::table('gt_archivo')->where('id', $archivo_id)->delete();
            DB::table('gt_recurso')->where('recurso_dinamico_id', $archivo_id)->delete();

            DB::commit();
            $result = ['successMessage' => 'Archivo eliminado con éxito', 'error' => false];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::error('ArchivoController@destroy, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return $result;
    }

    public function show($archivo_id)
    {
        $archivo = DB::table('gt_archivo')
            ->select('nombre_asignado', 'mime', 'file')
            ->where('id', $archivo_id)
            ->first();

        $nombre_asignado = $archivo->nombre_asignado;
        $mime = $archivo->mime;
        $file = $archivo->file;

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

        $file = base64_decode($file);

        return response($file, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $nombre_asignado . $extension . '"',
        ]);
    }
}
