<style>
body {

margin-left:35pt;
margin-right:35pt;
font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
font-size: 0.875rem;
font-weight: normal;
line-height: 1.5;
color: #151b1e;           
}
p.b {
  display: inline-block;
  width: 100px;
  height: 100px;
  padding: 5px;
  border: 1px solid blue;    
  background-color: yellow; 
}
</style>
<div class="logo" style="text-align: left;margin-bottom:10pt;"> 
	        <img width="150" src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/unsa.png" alt="Logo UNSA">
        </div>
        
<p dir="ltr" style="line-height:1.2;text-align: center;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">DECLARACI&Oacute;N JURADA DE NO REGISTRAR ANTECEDENTES</span></p>
<p dir="ltr" style="line-height:1.2;text-align: center;margin-top:0pt;margin-bottom:10pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">&nbsp;PENALES Y/O JUDICIALES</span></p>


<div align="left" dir="ltr" style="margin-left:-5.4pt;">
    <table style="border: none; border-collapse: collapse; margin-right: calc(3%); width: 97%;">
        <tbody>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Apellidos y Nombres</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;">{{ $graduando->apellidos }}, {{ $graduando->nombres }}</td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Nacionalidad</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;"><br></td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">DNI/C.E./Pasaporte</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;">{{ substr($graduando->alumno->dic, 1) }}</td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Direcci&oacute;n</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;">{{ $graduando->direccion }}</td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Distrito y Ciudad</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;"><br></td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Tel&eacute;fono/Celular</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;">{{ $graduando->telefono }}</td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Correo Electr&oacute;nico</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;">{{ $graduando->email_personal }}</td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">CUI</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;">{{ $graduando->cui }}</td>
            </tr>
            <tr style="height:17.473388671875pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Egresado/Bachiller</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;"><br></td>
            </tr>
            <tr style="height:0pt;">
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 20.2815%;">
                    <p dir="ltr" style="line-height:1.7999999999999998;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Programa profesional</span></p>
                </td>
                <td style="border-width: 0.5pt; border-style: solid; border-color: rgb(0, 0, 0); vertical-align: top; padding: 0pt 5.4pt; overflow: hidden; overflow-wrap: break-word; width: 79.5946%;"><br></td>
            </tr>
        </tbody>
    </table>
</div>

<p dir="ltr" style="line-height:1.7999999999999998;text-align: justify;margin-top:20pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Estando al amparo de lo dispuesto por los art&iacute;culos 49&ordm; y 51&ordm; de D.S. 004-2019-JUS, nuevo TUO de la &nbsp;Ley N&ordm; 27444, Ley del Procedimiento Administrativo General y en pleno ejercicio de mis derechos ciudadanos:&nbsp;</span><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">DECLARO BAJO JURAMENTO, NO REGISTRAR ANTECEDENTES PENALES Y/O JUDICIALES,&nbsp;</span><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">a efecto de realizar el tr&aacute;mite para la obtenci&oacute;n de: Grado Acad&eacute;mico de Bachiller: &nbsp;&nbsp;</span><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:underline;-webkit-text-decoration-skip:none;text-decoration-skip-ink:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">{modalidad de obtencion}</span></p>
<p dir="ltr" style="line-height:1.7999999999999998;text-align: justify;margin-top:0pt;margin-bottom:20pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Asimismo, manifiesto que lo declarado corresponde a la verdad de los hechos, teniendo conocimiento que toda declaraci&oacute;n falsa se encuentra sujeta a los alcances de lo establecido en el art&iacute;culo 411&deg; del C&oacute;digo Penal, que establece pena privativa de libertad de hasta cuatro (4) a&ntilde;os, para los que hacen, en un procedimiento administrativo, una falsa declaraci&oacute;n en relaci&oacute;n a hechos o circunstancias que le corresponde probar, violando la presunci&oacute;n de veracidad establecida por ley. Es en ese sentido que la presente declaraci&oacute;n jurada queda sujeta a fiscalizaci&oacute;n posterior por parte de la Universidad Nacional de San Agust&iacute;n de Arequipa.</span></p>

<p dir="ltr" style="line-height:1.3800000000000001;margin-top:0pt;margin-bottom:8pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Arequipa,&nbsp;</span><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:underline;-webkit-text-decoration-skip:none;text-decoration-skip-ink:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">{fecha larga : 05 de octubre del 2021}</span></p>
<div>
<p><br></p>
<p><br></p>
<p dir="ltr" style="line-height:1.3800000000000001;text-align: center;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">_______________________</span></p>
<p dir="ltr" style="line-height:1.3800000000000001;text-align: center;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">FIRMA&nbsp;</span></p>
<p dir="ltr" style="line-height:1.3800000000000001;text-align: center;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">DNI:&nbsp;</span><span style="font-size:11pt;font-family:'Times New Roman';color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;-webkit-text-decoration-skip:none;text-decoration-skip-ink:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">{dni del graduando}&nbsp;</span></p>
<p>&nbsp;</p>
</div>
<!-- no puedo agegar el rectnagulo de huella digital -->
<P ALIGN=JUSTIFY STYLE="text-align: center;margin-bottom: 0in; line-height: 200%"><SPAN DIR="LTR" STYLE="float: center; width: 0.98in; height: 1.29in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
	<P ALIGN=LEFT STYLE="text-align: center;margin-bottom: 0.11in; line-height: 0.65in"><BR><BR>
	</P>
    
</SPAN>
huella dactilar<BR>

</P>
<p><br></p>
