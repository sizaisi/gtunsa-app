<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Dar conformidad de asesoramiento de proyecto</title>
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
            .table-check td{border-collapse:collapse;border:none;}
            
            .firma{line-height:1.4;text-align: center;margin-top:0pt;margin-bottom:0pt;}
        </style>
    </head>
    <body>
        <div class="logo" > 
	        <img width="150" src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/unsa.png" alt="Logo UNSA">
        </div>
        <br>
        <div style="text-align:right;">
            <h3>SOLICITO: OPTAR EL
         GRADO  ACADÉMICO DE <br> BACHILLER POR LA MODALIDAD DE: BACHILLER AUTOMATICO</h3>
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
        <p class="texto"> Solicito optar Grado Académico de Bachiller en _______________________________________ en la
         Facultad de _______________________________________________________________ de la
         Universidad Nacional de San Agustín de Arequipa, por la modalidad de
         ____________________________________________; a tal efecto, adjunto los
         requisitos establecidos en el Reglamento General de Grado Académico de
         Bachiller y Título Profesional (Versión 3), marcar con una (X) según
         corresponda a la modalidad:</p>
        <br>
        <table class="table-check" border=1 cellspacing=0 cellpadding=0 width=56
         >
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border:solid black 1.0pt;
               border-left:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>Declaración Jurada
                  sobre sus antecedentes penales y/o judiciales, sujeta a fiscalización
                  posterior, en formato proporcionado por la Universidad. (Verificado por
                  Oficina de Grados y Títulos – Secretaría General).</span>
               </p>
            </td>
         </tr>
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               border-top:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border-top:none;border-left:
               none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
                  normal'><span lang=ES-PE style='font-family:"Times New Roman",serif'>Declaración
                  Jurada de Autenticidad proporcionada por la UNSA, señalando que ha
                  referenciado el material utilizado y que ninguna frase completa o párrafo del
                  documento corresponde a otro autor sin haber sido citado previamente.</span>
               </p>
            </td>
         </tr>
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               border-top:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border-top:none;border-left:
               none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
                  normal'><span lang=ES-PE style='font-family:"Times New Roman",serif'>Informe
                  del asesor dando conformidad que el Trabajo de Investigación esta expedito
                  para proceder con su evaluación.</span>
               </p>
            </td>
         </tr>
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               border-top:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border-top:none;border-left:
               none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
                  normal;border:none'><span lang=ES-PE style='font-family:"Times New Roman",serif;
                  color:black'>Constancia vigente que acredite el dominio a nivel intermedio de
                  un idioma extranjero de preferencia inglés o una lengua nativa, emitida por
                  las Unidades de Producción de Bienes Servicios (UPBS) de la Facultad de
                  Filosofía y Humanidades y/o del Centro de Idiomas de la UNSA para la toma de
                  exámenes de idiomas. También será válida la presentación de una copia
                  fedateada por el Secretario General de la UNSA de alguno de los certificados
                  de exámenes internacionales de idiomas vigentes (TOEFL, CAMBRIDGE y/o
                  MICHIGAN). Aplicable para los ingresantes a partir del año 2015, con
                  excepción de los programas de complementación académica, a quienes se
                  aplicará a partir del 2020.</span>
               </p>
            </td>
         </tr>
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               border-top:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border-top:none;border-left:
               none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
                  normal;border:none'><span lang=ES-PE style='font-family:"Times New Roman",serif;
                  color:black'>Fotografía reciente, de frente, tamaño pasaporte a color con
                  fondo blanco digitalizada, traje formal (varones con terno y mujeres con
                  traje), sin accesorios ni gafas oscuras que cumplan con las características
                  publicadas en la página web de la UNSA (adjuntar de manera opcional y
                  voluntaria, caso contrario se hará uso de la fotografía de la base de datos
                  UNSA.)</span>
               </p>
            </td>
         </tr>
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               border-top:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border-top:none;border-left:
               none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
                  normal'><span lang=ES-PE style='font-family:"Times New Roman",serif'>Autorización
                  de publicación de Trabajo de Investigación, según formato proporcionado por
                  la UNSA por Dirección Universitaria de Gestión de la Información (DUGINF), en
                  el siguiente enlace: </span><span lang=ES-PE><a
                     href="http://repositorio.unsa.edu.pe/page/Generacion-URL"><span
                     style='font-family:"Times New Roman",serif;color:#3367D6;background:white'>http://repositorio.unsa.edu.pe/page/Generacion-URL</span></a></span>
               </p>
            </td>
         </tr>
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               border-top:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border-top:none;border-left:
               none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
                  normal'><span lang=ES-PE style='font-family:"Times New Roman",serif'>Trabajo
                  de Investigación digitalizado en formato PDF.</span>
               </p>
            </td>
         </tr>
         <tr>
            <td width=37 valign=top style='width:28.1pt;border:solid black 1.0pt;
               border-top:none;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;line-height:115%'><span
                  lang=ES-PE style='font-family:"Times New Roman",serif'>&nbsp;</span></p>
            </td>
            <td width=529 valign=top style='width:396.65pt;border-top:none;border-left:
               none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
               padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
                  normal'><span lang=ES-PE style='font-family:"Times New Roman",serif'>Documento
                  que acredite la presentación de un artículo publicado.</span>
               </p>
            </td>
         </tr>
      </table>
      
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