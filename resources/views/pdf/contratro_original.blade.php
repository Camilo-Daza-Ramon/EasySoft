<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Contrato</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/pdf.css">
    <style>
    @page { size: 21.59cm 35.56cm portrait; margin: 0.5cm;}
    
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

    footer{
      margin: 0cm;
      background-image: url('img/intro01.png');
    }
  </style>

  </head>
  <body>
    <main>
      <div class="container contenedor">
        <table class="table principal">
          <tr>
            <td width="50%">
              <table width="100%">
                <tr class="bg-morado">
                  <td>
                    <img src="img/amigored.png" class="logo">
                  </td>
                  <td>
                    <h2 class="titulo">CONTRATO ÚNICO DE SERVICIOS FIJOS</h2>
                  </td>
                  <td style="text-align: right;">
                    <img src="img/logo_mastic.jpg" class="logo2">
                  </td>
                </tr>                
              </table>
              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <p>Este contrato explica las condiciones para la prestación de los servicios entre usted y SISTEMAS Y TELECOMUNICACIONES DEL ORIENTE S.A.S identificada con NIT 804.003.326-6 operador de la marca Amigo Red, por el que pagará mínimo mensualmente $<span class="datos-subrayados">{{$data['valo_pagar']}}</span> por <span class="datos-subrayados">{{$data['cantidad_megas']}}</span> Megas. <br>
                    Este contrato tendrá vigencia de <span class="datos-subrayados">{{$data['vigencia']}}</span> meses, contados a partir del <span class="datos-subrayados">$data['fecha_contrato']</span> El plazo máximo de instalación es de 15 días hábiles. <br>
                    Acepto que mi contrato se renueve  sucesiva y automáticamente por un plazo igual al inicial <input type="checkbox" checked>
                    </p>
                  </td>
                </tr>                
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>EL SERVICIO</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Con este contrato nos comprometemos a prestarle los servicios que usted elija*:</p>
                    <p>Telefonía fija <input type="checkbox">  Internet fijo <input type="checkbox" checked> Televisión <input type="checkbox"></p>
                    <p>Servicios adicionales ______________________________ Usted se compromete a pagar oportunamente el precio acordado. <br>
                    El servicio se activará a más tardar el día ___/___ /___.
                    </p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>INFORMACIÓN DEL SUSCRIPTOR</h2>
                  </td>                  
                </tr>
                <tr>
                  <td>
                    <p>Contrato No. <span class="datos-subrayados">{{$data['numero_contrato']}}</span><br>
                    Nombre / Razón Social <span class="datos-subrayados">{{$data['nombre_suscriptor']}}</span><br>
                    Identificación <span class="datos-subrayados">{{$data['identificacion']}}</span><br>
                    Correo electrónico <span class="datos-subrayados">{{$data['correo']}}</span><br>
                    Teléfono de contacto <span class="datos-subrayados">{{$data['telefono']}}</span><br>
                    Dirección Servicio <span class="datos-subrayados">{{$data['direccion']}}</span>Estrato <span class="datos-subrayados">{{$data['estrato']}}</span> <br>
                    Departamento <span class="datos-subrayados">{{$data['departamento']}}</span> Municipio <span class="datos-subrayados">{{$data['municipio']}}</span> <br>
                    Dirección  Suscriptor <span class="datos-subrayados">{{$data['direccion']}}</span><br>
                    </p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>CONDICIONES COMERCIALES CARACTERÍSTICAS DEL PLAN</h2>
                  </td>                  
                </tr>
                <tr>
                  <td>
                    <p>El servicio de Internet fijo ofrecido obedece a una iniciativa pública financiada por el Ministerio TIC e implementada por el operador Sistemas y Telecomunicaciones del Oriente S.A.S.</p>

                    <ol style="text-align: justify;">
                      <li>La comercialización del servicio deberá dirigirse a hogares de estratos 1 y 2, beneficiarios inscritos en SISBEN IV, y los beneficiarios de la ley 1699 de 2013. 
                    </li>
                      <li>El usuario que se beneficie del proyecto debe suministrar declaración juramentada de que es un nuevo usuario, es decir que él y los miembros de su núcleo familiar que residen en el mismo predio para el que se requiere la conexión, no han contado con la prestación del servicio de Internet fijo, al menos durante los seis meses anteriores a la suscripción.
                    </li>
                      <li>Las tarifas sociales establecidas para el pago mensual del servicio de Internet, según las condiciones técnicas y financieras establecidas en el proyecto, corresponden a $8.613 pesos para estrato 1 y $19.074 pesos para estrato 2 y para el caso de los inscritos al SISBEN IV y beneficiarios de la Ley 1699 de 2013 deberán oscilar en el rango señalado para los estratos 1 y 2 por una velocidad de Internet 5 Mbps Downstream /1 Mbps Upstream.
                    </li>
                      <li>El usuario deberá pagar, de manera anticipada, la tarifa social mensual establecida, según el estrato correspondiente. 
                    durante <span class="datos-subrayados">{{$data['vigencia']}}</span> meses a partir de la instalación del servicio.
                    La disponibilidad de los servicios y aplicaciones incluidas en los planes está sujeta a retiros o cambios que se comunicarán previamente.</li>
                    </ol>
                    <p>Usuario Beneficiario pertenece a: <br>
                      Estrato 1 
                      @if($data['tipo_beneficiario'] == 'Estrato 1')
                        <input type="checkbox" checked>
                      @else
                        <input type="checkbox">
                      @endif
                       Estrato 2 
                       @if($data['tipo_beneficiario'] == 'Estrato 2')
                        <input type="checkbox" checked>
                      @else
                        <input type="checkbox">
                      @endif
                       SISBEN IV
                       @if($data['tipo_beneficiario'] == 'SISBEN IV')
                        <input type="checkbox" checked>
                      @else
                        <input type="checkbox">
                      @endif
                      Beneficiario Ley 1699 de 2013
                      @if($data['tipo_beneficiario'] == 'Ley 1699 de 2013')
                        <input type="checkbox" checked>
                      @else
                        <input type="checkbox">
                      @endif
                      <br>
                      Las tarifas podrán ajustarse anualmente al incremento el IPC.
                    </p>

                  </td>
                </tr>
              </table>

              <table width="100%" style="border:solid 1px;">
                <tr>
                  <td width="85%">
                    <p style="text-align: right;">Valor Total</p>
                  </td>
                  <td class="bg-gris">
                    <p>${{$data['valo_pagar']}}</p>
                  </td>
                </tr>
              </table>              
            </td>

            <td width="50%">

              <table width="100%">
                <tr class="bg-morado-claro">                 
                  <td>
                    <p>N° <span class="right" >{{$data['numero_contrato']}}</span></p>
                  </td>                  
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>PRINCIPALES OBLIGACIONES DEL USUARIO</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p><b>1)</b> Pagar oportunamente los servicios prestados, incluyendo los intereses de mora cuando haya incumplimiento; <b>2)</b> suministrar información verdadera; <b>3)</b> hacer uso adecuado de los equipos y los servicios; <b>4)</b> no divulgar ni acceder a pornografía infantil; Según ley 679 de 2001; <b>5)</b> avisar a las autoridades cualquier evento de robo o hurto de elementos de la red, como el cable; <b>6)</b> No cometer o ser partícipe de actividades de fraude.</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>CALIDAD Y COMPENSACIÓN</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Cuando se presente indisponibilidad del servicio o este se suspenda a pesar de su pago oportuno, lo compensaremos en su próxima factura. Debemos cumplir con las condiciones de calidad definidas por la CRC. Consúltelas en la página: <a href="https://www.amigored.com.co/indicadores" target="_black">https://www.amigored.com.co/indicadores</a></p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>CESIÓN</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Si quiere ceder este contrato a otra persona, debe presentar una solicitud por escrito a través de nuestros Medios de Atención, acompañada de la aceptación por escrito de la persona a la que se hará la cesión. Dentro de los 15 días hábiles siguientes, analizaremos su solicitud y le daremos una respuesta. Si se acepta la cesión queda liberado de cualquier responsabilidad con nosotros.</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>MODIFICACIÓN</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Nosotros no podemos modificar el contrato sin su autorización. Esto incluye que no podemos cobrarle servicios que no haya aceptado expresamente. Si esto ocurre tiene derecho a terminar el contrato, incluso estando vigente la cláusula de permanencia mínima, sin la obligación de pagar suma alguna por este concepto. No obstante, usted puede en cualquier momento modificar los servicios contratados. Dicha modificación se hará efectiva en el período de facturación siguiente, para lo cual deberá presentar la solicitud de modificación por lo menos con 3 días hábiles de anterioridad al corte de facturación.</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>SUSPENSIÓN</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Usted tiene derecho a solicitar la suspensión del servicio por un máximo de 2 meses al año. Para esto debe presentar la solicitud antes del inicio del ciclo de facturación que desea suspender. Si existe una cláusula de permanencia mínima, su vigencia se prorrogará por el tiempo que dure la suspensión.</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>TERMINACIÓN</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Usted puede terminar el contrato en cualquier momento sin penalidades. Para esto debe realizar una solicitud a través de cualquiera de nuestros Medios de Atención mínimo 3 días hábiles antes del corte de facturación (su corte de facturación es el día 25 de cada mes). Si presenta la solicitud con una anticipación menor, la terminación del servicio se dará en el siguiente periodo de facturación. <br>
                    Así mismo, usted puede cancelar cualquiera de los servicios contratados, para lo que  le informaremos las condiciones en las que serán prestados los servicios no cancelados y actualizaremos el contrato. Así mismo, si el operador no inicia la prestación del servicio en el plazo acordado, usted puede pedir la restitución de su dinero y la terminación del contrato.</p>
                  </td>
                </tr>
              </table>

            </td>
          </tr>
        </table>

        

        <table class="table principal">
          <tr>
            <td width="50%">
              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>PAGO Y FACTURACIÓN</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>La factura le debe llegar como mínimo 5 días hábiles antes de la fecha de pago. Si no llega, puede solicitarla a través de nuestros Medios de Atención y debe pagarla oportunamente. <br>
                      Si no paga a tiempo, previo aviso, suspenderemos su servicio hasta que pague sus saldos pendientes. Contamos con 3 días hábiles luego de su pago para reconectarle el servicio. Si no paga a tiempo, también podemos reportar su deuda a las centrales de riesgo. Para esto tenemos que avisarle por lo menos con 20 días calendario de anticipación. Si paga luego de este reporte tenemos la obligación dentro del mes de seguimiento de informar su pago para que ya no aparezca reportado. <br>
                      Si tiene un reclamo sobre su factura, puede presentarlo antes de la fecha de pago y en ese caso no debe pagar las sumas reclamadas hasta que resolvamos su solicitud. Si ya pagó, tiene 6 meses para presentar la reclamación.</p>
                  </td>
                </tr>
                <tr>
                  <td style="border: solid 1px;">
                    
                    <span class="text-info-abajo">Con esta firma acepta recibir la factura solamente por medios electrónicos</span>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado" colspan="2">
                    <h2>CÓMO COMUNICARSE CON NOSOTROS <br>
                    (MEDIOS DE ATENCIÓN)</h2>
                  </td>
                </tr>
                <tr>
                  <td class="bg-morado">
                    <h2 style="text-align: center;">1</h2>
                  </td>
                  <td width="90%" style="border-right: solid 1px; border-bottom: solid 1px;">
                    <p>Nuestros medios de atención son: oficinas físicas, página web, redes sociales y líneas telefónicas gratuitas.</p>
                  </td>
                </tr>
                <tr>
                  <td class="bg-morado">
                    <h2 style="text-align: center;">2</h2>
                  </td>
                  <td width="90%" style="border-right: solid 1px; border-bottom: solid 1px;">
                    <p>Presente cualquier queja, petición/reclamo o recurso a través de estos medios y le responderemos en máximo 15 días hábiles.</p>
                  </td>
                </tr>
                <tr>
                  <td class="bg-morado">
                    <h2 style="text-align: center;">3</h2>
                  </td>
                  <td width="90%" style="border-right: solid 1px; border-bottom: solid 1px;">
                    <p>Si no respondemos es porque aceptamos su petición o reclamo. Esto se llama silencio administrativo positivo y aplica para internet y telefonía.</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p style="text-align: center"><b>Si no está de acuerdo con nuestra respuesta</b></p>
                  </td>
                </tr>
                <tr>
                  <td class="bg-morado">
                    <h2 style="text-align: center;">4</h2>
                  </td>
                  <td width="90%" style="border: solid 1px;">
                    <p>Cuando su queja o petición sea por los servicios de telefonía y/o internet, y esté relacionada con actos de negativa del contrato, suspensión del servicio, terminación del contrato, corte y facturación; usted puede insistir en su solicitud ante nosotros, dentro de los 10 días hábiles siguientes a la respuesta, y pedir que si no llegamos a una solución satisfactoria para usted, enviemos su reclamo directamente a la SIC (Superintendencia de Industria y Comercio) quien resolverá de manera definitiva su solicitud. Esto se llama recurso de reposición y en subsidio apelación. <br>
                    Cuando su queja o petición sea por el servicio de televisión, puede enviar la misma a la Autoridad Nacional de Televisión informacion@antv.gov.co, para que esta Entidad resuelva su solicitud.</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>ACEPTO CLÁUSULA DE PERMANENCIA MÍNIMA <input type="checkbox"> *</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>En consideración a que le estamos otorgando un descuento respecto del valor del cargo por conexión, o le diferimos el pago del mismo, se incluye la presente cláusula de permanencia mínima. En la factura encontrará el valor a pagar si decide terminar el contrato anticipadamente.</p>
                  </td>
                </tr>
              </table>

              <table class="table-bordered" width="100%">
                <tr>
                  <td class="bg-morado">
                    <span>Valor total del cargo por conexión</span>
                  </td>
                  <td>
                    <p>$</p>
                  </td>
                </tr>
                <tr>
                  <td class="bg-morado">
                    <span>Suma que le fue descontada o diferida del valor total del cargo por conexión</span>
                  </td>
                  <td>
                    <p>$</p>
                  </td>
                </tr>
                <tr>
                  <td class="bg-morado">
                    <span>Fecha de inicio de la permanencia mínima</span>
                  </td>
                  <td>
                    <p>___/___/___/</p>
                  </td>
                </tr>
                <tr>
                  <td class="bg-morado">
                    <span>Fecha de finalización de la permanencia mínima</span>
                  </td>
                  <td>
                    <p>___/___/___/</p>
                  </td>
                </tr>                
              </table>

              <table class="table-bordered" width="100%">
                <tr>
                  <td class="bg-morado" colspan="6">
                    <span>Valor a pagar si termina el contrato anticipadamente según el mes</span>
                  </td>
                </tr>
                <tr class="text-center">
                  <td>
                    <span>Mes 1 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 2 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 3 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 4 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 5 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 6 <br>$0.00</span>
                  </td>
                </tr>
                <tr class="text-center">
                  <td>
                    <span>Mes 7 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 8 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 9 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 10 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 11 <br>$0.00</span>
                  </td>
                  <td>
                    <span>Mes 12 <br>$0.00</span>
                  </td>
                </tr>
              </table>


            </td>
            <td width="50%">
              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>CAMBIO DE DOMICILIO</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Usted puede cambiar de domicilio y continuar con el servicio siempre que sea técnicamente posible. Si desde el punto de vista técnico no es viable el traslado del servicio, usted puede ceder su contrato a un tercero o terminarlo pagando el valor de la cláusula de permanencia mínima si está vigente.</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>LARGA DISTANCIA (TELEFONÍA)</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Nos comprometemos a usar el operador de larga distancia que usted nos indique, para lo cual debe marcar el código de larga distancia del operador que elija.</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <h2>COBRO POR RECONEXIÓN DEL SERVICIO</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>En caso de suspensión del servicio por mora en el pago, podremos cobrarle un valor por reconexión que corresponderá estrictamente a los costos asociados a la operación de reconexión. En caso de servicios empaquetados procede máximo un cobro de reconexión por cada tipo de conexión empleado en la prestación de los servicios. <br>
                    Costo reconexión: $10.000</p>
                  </td>
                </tr>
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <p>El usuario es el ÚNICO responsable por el contenido y la información que se curse a través de la red y del uso que se haga de los equipos o de los servicios.</p>
                  </td>
                </tr>                
              </table>

              <table width="100%">
                <tr>
                  <td class="bg-morado">
                    <p>Los equipos de comunicaciones que ya no use son desechos que no deben ser botados a la caneca, consulte nuestra política de recolección de aparatos en desuso.</p>
                  </td>
                </tr>                
              </table>

              <table width="100%" style="border: 1px solid;">
                <tr>
                  <td class="bg-morado">
                    <h2>CONDICIONES DEL SERVICIO</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p><b>1)</b> Suspensión del contrato por No pago de 2 facturas consecutivas. El usuario pagará cargo de reconexión y los servicios que pueda usar durante la suspensión y terminación por: Vencimiento del plazo o de sus prorrogas; Incumplimiento de sus obligaciones; Fuerza mayor/caso fortuito; Uso inadecuado de la red o del servicio; por prevención de fraude; No viabilidad técnica o económica para prestar el servicio; Irregularidades en los documentos suministrados; o por evolución tecnológica. <b>2)</b> El usuario responde por los equipos entregados para prestación y operación del servicio y autoriza el cobro de su reposición por daño o perdida. Deberá entregarlos a la terminación del contrato del modo establecido en regulación, de no hacerlo pagará el valor comercial de los mismos. <b>3)</b> Las tarifas podrán incrementar por mes o año sin superar el 50%de la tarifa antes del incremento, más el índice de precios al consumidor del año anterior. Podrán modificarse excediendo dicho límite, y el usuario podrá terminar el contrato en los 30 días siguientes. <b>4)</b> El interés de mora es el máximo legal, se cobrarán los gastos de cobranza judicial y extrajudicial. Respondemos hasta 3 cargos mensuales anteriores al daño. No respondemos por lucro cesante, daños indirectos, incidentales o consecuenciales. <b>5)</b> Este contrato presta mérito ejecutivo para hacer exigibles las obligaciones y prestaciones contenidas en él. <b>6)</b> El cargo por cada solicitud de traslado de equipos a una nueva dirección según la tarifa vigente. Costo de traslado: $60.000 + IVA. <b>7)</b> El usuario deberá pagar el excedente por metro de cable adicional al autorizado para la instalación $1.800 + IVA por metro, al momento de realizar traslados solicitados. <b>8)</b> No podemos garantizar vía wifi, la velocidad contratada, toda vez que ésta depende de múltiples aspectos, que no son siempre directamente imputables al proveedor del servicio, por ejemplo: equipos, configuración, tarjetas de red, obstáculos físicos permanentes y/o transitorios, entre otros. En caso de que un cliente perciba que la velocidad no cumple sus expectativas, deberá comunicarse con las líneas de atención.</p>
                  </td>
                </tr>                
              </table>

              <table class="table-bordered" width="100%">
                <tr>
                  <td colspan="2">
                    <img src=".{{Storage::url($data['firma'])}}" width="220"> <br>
                    <span>Aceptación contrato mediante firma o cualquier otro medio válido</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>CC/CE <span class="right">{{$data['identificacion']}}</span></p>
                  </td>
                  <td>
                    <p>FECHA <span class="right">{{$data['fecha_contrato']}}</span></p>
                  </td>
                </tr>
              </table>
              <span>Consulte el régimen de protección de usuarios en www.crcom.gov.co</span>

            </td>
          </tr>
        </table>
        
        
      </div>
    </main>
  </body>
</html>