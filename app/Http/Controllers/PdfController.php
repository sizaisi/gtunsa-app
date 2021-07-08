<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class PdfController extends Controller
{
    //
    public function bachiller_solicitud_proyecto()  
    {      
        $graduando = User::find(\Auth::id())->administrado()->with('alumno:cui,dic,apn')->first();

        //return $graduando;
        $qr = base64_encode(\QrCode::format('svg')->size(55)->errorCorrection('H')->generate('SB'.substr($graduando->alumno->dic, 1)));
        $pdf = \PDF::loadView('pdf.Bachiller.solicitud_proyecto', 
            [
                'graduando' => $graduando,
                'qr' => $qr
            ]);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("solicitud_proyecto.pdf", array("Attachment" => false));    
    }
    public function bachiller_declaracion_jurada()  
    {      
        $graduando = User::find(\Auth::id())->administrado()->with('alumno:cui,dic,apn')->first();

        $pdf = \PDF::loadView('pdf.Bachiller.declaracion_jurada', 
            [
                'graduando' => $graduando
            ]);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("declaracion_jurada.pdf", array("Attachment" => false));    
    }
    public function bachiller_solicitud_automatico()  
    {      
        $graduando = User::find(\Auth::id())->administrado()->with('alumno:cui,dic,apn')->first();

        $pdf = \PDF::loadView('pdf.Bachiller.solicitud_automatico', 
            [
                'graduando' => $graduando
            ]);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("solicitud_automatico.pdf", array("Attachment" => false));    
    }
    public function titulacion_solicitud_proyecto()  
    {      
        $pdf = \PDF::loadView('pdf.Titulo_profecional.solicitud_modalidad', 
            []);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("solicitud_automatico.pdf", array("Attachment" => false));    
    }
    public function titulacion_solicitud_modalidad()  
    {      
        $pdf = \PDF::loadView('pdf.Titulo_profecional.solicitud_modalidad', 
            []);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("solicitud_automatico.pdf", array("Attachment" => false));    
    }
}
