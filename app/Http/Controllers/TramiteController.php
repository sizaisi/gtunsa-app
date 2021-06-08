<?php

namespace App\Http\Controllers;

use App\Tramite;
use Illuminate\Http\Request;

class TramiteController extends Controller
{
    public function index(Request $request)
    {       
        $prefijo_escuela = $request->prefijo_escuela;

        $tramites = Tramite::select('id', 'nombre', 'componente')                            
                            ->where('prefijo_escuela', $prefijo_escuela)                            
                            ->orderby('nombre', 'asc')
                            ->get();

        $_tramites = array();

        foreach ($tramites as $indice => $tramite) {
            $_tramites[$indice]['text'] = $tramite->nombre;
            $_tramites[$indice]['value'] = array('id'=>$tramite->id, 'componente'=>$tramite->componente);            
        }

        return json_encode($_tramites);
    }
}
