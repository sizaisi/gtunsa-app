<?php

namespace App\Http\Controllers;

use App\User;
use App\Tramite;
use App\Expediente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpedienteController extends Controller
{    
    public function index()
    {        
        $expedientes = User::find(Auth::id())->administrado->expedientes()->with('tramite')->with('escuela')->get();         
        
        return $expedientes;    
    }    
}
