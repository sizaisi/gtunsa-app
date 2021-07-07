<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Declaracion jurada</title>
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
            .table-info{border: none; border-collapse: collapse;  width: 100%;}

            .table-info td{line-height:1.8;
                border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); 
                vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word;}
            
            .firma{line-height:1.4;text-align: center;margin-top:0pt;margin-bottom:0pt;}
        </style>
    </head>
    <body>
        <div class="logo" > 
	        <img width="150" src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/unsa.png" alt="Logo UNSA">
        </div>
        <br>
        <div class="text-center">
            <h3>DECLARACI&Oacute;N JURADA DE NO REGISTRAR ANTECEDENTES PENALES Y/O JUDICIALES</h3>
        </div>
        <br/>
        <div >
        <table class="table-info">
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

        <p class="texto"> Estando al amparo de lo dispuesto por los art&iacute;culos 49&ordm; y 51&ordm; de D.S. 004-2019-JUS, nuevo TUO de la &nbsp;Ley N&ordm; 27444, 
            Ley del Procedimiento Administrativo General y en pleno ejercicio de mis derechos ciudadanos:&nbsp;<b >DECLARO BAJO JURAMENTO, NO REGISTRAR ANTECEDENTES PENALES Y/O JUDICIALES,&nbsp;</b>
            a efecto de realizar el tr&aacute;mite para la obtenci&oacute;n de: Grado Acad&eacute;mico de Bachiller: &nbsp;&nbsp;<span >{modalidad de obtencion}</p>
        <p class="texto">Asimismo, manifiesto que lo declarado corresponde a la verdad de los hechos, teniendo conocimiento que toda declaraci&oacute;n falsa se encuentra sujeta a los alcances de lo establecido en el art&iacute;culo 411&deg; del C&oacute;digo Penal, que establece pena privativa de libertad de hasta cuatro (4) a&ntilde;os, para los que hacen, en un procedimiento administrativo, una falsa declaraci&oacute;n en relaci&oacute;n a hechos o circunstancias que le corresponde probar, violando la presunci&oacute;n de veracidad establecida por ley. Es en ese sentido que la presente declaraci&oacute;n jurada queda sujeta a fiscalizaci&oacute;n posterior por parte de la Universidad Nacional de San Agust&iacute;n de Arequipa.</p>

        <p >Arequipa,&nbsp;{fecha larga : 05 de octubre del 2021}</p>
        
        <table style=" width: 100%; margin-left: auto; margin-right: auto;">
            <tbody>
                <tr >
                    <td style=" width: 50%;" >
                    
                    <p class="firma"><span >_______________________</span></p>
                    <p class="firma" ><span >FIRMA&nbsp;</span></p>
                    <p class="firma" ><span >DNI:{dni del graduando}</p>
                                
                    </td>
                    
                    <td style=" width: 50%; ">
                        <div STYLE="width: 0.98in; height: 1.29in; border: 1px solid #000000;">
                        </div>
                        <p style="">huella dactilar</p>
                        

                    </td>
                </tr>
            </tbody>
        </table>
      
        
    </body>
</html>