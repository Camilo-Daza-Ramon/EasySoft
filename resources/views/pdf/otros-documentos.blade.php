<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Otros Documentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/pdf.css">
    <style>
    @page { size: 21cm 29.9cm portrait;}
    
    @font-face {
      font-family: 'Calibri';
      src: url({{ storage_path('fonts\\calibri.ttf')}});      
    }

    body{      
      font-family: 'Calibri';
      font-size: 12px;      
    }

    .page-break {
      page-break-after: always;
    }
    main{
      margin-top: 2cm;
    }

    header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;            
            text-align: center;
            line-height: 30px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;            
            text-align: center;
        }
  </style>

  </head>
  <body>
    <header>
      <img src="img/amigored.png" class="logo"><img src="img/logo_mintic.png" class="logo3"><img src="img/logo-presidencia.png" class="logo3">
    </header>
    <main>      
      <div class="container contenedor">        
        <h2 style="text-align: center; font-size: 15px; font-weight: 400;">FORMATO DE AUTORIZACIÓN DE TRATAMIENTO PROTECCIÓN DE DATOS PERSONALES</h2>
        <br>
        <p>Yo <span class="datos-subrayados">{{$data['nombre_suscriptor']}}</span>, identificado con la C.C.<span class="datos-subrayados">{{$data['identificacion']}}</span>, de <span class="datos-subrayados">{{$data['documento_expedicion']}}</span>, con domicilio en la <span class="datos-subrayados">{{$data['direccion']}}</span> de la ciudad de <span class="datos-subrayados">{{$data['municipio']}}</span>, dando cumplimiento a lo dispuesto en la Ley 1581 de 2012, "Por el cual se dictan disposiciones generales para la protección de datos personales" y de conformidad con lo señalado en el Decreto 1377 de 2013, manifiesto de forma libre y espontánea que:</p>

        <ol>
          <li>Con ocasión de la suscripción el presente contrato y por ser beneficiario del proyecto de Incentivos a la Demanda Fase II, implementado por el Fondo Único de Tecnologías de la Información y las Comunicaciones, acepto de forma libre y espontánea que los datos recolectados sean conocidos y compartidos a la firma interventora del contrato suscrito por la empresa Sistemas y Telecomunicaciones del Oriente S.A.S., quien hace parte de la UNIÓN TEMPORAL CONECTAMOS EL NORTE UT y al Fondo Único de Tecnologías de la Información y las Comunicaciones.</li>
          <li>Con la firma de este documento manifiesto que he sido informado por la empresa Sistemas y Telecomunicaciones del Oriente S.A.S. quien actuará como responsable del Tratamiento de datos personales de los cuales soy titular y que, conjunta o separadamente podrá recolectar, usar y tratar mis datos personales conforme a la Política de Privacidad y Tratamiento de Datos Personales, disponible en SGC, en página web de la entidad.</li>
          <li>Que me ha sido informada la (s) finalidad (es) de la recolección de los datos personales, la cual se encuentra expuesta públicamente en cartelera, página web, medios plegables de la factura y contrato de servicio.</li>
          <li>Es de carácter facultativo o voluntario responder preguntas que versen sobre Datos Sensibles o sobre menores de edad.</li>
          <li>Mis derechos como titular de los datos son los previstos en la Constitución y la ley, especialmente el derecho a conocer, actualizar, rectificar y suprimir mi información personal, así como el derecho a revocar el consentimiento otorgado para el tratamiento de datos personales.</li>
          <li>Los derechos pueden ser ejercidos a través de los canales dispuestos por Sistemas y Telecomunicaciones del Oriente S.A.S. y observando la Política de Privacidad y Tratamiento de Datos Personales de Sistemas y Telecomunicaciones del Oriente S.A.S.</li>
          <li>Mediante la página web de la entidad (<a href="https://www.amigored.com.co/">https://www.amigored.com.co/</a>), podré radicar cualquier tipo de requerimiento relacionado con el tratamiento de mis datos personales.</li>
          <li>Sistemas y Telecomunicaciones del Oriente S.A.S. garantizará la confidencialidad, libertad, seguridad, veracidad, transparencia, acceso y circulación restringida de mis datos y se reservará el derecho de modificar su Política de Privacidad y Tratamiento de Datos Personales en cualquier momento. Cualquier cambio será informado y publicado oportunamente en la página web.</li>
          <li>Teniendo en cuenta lo anterior, autorizo de manera voluntaria, previa, explícita, informada e inequívoca a la empresa Sistemas y Telecomunicaciones del Oriente S.A.S. para tratar mis datos personales, tomar mi huella y fotografía de acuerdo con su Política de Privacidad y Tratamiento de Datos Personales para los fines relacionados con su objeto y en especial para fines legales, contractuales, misionales descritos en la Política de Privacidad y Tratamiento de Datos Personales.</li>
          <li>La información obtenida para el Tratamiento de mis datos personales la he suministrado de forma voluntaria y es verídica.</li>
        </ol>

        <p>Se firma en la ciudad de <b>{{$data['municipio']}} ({{$data['departamento']}})</b> a los <b>{{date("d", strtotime( $data['fecha_contrato']))}}</b> días del mes de <b>{{date("M", strtotime( $data['fecha_contrato']))}}</b> del año <b>{{date("Y", strtotime( $data['fecha_contrato']))}}</b>.</p>

        <p><b>FIRMA:</b> <img src=".{{Storage::url($data['firma'])}}" width="220" style="margin: 5px;"> <br>
          <b>NOMBRE:</b> {{$data['nombre_suscriptor']}}<br>
          <b>CC/CE: </b> {{$data['identificacion']}}<br>
          <b>FECHA: </b> {{$data['fecha_contrato']}}
        </p>
        

        <footer>
          <table width="100%">
            <tr>
              <td><img src="img/logo_mastic.png" class="logo"></td>
              <td>
                <center><span>Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 – 302 PBX: +57 (7) 6334050 <br>Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co. <br>GC F 004   Versión 04   11 Feb 2020</span></center>
              </td>
            </tr>
          </table>         
        </footer>
      
        <div class="page-break"></div>

        <h2 style="text-align: center; font-size: 15px; font-weight: 400; margin-top: 2cm;">AUTORIZACIÓN PARA CONSULTA Y REPORTE A CENTRALES DE INFORMACIÓN COMERCIAL SOBRE EL CLIENTE</h2>
        <br>
        <p>Lea cuidadosamente la siguiente cláusula y pregunte la que no comprenda.</p>
        <p>Yo <span class="datos-subrayados">{{$data['nombre_suscriptor']}}</span>, identificado con la C.C.<span class="datos-subrayados">{{$data['identificacion']}}</span>, autorizo de manera irrevocable a Sistemas y Telecomunicaciones del Oriente S.A.S de Colombia S.A. E.S.P., o a quien sea en un futuro acreedor de esta obligación, para:</p>


        <ol type="a">
          <li>Consultar, en cualquier tiempo, en las centrales de riesgo toda la información relevante para conocer mi desempeño como deudor, mi capacidad de pago o para valorar el riesgo futuro de concederme crédito.</li>
          <li>Reportar a las centrales de información de riesgo, que administren base de datos, la información sobre el comportamiento de las obligaciones de contenido patrimonial que adquiera para con Sistemas y Telecomunicaciones del Oriente S.A.S. cualquier título y las facture por parte de Sistemas y Telecomunicaciones del Oriente S.A.S. en relación con los servicios que está a los terceros con quienes han celebrado convenios de facturación, me hayan prestado, de tal forma que estas presenten una información veraz, pertinente, completa, actualizada y exacta, de mi desempeño como deudor después de haber cruzado y procesado diversos datos útiles para obtener una información significativa.</li>
          <li>Enviar la información mencionada a las centrales de riesgo de manera directa y, también, por intermedio de la superintendencia Bancaria o las demás entidades públicas que ejercen funciones de vigilancia y control, con el fin de que éstas puedan tratarla, analizarla, clasificarla y luego suministrarla a dichas centrales.</li>
          <li>Conservar la información reportada, en la base de datos de las centrales de riesgo, con las debidas actualizaciones y durante el periodo necesario señalado en sus reglamentos.</li>          
        </ol>

        <p>Declaro haber leído cuidadosamente el contenido de esta cláusula y haberla comprendido a cabalidad, razón por la cual entiendo sus alcances y sus implicaciones.</p>

        <p><b>FIRMA:</b> <img src=".{{Storage::url($data['firma'])}}" width="220" style="margin: 5px;"> <br>
          <b>NOMBRE:</b> {{$data['nombre_suscriptor']}}<br>
          <b>CC/CE: </b> {{$data['identificacion']}}<br>
          <b>FECHA: </b> {{$data['fecha_contrato']}}
        </p>

        <footer>
          <table width="100%">
            <tr>
              <td><img src="img/logo_mastic.png" class="logo"></td>
              <td>
                <center><span>Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 – 302 PBX: +57 (7) 6334050 <br>Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co. <br>GC F 004   Versión 04   11 Feb 2020</span></center>
              </td>
            </tr>
          </table>         
        </footer>

        <div class="page-break"></div>

        <h2 style="text-align: center; font-size: 15px; font-weight: 400; margin-top: 2cm;">ANEXO 1 AL CONTRATO DE PRESTACIÓN DE SERVICIOS DE TELECOMUNICACIONES, PARA PREVENIR Y CONTRARRESTAR LA EXPLOTACIÓN Y LA PORNOGRAFÍA INFANTIL.</h2>
        <p>Las partes se comprometen de manera expresa y suscriben el presente documento en constancia, a dar cumplimiento a todas las disposiciones legales y reglamentarias sobre el adecuado uso de la red, y la prevención de acceso a páginas de contenido restringido, toda forma de explotación pornográfica, turismo sexual y demás formas de abuso de menores según lo previsto en la Ley 679 de 2001 y sus decretos reglamentarios. Así mismo se comprometen a implementar todas las medidas de tipo técnico que considere necesarias para prevenir dichas conductas.</p>
        <p>En cumplimiento del artículo 7º del Decreto 1524 de 2002, "Por el cual reglamenta el artículo 5° de la Ley 679 de 2001" y con el objeto de prevenir el acceso de menores de edad a cualquier modalidad de información pornográfica contenida en Internet o en las distintas clases de redes informáticas a las cuales se tenga acceso mediante redes globales de información. Así mismo con el fin de propender para que estos medios no sean aprovechados con fines de explotación sexual infantil u ofrecimiento de servicios comerciales que impliquen abuso sexual con menores de edad. Se advierte que el incumplimiento de las siguientes prohibiciones y deberes acarreará para el incumplido las sanciones administrativas y penales contempladas en la Ley 679 de 2001 y en el Decreto 1524 de 2002.</p>
        <p><b>PROHIBICIONES.</b> Los proveedores o servidores, administradores y usuarios de redes globales de información no podrán:</p>

        <ol>
          <li>Alojar en su propio sitio imágenes, textos, documentos o archivos audiovisuales que impliquen directa o indirectamente actividades sexuales con menores de edad. </li>
          <li>Alojar en su propio sitio material pornográfico, en especial en modo de imágenes o videos, cuando existan indicios de que las personas fotografiadas o filmadas son menores de edad.</li>
          <li>Alojar en su propio sitio vínculos o "links", sobre sitios telemáticos que contengan o distribuyan material pornográfico relativo a menores de edad.</li>
        </ol>

        <p><b>DEBERES.</b> Sin perjuicio de la obligación de denuncia consagrada en la ley para todos los residentes en Colombia, los proveedores, administradores y usuarios de redes globales de información deberán: </p>

        <ol>
          <li>Denunciar ante las autoridades competentes cualquier acto criminal contra menores de edad de que tengan conocimiento, incluso de la difusión de material pornográfico asociado a menores.</li>
          <li>Combatir con todos los medios técnicos a su alcance la difusión de material pornográfico con menores de edad. </li>
          <li>Abstenerse de usar las redes globales de información para divulgación de material ilegal con menores de edad. </li>
          <li>Establecer mecanismos técnicos de bloqueo por medio de los cuales los usuarios se puedan proteger a sí mismos o a sus hijos de material ilegal, ofensivo o indeseable en relación con menores de edad. Se prohíbe expresamente el alojamiento de contenidos de pornografía infantil.</li>
        </ol>

        <p><b>SANCIONES ADMINISTRATIVAS:</b>  Los proveedores o servidores, administradores y usuarios que no cumplan o infrinjan lo establecido en el presente capítulo, serán sancionados por el Ministerio de Tecnologías de la Información y las Comunicaciones sucesivamente de la siguiente manera:</p>
        <ol>
          <li>Multas hasta de cien (100) salarios mínimos legales mensuales vigentes, que serán pagadas al Fondo Contra la Explotación Sexual de Menores, de que trata el artículo 24 de la Ley 679 de 2001.</li>
          <li>Suspensión de la correspondiente página electrónica. </li>
          <li>Cancelación de la correspondiente página electrónica. Para la imposición de estas sanciones se aplicará el procedimiento establecido en el Código de Procedimiento Administrativo y de lo Contencioso Administrativo, con observancia del debido proceso y criterios de adecuación, proporcionalidad y reincidencia. Parágrafo. El Ministerio de Tecnologías de la Información y las Comunicaciones adelantará las investigaciones administrativas pertinentes e impondrá, si fuere el caso, las sanciones previstas en este Título, sin perjuicio de las investigaciones penales que adelanten las autoridades competentes y de las sanciones a que ello diere lugar.</li>
        </ol>
        <p>Acepto,</p>
        <table width="100%" class="table-bordered">
          <tr>
            <td>
              <img src=".{{Storage::url($data['firma'])}}" width="200">
            </td>
            <td>
              <p><b>Nombre</b> <br>
                {{$data['nombre_suscriptor']}}
              </p>
              <p><b>CC/CE:</b> <span class="right">{{$data['identificacion']}}</span></p>
            </td>            
          </tr>
        </table>

        <footer>
          <table width="100%">
            <tr>
              <td><img src="img/logo_mastic.png" class="logo"></td>
              <td>
                <center><span>Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 – 302 PBX: +57 (7) 6334050 <br>Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co. <br>GC F 004   Versión 04   11 Feb 2020</span></center>
              </td>
            </tr>
          </table>         
        </footer>

        <div class="page-break"></div>

        <h2 style="text-align: center; font-size: 15px; font-weight: 400; margin-top: 2cm;">ANEXO 2 CONDICIONES DE LA PRESTACIÓN DEL SERVICIO</h2>
        <p>Conforme lo establecido en el contrato suscrito entre el Fondo Único de Tecnologías de la Información y las Comunicaciones y Sistemas y Telecomunicaciones del Oriente S.A.S, a continuación, le informamos:</p>


        <ol>
          <li>Que el proyecto de incentivos a la demanda Fase II es financiado por el Fondo Único de Tecnologías de la Información y las Comunicaciones.</li>
          <li>El usuario deberá pagar, de manera anticipada, la tarifa social mensual establecida, según el estrato correspondiente, a lo largo de la operación, es decir, dentro de los próximos <span class="datos-subrayados">{{$data['vigencia']}}</span> meses, sin que esto implique la existencia de cláusula de permanencia alguna.</li>
          <li>Las tarifas sociales establecidas para el pago mensual del servicio de Internet, según las condiciones técnicas y financieras establecidas en el proyecto, corresponden a $8.613 pesos para estrato 1 y $19.074 pesos para estrato 2 y para el caso de los inscritos al SISBEN IV y beneficiarios de la Ley 1699 de 2013 deberán oscilar en el rango señalado para los estratos 1 y 2 por una velocidad de Internet 5 Mbps Downstream /1 Mbps Upstream. El servicio de instalación no tiene costo alguno.</li>
          <li>Las características del plan de Internet fijo, corresponden a:  Parámetros de la velocidad de 5 Mbps Downstream /1 Mbps Upstream</li>
          <li>Los datos de contacto de la mesa de ayuda son: <br>
            <p>Línea de atención: (7) 6705425<br>
            Línea gratuita:  018000945080<br>
            e-Mail:  servicioalcliente@amigored.com.co<br>
            Oficina Principal:<br>
            calle 35 # 17–77 Centro Edificio Bancoquia.<br>
            Bucaramanga, Santander, Colombia<br>
            </p>
          </li>
          <li>Los puntos de pago autorizados: <br>
            <ul>
              <li>Botón PSE, desde cualquier entidad bancaria, a través de nuestra página de internet www.amigored.com.co/puntos-de-pago/</li>
              <li>Puntos de atención de Efecty</li>
              <li>Puntos de atención de Baloto</li>
            </ul>
          </li>
          <li>Modalidad de facturación: digital y/o física, conforme lo autorice la Ley y el usuario</li>
          <li>Cargo que deberá asumir el usuario en caso de requerir una reinstalación del servicio por cambio de predio $60.000 + IVA</li>
          <li>Cargo que deberá asumir el usuario en caso de requerir reposición de un equipo por daños $135.300 + IVA</li>
        </ol>

        <p>Se firma en constancia de recibido, a los <b>{{date("d", strtotime( $data['fecha_contrato']))}}</b> del mes de <b>{{date("M", strtotime( $data['fecha_contrato']))}}</b> del año <b>{{date("Y", strtotime( $data['fecha_contrato']))}}</b>.</p>

        <p><b>Firma:</b> <img src=".{{Storage::url($data['firma'])}}" width="220" style="margin: 5px;"> <br>
          <b>Nombres:</b> {{$data['nombre']}} <b>Apellidos:</b> {{ $data['apellido']}} <br>
          <b>Cédula No:</b> {{$data['identificacion']}} <b>Teléfono No:</b> {{$data['telefono']}} <br>
          <b>Dirección:</b> {{$data['direccion']}} <b>Municipio:</b> {{$data['municipio']}} ({{$data['departamento']}}) <br>
          <b>Correo Electrónico:</b> {{$data['correo']}} <br>
        </p>

       

        <footer>
          <table width="100%">
            <tr>
              <td><img src="img/logo_mastic.png" class="logo"></td>
              <td>
                <center><span>Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 – 302 PBX: +57 (7) 6334050 <br>Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co. <br>GC F 004   Versión 04   11 Feb 2020</span></center>
              </td>
            </tr>
          </table>         
        </footer>
      </div>    
    </main>
    
  </body>
</html>