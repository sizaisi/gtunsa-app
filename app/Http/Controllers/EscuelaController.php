<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class EscuelaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $escuelas = DB::TABLE('acdidal')                        
                        ->JOIN('actescu', 'acdidal.nues', '=', 'actescu.nues')
                        ->SELECT('acdidal.nues', 'nesc', 'nive', 'acdidal.espe')                        
                        ->WHERE('cui', '=', Auth::user()->cui)
                        ->WHERE('cond', '=', 'E')
                        ->ORDERBY('nesc', 'asc')
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
