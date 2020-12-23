<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EscuelaController extends Controller
{    
    public function index()
    {
        $escuelas = DB::table('acdidal')                        
                        ->join('actescu', 'acdidal.nues', '=', 'actescu.nues')
                        ->select('acdidal.nues', 'nesc', 'nive', 'acdidal.espe')                        
                        ->where('cui', '=', Auth::user()->cui)
                        ->where('cond', '=', 'E')
                        ->orderby('nesc', 'asc')
                        ->get();

        $escuelas2= array();

        foreach ($escuelas as $indice => $escuela) {
            $escuelas2[$indice]['text'] = $escuela->nesc;
            $escuelas2[$indice]['value'] = array('nues'=>$escuela->nues, 
                                                 'espe'=>$escuela->espe, 
                                                 'nive'=>$escuela->nive);            
        }

        return json_encode($escuelas2);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }
    
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        //
    }
}
