<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Solicitud: Bachiller Automatico</title>
        <style>

            body {

                margin-left:35pt;
                margin-right:35pt;
                font-family: "Times New Roman";
                font-size:11pt;
                font-weight: normal;
                line-height: 1.5;
                color: #151b1e;           

            }

            .izquierda {
                float:left;
            }
            .derecha {
                float:right;
            }
            .text-right{text-align:right!important}
            .text-center{text-align:center!important}
            
            .texto{
                line-height:1.6;
                text-align: justify;}
            .table-info{border: none; border-collapse: collapse;  width: 100%;margin-top:0pt;}

            .table-info td{line-height:1.6;
                border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); 
                vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word;}

            .table-check{border-collapse:collapse;border:none;}
            .table-check td{border:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt;margin-bottom:0in;text-align:justify;line-height:
                  normal}
            
            .firma{line-height:1.4;text-align: center;margin-top:0pt;margin-bottom:0pt;}
        </style>
    </head>
    <body>
        <div class="logo" > 
	        <img width="150" src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/unsa.png" alt="Logo UNSA">
        </div>
        <br>
        <div style="text-align:right;">
            <h3>SOLICITO: INSCRIPCIÓN PROYECTO DE TESIS<br>
                PARA OPTAR EL TÍTULO
                PROFESIONAL </h3>
        </div>
        <div >
        <p>Señor Decano de la Facultad de: <b> INGENIERIA DE PRODUCCION Y SERVICIOS</b></p>
        <table class="table-info" >
            <tbody>
                <tr >
                    <td style="width: 25%;">
                        Apellidos y Nombres
                    </td>
                    <td style="width: 75%;">{{ $graduando->apellidos }}, {{ $graduando->nombres }}</td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        Nacionalidad
                    </td>
                    <td style=" width: 75%;"><br></td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        DNI/C.E./Pasaporte
                    </td>
                    <td style=" width: 75%;">{{ substr($graduando->alumno->dic, 1) }}</td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        Direcci&oacute;n
                    </td>
                    <td style=" width: 75%;">{{ $graduando->direccion }}</td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        Distrito y Ciudad
                    </td>
                    <td style=" width: 75%;"><br></td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        Tel&eacute;fono/Celular
                    </td>
                    <td style=" width: 75%;">{{ $graduando->telefono }}</td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        Correo Electr&oacute;nico
                    </td>
                    <td style=" width: 75%;">{{ $graduando->email_personal }}</td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        CUI
                    </td>
                    <td style=" width: 75%;">{{ $graduando->cui }}</td>
                </tr>
                <tr style="height:17.473388671875pt;">
                    <td style=" width: 25%;">
                        Egresado/Bachiller
                    </td>
                    <td style=" width: 75%;"><br></td>
                </tr>
                <tr >
                    <td style=" width: 25%;">
                        Programa profesional
                    </td>
                    <td style=" width: 75%;"><br></td>
                </tr>
            </tbody>
        </table>
        </div>
        <p class="texto"> Ante usted me presento con el debido respeto y expongo:</p>
        <p class="texto"> En virtud al Reglamento
         General de Grado Académico de Bachiller y Título Profesional de la Universidad
         Nacional de San Agustín de Arequipa, solicito a usted considerar el Proyecto de
         Tesis titulado:</p>
        
         <p class="texto">____________________________.</p>

        <p class="texto">Para su inscripción, revisión (Unidad de
         Investigación)  y designar como asesor a don(ña):</p>
         <p class="texto">____________________________.</p>
        <p class="texto">Por lo expuesto, pido a
         usted acceder a mi solicitud disponiendo el trámite correspondiente.</p>

        <p >Arequipa,&nbsp;{fecha larga : 05 de octubre del 2021}</p>
        
        <div>
            <p><br></p>
            <p><br></p>
            <p><br></p>
            <p class="firma"><span >_______________________</span></p>
            <p class="firma" ><span >FIRMA&nbsp;</span></p>
            <p class="firma" ><span >DNI:{dni del graduando}</p>
        </div>
        
    </body>
</html>