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
        <table class="table-check"  cellspacing=0 cellpadding=0  >
         <tr>
            <td style=" width: 10%;">
               <p></p>
            </td>
            <td style=" width: 90%;">
               Declaración Jurada sobre sus antecedentes penales y/o judiciales, sujeta a fiscalización
                  posterior, en formato proporcionado por la Universidad. (Verificado por
                  Oficina de Grados y Títulos – Secretaría General).
            </td>
         </tr>
         <tr>
            <td style=" width: 10%;">
               <p></p>
            </td>
            <td >
               Declaración
                  Jurada de Autenticidad proporcionada por la UNSA, señalando que ha
                  referenciado el material utilizado y que ninguna frase completa o párrafo del
                  documento corresponde a otro autor sin haber sido citado previamente.
            </td>
         </tr>
         <tr>
            <td style=" width: 10%;">
               <p></p>
            </td>
            <td >
               Informe
                  del asesor dando conformidad que el Trabajo de Investigación esta expedito
                  para proceder con su evaluación.
            </td>
         </tr>
         <tr>
            <td style=" width: 10%;">
               <p></p>
            </td>
            <td >
               
                  
                  Constancia vigente que acredite el dominio a nivel intermedio de
                  un idioma extranjero de preferencia inglés o una lengua nativa, emitida por
                  las Unidades de Producción de Bienes Servicios (UPBS) de la Facultad de
                  Filosofía y Humanidades y/o del Centro de Idiomas de la UNSA para la toma de
                  exámenes de idiomas. También será válida la presentación de una copia
                  fedateada por el Secretario General de la UNSA de alguno de los certificados
                  de exámenes internacionales de idiomas vigentes (TOEFL, CAMBRIDGE y/o
                  MICHIGAN). Aplicable para los ingresantes a partir del año 2015, con
                  excepción de los programas de complementación académica, a quienes se
                  aplicará a partir del 2020.
               
            </td>
         </tr>
         <tr>
             <td style=" width: 10%;">
               <p></p>
            </td>
            <td >
               
                  
                  Fotografía reciente, de frente, tamaño pasaporte a color con
                  fondo blanco digitalizada, traje formal (varones con terno y mujeres con
                  traje), sin accesorios ni gafas oscuras que cumplan con las características
                  publicadas en la página web de la UNSA (adjuntar de manera opcional y
                  voluntaria, caso contrario se hará uso de la fotografía de la base de datos
                  UNSA.)
            </td>
         </tr>
         <tr>
             <td style=" width: 10%;">
               <p></p>
            </td>
            <td >
               
                  Autorización
                  de publicación de Trabajo de Investigación, según formato proporcionado por
                  la UNSA por Dirección Universitaria de Gestión de la Información (DUGINF), en
                  el siguiente enlace: </span><span lang=ES-PE><a
                     href="http://repositorio.unsa.edu.pe/page/Generacion-URL"><span
                     style='font-family:"Times New Roman",serif;color:#3367D6;background:white'>http://repositorio.unsa.edu.pe/page/Generacion-URL</span></a></span>
               
            </td>
         </tr>
         <tr>
             <td style=" width: 10%;">
               <p></p>
            </td>
            <td >
               
                  Trabajo
                  de Investigación digitalizado en formato PDF.
            </td>
         </tr>
         <tr>
             <td style=" width: 10%;">
               <p></p>
            </td>
           <td >
               
                  Documento
                  que acredite la presentación de un artículo publicado.
            </td>
         </tr>
      </table>
      
         <p class="texto"> Hago presente que he realizado el pago por derechos de tramitación según recibo/voucher N°
                            __________________________________, por la cantidad de S/.________________________,
                            expedido por: _________________________, de fecha _______________________________.</p>

        <p class="texto">DECLARO BAJO JURAMENTO que los documentos e información antes indicados son
            AUTÉNTICOS y responden a la verdad de los hechos que en ellos se consignan; así mismo
            declaro conocer que de comprobarse fraude o falsedad en la declaración, información o
            documentación presentada, la UNSA tendrá por no satisfecha la exigencia de su presentación
            para todos los efectos, quedando facultada a imponer multa en favor de la entidad de entre cinco
            (5) y diez (10) Unidades Impositivas Tributarias vigentes a la fecha de pago; y, además, si la
            conducta se adecua a los supuestos previstos en el Título XIX Delitos contra la Fe Pública del
            Código Penal, ésta deberá ser comunicada al Ministerio Público a efecto de que interponga la
            acción penal correspondiente.</p>
        <p class="texto">Por lo expuesto, pido a usted acceder a mi solicitud disponiendo el trámite respectivo.</p>

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