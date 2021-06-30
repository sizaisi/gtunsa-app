<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    //
    public function bachiller_solicitud_proyecto()  
    {      
        $pdf = \PDF::loadView('pdf.Bachiller.solicitud_proyecto', 
            []);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("solicitud_proyecto.pdf", array("Attachment" => false));    
    }
    public function bachiller_declaracion_jurada()  
    {      
        $pdf = \PDF::loadView('pdf.Bachiller.declaracion_jurada', 
            []);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("declaracion_jurada.pdf", array("Attachment" => false));    
    }
    public function bachiller_solicitud_automatico()  
    {      
        $pdf = \PDF::loadView('pdf.Bachiller.solicitud_automatico', 
            []);
        $pdf->setPaper('A4', 'portrait');        
        
        return $pdf->stream("solicitud_automatico.pdf", array("Attachment" => false));    
    }
    public function titulacion_solicitud_proyecto()  
    {      
        $pdf = \PDF::loadView('pdf.Titulo_profecional.solicitud_proyecto', 
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
