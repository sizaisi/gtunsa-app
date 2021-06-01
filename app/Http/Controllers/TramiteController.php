<?php

namespace App\Http\Controllers;

use App\Tramite;
use Illuminate\Http\Request;

class TramiteController extends Controller
{
    public function index(Request $request)
    {       
        $codigo = $request->codigo;

        $tramites = Tramite::select('id', 'nombre', 'componente')                            
                            //->where('codigo', $codigo)                            
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
