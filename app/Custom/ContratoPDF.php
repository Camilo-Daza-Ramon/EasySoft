<?php 

namespace App\Custom;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;


class ContratoPDF extends Fpdf
{
	public $datos;

	function logos(){
    $this->Image('img/logos_proyecto.jpg' , 10 ,5, 180 , 0,'jpg');
        
    $this->Ln();
    $this->SetY(30);
  }

	function pagina1(){

		$data = $this->datos;

		//fondo 
      	$this->Image('img/fondo.png','0','0','205','347','PNG');

	    #COLUMNA 1
	    $this->SetFillColor(85,64,111);

	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',14);
		
	    $this->SetXY(10,5);
	    $this->MultiCell(90,8, utf8_decode("CONTRATO ÚNICO\nDE SERVICIOS FIJOS"),0, 'C', true);
	    $this->Image('img/amigored1.png', 10 ,5.5, 26 , 0,'PNG');
	    $this->Image('img/logo_mastic.jpg', 80 ,5, 20 , 0,'JPG');
	    $this->Ln(2);

	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("Este contrato explica las condiciones para la prestación de los servicios entre usted y SISTEMAS Y TELECOMUNICACIONES DEL ORIENTE S.A.S identificada con NIT 804.003.326-6 operador de la marca Amigo Red, por el que pagará mínimo mensualmente $" . $data['valor_pagar']." por ". $data['cantidad_megas'] ." Megas.\nEste contrato tendrá vigencia de " .$data['vigencia']. " meses, contados a partir del ". $data['fecha_contrato'] ." El plazo máximo de instalación es de 15 días hábiles.\nAcepto que mi contrato se renueve  sucesiva y automáticamente"),0, 'J', true);
	    $this->Cell(36,4, 'por un plazo igual al inicial', 0, 0 , 'L', true);

	    $x = $this->GetX();
	    $this->Cell(4,4, '', 1, 0 , 'L', false);
	    $this->Cell(50,4, '*', 0, 0 , 'L', true);
	    
	    //Equis
	    $y = $this->GetY();
	    /*$this->Line($x,$y,($x + 4),($y + 4));
	    $this->Line($x,($y + 4),($x + 4),$y);*/
	    $this->Ln(7);


	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, 'EL SERVICIO', 0,'L', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("Con este contrato nos comprometemos a prestarle los servicios que usted elija*:"),0, 'J', false);

	    $this->Cell(20,4,utf8_decode("Telefonía fija "),0,0);
	    $this->Cell(4,4,utf8_decode(""),1,0);
	    $this->Cell(20,4,utf8_decode(" Internet fijo "),0,0);
	    $x = $this->GetX();
	    $this->Cell(4,4,"",1,0);
	    $this->Cell(17,4,utf8_decode(" Televisión "),0,0);
	    $this->Cell(4,4,utf8_decode(""),1,0);

	    //Equis
	    $y = $this->GetY();
	    $this->Line($x,$y,($x + 4),($y + 4));
	    $this->Line($x,($y + 4),($x + 4),$y);
	    $this->Ln(5);

	    $this->MultiCell(90,4, utf8_decode("NADA QUE HACER ______________________________ Usted se compromete a pagar oportunamente el precio acordado.\nEl servicio se activará a más tardar el día ___/___ /___. "),0, 'J', false);
	    $this->Ln(3);

	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('INFORMACIÓN DEL SUSCRIPTOR'), 0,'L', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("Contrato No.:".$data['contrato']." \nNombre / Razón Social: ".$data['nombre_suscriptor']."\nIdentificación: ".$data['identificacion']."\nCorreo electrónico: ".$data['correo']."\nTeléfono de contacto: ".$data['telefono']."\nDirección Servicio: ".$data['direccion']."\nEstrato: ".$data['estrato']."   Barrio: ".$data['barrio']."\nDepartamento: ".$data['departamento']." Municipio: ".$data['municipio']."\nDirección Suscriptor: " . $data['direccion']),1, 'J', false);
	    $this->Ln(3);

	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('CONDICIONES COMERCIALES CARACTERÍSTICAS DEL PLAN'), 0,'L', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("El servicio de Internet fijo ofrecido obedece a una iniciativa pública financiada por el Ministerio TIC e implementada por el operador Sistemas y Telecomunicaciones del Oriente S.A.S.\n1.  La comercialización del servicio deberá dirigirse a hogares de estratos 1 y 2, beneficiarios inscritos en SISBEN IV, y los beneficiarios de la ley 1699 de 2013.\n2.  El usuario que se beneficie del proyecto debe suministrar declaración juramentada de que es un nuevo usuario, es decir que él y los miembros de su núcleo familiar que residen en el mismo predio para el que se requiere la conexión, no han contado con la prestación del servicio de Internet fijo, al menos durante los seis meses anteriores a la suscripción.\n3.  Las tarifas sociales establecidas para el pago mensual del servicio de Internet, según las condiciones técnicas establecidas en el presente documento, corresponden a $8.613 pesos para estrato 1 y $19.074 pesos para estrato 2. y para el caso de los inscritos al SISBEN IV y beneficiarios de la Ley 1699 de 2013 deberán oscilar en el rango señalado para los estratos 1 y 2 por una velocidad de Internet 5 Mbps Downstream/1 Mbps Upstream. \n4. El usuario deberá pagar, de manera anticipada, la tarifa social mensual establecida, según el estrato correspondiente. durante ". $data['vigencia'] ." meses a partir de la instalación del servicio. La disponibilidad de los servicios y aplicaciones incluidas en los planes está sujeta a retiros o cambios que se comunicarán previamente."),'L R', 'J', false);

	    $this->Cell(90,4,"Usuario Beneficiario pertenece a:","L R",0);
	    $this->Ln();

	    $this->Cell(14,4,utf8_decode("Estrato 1 "),"L",0);
	    $x = $this->GetX();
	    $this->Cell(4,4,utf8_decode(""),1,0);

	    if ($data['tipo_beneficiario'] == 'Estrato 1') {
	    	$y = $this->GetY();
	    	$this->Line($x,$y,($x + 4),($y + 4));
	    	$this->Line($x,($y + 4),($x + 4),$y);
	    }


	    $this->Cell(14,4,utf8_decode(" Estrato 2"),0,0);
	    $x = $this->GetX();
	    $this->Cell(4,4,"",1,0);

	    if ($data['tipo_beneficiario'] == 'Estrato 2') {
	    	$y = $this->GetY();
	    	$this->Line($x,$y,($x + 4),($y + 4));
	    	$this->Line($x,($y + 4),($x + 4),$y);
	    }


	    $this->Cell(16,4,utf8_decode("  SISBEN IV "),0,0);
	    $x = $this->GetX();
	    $this->Cell(4,4,utf8_decode(""),1,0);

	    if ($data['tipo_beneficiario'] == 'SISBEN IV') {
	    	$y = $this->GetY();
	    	$this->Line($x,$y,($x + 4),($y + 4));
	    	$this->Line($x,($y + 4),($x + 4),$y);
	    }

	    $xx = $this->GetX();
	    $this->Cell(90 - $xx + 10,4,"","R",0);

	    

	    $this->Ln();
	    $this->Cell(42,4,utf8_decode("Beneficiario Ley 1699 de 2013"),"L",0);
	    $x = $this->GetX();
	    $this->Cell(4,4,utf8_decode(""),1,0);

	    if ($data['tipo_beneficiario'] == 'Ley 1699 de 2013') {
	    	$y = $this->GetY();
	    	$this->Line($x,$y,($x + 4),($y + 4));
	    	$this->Line($x,($y + 4),($x + 4),$y);
	    }

	    $xx = $this->GetX();
	    $this->Cell(90 - $xx + 10,4,"","R",0);
	    
	    $this->Ln();

	    $this->MultiCell(90,4, utf8_decode("Las tarifas podrán ajustarse anualmente al incremento el IPC."),'L R B', 'J', false);
	    $this->Ln();

	    $this->SetFillColor(208,206,206);
	    $this->Cell(75,6, "Valor Total:",'L T B',0, 'R');
	    $this->Cell(15,6, '$' . $data['valor_pagar'],'R T B',0,'L', true);

	    $this->Ln(8);
	    $this->MultiCell(90,6, "ASESOR COMERCIAL: " . utf8_decode($data['asesor_comercial']),1,'L', false);



	    #COLUMNA 2
	    $this->SetXY(105,5);

	    $this->SetFillColor(126,104,154);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('N° ') . $data['contrato'], 0,'L', true);
	    $this->ln(2);

	    $this->SetX(105,5);

	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, 'PRINCIPALES OBLIGACIONES DEL USUARIO', 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('1) Pagar oportunamente los servicios prestados, incluyendo los intereses de mora cuando haya incumplimiento; 2) suministrar información verdadera; 3) hacer uso adecuado de los equipos y los servicios; 4) no divulgar ni acceder a pornografía infantil; Según ley 679 de 2001; 5) avisar a las autoridades cualquier evento de robo o hurto de elementos de la red, como el cable; 6) No cometer o ser partícipe de actividades de fraude.'),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('CALIDAD Y COMPENSACIÓN'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Cuando se presente indisponibilidad del servicio o este se suspenda a pesar de su pago oportuno, lo compensaremos en su próxima factura. Debemos cumplir con las condiciones de calidad definidas por la CRC. Consúltelas en la página: https://www.amigored.com.co/indicadores.'),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('CESIÓN'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Si quiere ceder este contrato a otra persona, debe presentar una solicitud por escrito a través de nuestros Medios de Atención, acompañada de la aceptación por escrito de la persona a la que se hará la cesión. Dentro de los 15 días hábiles siguientes, analizaremos su solicitud y le daremos una respuesta. Si se acepta la cesión queda liberado de cualquier responsabilidad con nosotros.'),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('MODIFICACIÓN'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Nosotros no podemos modificar el contrato sin su autorización. Esto incluye que no podemos cobrarle servicios que no haya aceptado expresamente. Si esto ocurre tiene derecho a terminar el contrato, incluso estando vigente la cláusula de permanencia mínima, sin la obligación de pagar suma alguna por este concepto. No obstante, usted puede en cualquier momento modificar los servicios contratados. Dicha modificación se hará efectiva en el período de facturación siguiente, para lo cual deberá presentar la solicitud de modificación por lo menos con 3 días hábiles de anterioridad al corte de facturación.'),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('SUSPENSIÓN'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Usted tiene derecho a solicitar la suspensión del servicio por un máximo de 2 meses al año. Para esto debe presentar la solicitud antes del inicio del ciclo de facturación que desea suspender. Si existe una cláusula de permanencia mínima, su vigencia se prorrogará por el tiempo que dure la suspensión.'),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('TERMINACIÓN'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Usted puede terminar el contrato en cualquier momento sin penalidades. Para esto debe realizar una solicitud a través de cualquiera de nuestros Medios de Atención mínimo 3 días hábiles antes del corte de facturación (su corte de facturación es el día 25 de cada mes). Si presenta la solicitud con una anticipación menor, la terminación del servicio se dará en el siguiente periodo de facturación. Así mismo, usted puede cancelar cualquiera de los servicios contratados, para lo que  le informaremos las condiciones en las que serán prestados los servicios no cancelados y actualizaremos el contrato. Así mismo, si el operador no inicia la prestación del servicio en el plazo acordado, usted puede pedir la restitución de su dinero y la terminación del contrato.'),0, 'J', false);
	}

	function pagina2(){

		$data = $this->datos;

		//fondo 
      	$this->Image('img/fondo.png','0','0','205','347','PNG');

	    $this->SetXY(10,5);

	    #COLUMNA 1
	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('PAGO Y FACTURACIÓN'), 0,'L', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("La factura le debe llegar como mínimo 5 días hábiles antes de la fecha de pago. Si no llega, puede solicitarla a través de nuestros Medios de Atención y debe pagarla oportunamente.\nSi no paga a tiempo, previo aviso, suspenderemos su servicio hasta que pague sus saldos pendientes. Contamos con 3 días hábiles luego de su pago para reconectarle el servicio. Si no paga a tiempo, también podemos reportar su deuda a las centrales de riesgo. Para esto tenemos que avisarle por lo menos con 20 días calendario de anticipación. Si paga luego de este reporte tenemos la obligación dentro del mes de seguimiento de informar su pago para que ya no aparezca reportado.\nSi tiene un reclamo sobre su factura, puede presentarlo antes de la fecha de pago y en ese caso no debe pagar las sumas reclamadas hasta que resolvamos su solicitud. Si ya pagó, tiene 6 meses para presentar la reclamación."),0, 'J', false);

		if(!empty($data['firma'])){	    
	    	$this->Image("storage/".$data['firma'], 20,$this->GetY() + 5, 65 , 0,'JPG');
	    }	    

	    $this->Cell(90, 30, '', 'L T R', 0, 'C');
	    $this->Ln();



	    $this->SetFont('calibri','',8);
	    $this->Cell(90, 4, utf8_decode("Con esta firma acepta recibir la factura solamente por medios electrónicos"), 'L R B', 0, 'C');
	    $this->ln();
	    $this->Cell(90, 4, $data['tipo_documento'].': '. $data['identificacion'], 'L R B', 0, 'L');
	    $this->ln(6);

	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,5, utf8_decode("CÓMO COMUNICARSE CON NOSOTROS\n(MEDIOS DE ATENCIÓN)"), 0,'L', true);

	    $this->SetFont('calibri','',18);
	    $this->Cell(8, 12, '1', 0, 0,'C', true);

	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(27,27,27);
	    $this->MultiCell(82, 4, utf8_decode('Nuestros medios de atención son: Calle 35 # 17-77 Edificio Bancoquia Oficina 301 Barrio Centro, Bucaramanga - Santander, www.amigored.com.co, 018000945080.'), 'R B','J');

	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',18);
	    $this->Cell(8, 12, '2', 0, 0,'C', true);

	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(27,27,27);
	    $this->MultiCell(82, 4, utf8_decode('Presente cualquier queja, petición/reclamo o recurso a través de estos medios y le responderemos en máximo 15 días hábiles.'), 'R B','J');

	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',18);
	    $this->Cell(8, 12, '3', 0, 0,'C', true);

	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(27,27,27);
	    $this->MultiCell(82, 4, utf8_decode('Si no respondemos es porque aceptamos su petición o reclamo. Esto se llama silencio administrativo positivo y aplica para internet y telefonía.'), 'R B','J');

	    $this->MultiCell(90, 5, utf8_decode("Si no está de acuerdo con nuestra respuesta"), 0, 'C', false);

	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',18);
	    $this->Cell(8, 56, '4', 0, 0,'C', true);

	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(27,27,27);
	    $this->MultiCell(82, 4, utf8_decode("Cuando su queja o petición sea por los servicios de telefonía y/o internet, y esté relacionada con actos de negativa del contrato, suspensión del servicio, terminación del contrato, corte y facturación; usted puede insistir en su solicitud ante nosotros, dentro de los 10 días hábiles siguientes a la respuesta, y pedir que si no llegamos a una solución satisfactoria para usted, enviemos su reclamo directamente a la SIC (Superintendencia de Industria y Comercio) quien resolverá de manera definitiva su solicitud. Esto se llama recurso de reposición y en subsidio apelación.\nCuando su queja o petición sea por el servicio de televisión, puede enviar la misma a la Autoridad Nacional de Televisión informacion@antv.gov.co, para que esta Entidad resuelva su solicitud."), 'T R B','J');

	    $this->Ln();

	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->Cell(75, 8, utf8_decode('ACEPTO CLÁUSULA DE PERMANENCIA MÍNIMA'), 0, 0, 'L', true);
	    $this->Cell(8, 8, '', 1, 0, 'L', false);
	    $this->Cell(7, 8, '*', 0, 0, 'L', true);
	    $this->Ln();

	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(27,27,27);
	    $this->MultiCell(90,4, utf8_decode('En consideración a que le estamos otorgando un descuento respecto del valor del cargo por conexión, o le diferimos el pago del mismo, se incluye la presente cláusula de permanencia mínima. En la factura encontrará el valor a pagar si decide terminar el contrato anticipadamente.'),0, 'J', false);

	    $this->Ln();

	    
	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(255,255,255);
	    $this->Cell(70, 5, utf8_decode("Valor total del cargo por conexión"), 'B',0,'L', true);
	    $this->SetTextColor(27,27,27);
	    $this->Cell(20, 5, "$0.00", 'T R B', 0, 'L', false);
	    $this->Ln();

	    $this->SetTextColor(255,255,255);
	    $this->MultiCell(70, 4, utf8_decode("Suma que le fue descontada o diferida del valor total del cargo por conexión"), 'B','L', true);
	    $this->SetXY($this->GetX() + 70, $this->GetY() - 8);
	    $this->SetTextColor(27,27,27);
	    $this->Cell(20, 8, "$0.00", 'R B', 0, 'L', false);
	    $this->Ln();

	    $this->SetTextColor(255,255,255);
	    $this->Cell(70, 5, utf8_decode("Fecha de inicio de la permanencia mínima"), 'B',0,'L', true);
	    $this->SetTextColor(27,27,27);
	    $this->Cell(20, 5, "N/A", 'R B', 0, 'L', false);
	    $this->Ln();

	    $this->SetTextColor(255,255,255);
	    $this->Cell(70, 5, utf8_decode("Fecha de finalización de la permanencia mínima"), 'B',0,'L', true);
	    $this->SetTextColor(27,27,27);
	    $this->Cell(20, 5, "N/A", 'R B', 0, 'L', false);
	    $this->Ln(7);

	    $this->SetTextColor(255,255,255);
	    $this->MultiCell(90, 5, utf8_decode("Valor a pagar si termina el contrato anticipadamente según el mes"), 0, 'C', true);
	    $this->SetTextColor(27,27,27);
	    $this->Cell(15, 4, 'Mes 1', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 2', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 3', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 4', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 5', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 6', 'L R', 0, 'C', false);
	    $this->Ln();
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L R B', 0, 'C', false);
	    $this->Ln();
	    $this->Cell(15, 4, 'Mes 7', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 8', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 9', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 10', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 11', 'L', 0, 'C', false);
	    $this->Cell(15, 4, 'Mes 12', 'L R', 0, 'C', false);
	    $this->Ln();
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L B', 0, 'C', false);
	    $this->Cell(15, 4, '$0.00', 'L R B', 0, 'C', false);
	    $this->Ln();


	    #COLUMNA 2
	    $this->SetXY(105,5);

	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, 'CAMBIO DE DOMICILIO', 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Usted puede cambiar de domicilio y continuar con el servicio siempre que sea técnicamente posible. Si desde el punto de vista técnico no es viable el traslado del servicio, usted puede ceder su contrato a un tercero o terminarlo pagando el valor de la cláusula de permanencia mínima si está vigente.'),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('LARGA DISTANCIA (TELEFONÍA)'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Nos comprometemos a usar el operador de larga distancia que usted nos indique, para lo cual debe marcar el código de larga distancia del operador que elija.'),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,8, utf8_decode('COBRO POR RECONEXIÓN DEL SERVICIO'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('En caso de suspensión del servicio por mora en el pago, podremos cobrarle un valor por reconexión que corresponderá estrictamente a los costos asociados a la operación de reconexión. En caso de servicios empaquetados procede máximo un cobro de reconexión por cada tipo de conexión empleado en la prestación de los servicios. Costo reconexión: $' . $data['reconexion']),0, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('El usuario es el ÚNICO responsable por el contenido y la información que se curse a través de la red y del uso que se haga de los equipos o de los servicios.'), 0,'L', true);
	    $this->Ln();

	    $this->SetX(105);
	    $this->MultiCell(90,4, utf8_decode('Los equipos de comunicaciones que ya no use son desechos que no deben ser botados a la caneca, consulte nuestra política de recolección de aparatos en desuso.'), 0,'L', true);
	    $this->Ln();

	    $this->SetX(105);
	    $this->MultiCell(90,4, utf8_decode('CONDICIONES DEL SERVICIO'), 0,'L', true);

	    $this->SetX(105);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("1. Suspensión del contrato por No pago de 2 facturas consecutivas. El usuario pagará cargo de reconexión y los servicios que pueda usar durante la suspensión y terminación por: Vencimiento del plazo o de sus prorrogas; Incumplimiento de sus obligaciones; Fuerza mayor/caso fortuito; Uso inadecuado de la red o del servicio; por prevención de fraude; No viabilidad técnica o económica para prestar el servicio; Irregularidades en los documentos suministrados; o por evolución tecnológica.\n2.El usuario responde por los equipos entregados para prestación y operación del servicio y autoriza el cobro de su reposición por daño o perdida. Deberá entregarlos a la terminación del contrato del modo establecido en regulación, de no hacerlo pagará el valor comercial de los mismos. \n3. Las tarifas podrán incrementar por mes o año sin superar el 50%de la tarifa antes del incremento, más el índice de precios al consumidor del año anterior. Podrán modificarse excediendo dicho límite, y el usuario podrá terminar el contrato en los 30 días siguientes. \n4. El interés de mora es el máximo legal, se cobrarán los gastos de cobranza judicial y extrajudicial. Respondemos hasta 3 cargos mensuales anteriores al daño. No respondemos por lucro cesante, daños indirectos, incidentales o consecuenciales. \n5. Este contrato presta mérito ejecutivo para hacer exigibles las obligaciones y prestaciones contenidas en él. \n6. El cargo por cada solicitud de traslado de equipos a una nueva dirección según la tarifa vigente. Costo de traslado: $60.000 + IVA. \n7. El usuario deberá pagar el excedente por metro de cable adicional al autorizado para la instalación $1.800 + IVA por metro, al momento de realizar traslados solicitados. \n8. No podemos garantizar vía wifi, la velocidad contratada, toda vez que ésta depende de múltiples aspectos, que no son siempre directamente imputables al proveedor del servicio, por ejemplo: equipos, configuración, tarjetas de red, obstáculos físicos permanentes y/o transitorios, entre otros. En caso de que un cliente perciba que la velocidad no cumple sus expectativas, deberá comunicarse con las líneas de atención."),1, 'J', false);
	    $this->Ln();

	    $this->SetX(105);
		if(!empty($data['firma'])){	    
	    	$this->Image("storage/".$data['firma'], 105 + 15,$this->GetY() + 5, 65 , 0,'JPG');
	    }	    
	    $this->Cell(90, 30, '', 'L T R', 0, 'C');
	    $this->Ln();

	    $this->SetX(105);
	    $this->SetFont('calibri','',8);
	    $this->Cell(90, 4, utf8_decode("Aceptación contrato mediante firma o cualquier otro medio válido"), 'L R B', 0, 'C');
	    $this->ln();

	    $this->SetX(105);
	    $this->Cell(45, 4, $data['tipo_documento'].': ' . $data['identificacion'], 'L B', 0, 'L');
	    $this->Cell(45, 4, 'FECHA: ' . $data['fecha_contrato'], 'L R B', 0, 'L');
	    $this->ln(6);

	    $this->SetX(105);
	    $this->SetFont('calibri','',8.5);
	    $this->MultiCell(90,4, utf8_decode("Consulte el régimen de protección de usuarios en www.crcom.gov.co"), 0,'L', false);
	}

	function informacion(){

		$data = $this->datos;

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->Cell(0,7,utf8_decode('FORMATO DE AUTORIZACIÓN DE TRATAMIENTO PROTECCIÓN DE DATOS PERSONALES'),0,0,'C');
        $this->Ln();
        $this->Ln();

        $this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode('Yo '. $data["nombre_suscriptor"].', identificado con la C.C. '.$data["identificacion"] .', de '.$data["documento_expedicion"].', con domicilio en la '.$data["direccion"].' de la ciudad de '.$data["municipio"] .' ('. $data["departamento"].'), dando cumplimiento a lo dispuesto en la Ley 1581 de 2012, "Por el cual se dictan disposiciones generales para la protección de datos personales" y de conformidad con lo señalado en el Decreto 1377 de 2013, manifiesto de forma libre y espontánea que:'),0,'J',false);
        $this->Ln(2);


        $this->MultiCell(0,5,utf8_decode('1. Con ocasión de la suscripción el presente contrato y por ser beneficiario del proyecto de Incentivos a la Oferta de Internet Fijo, implementado por el Fondo Único de Tecnologías de la Información y las Comunicaciones, acepto de forma libre y espontánea que los datos recolectados sean conocidos y compartidos a la firma interventora del contrato suscrito por la empresa Sistemas y Telecomunicaciones del Oriente S.A.S. y al Fondo Único de Tecnologías de la Información y las Comunicaciones.'),0,'J',false);

        

        $this->MultiCell(0,5,utf8_decode('2. Con la firma de este documento manifiesto que he sido informado por la empresa Sistemas y Telecomunicaciones del Oriente S.A.S. quien actuará como responsable del Tratamiento de datos personales de los cuales soy titular y que, conjunta o separadamente podrá recolectar, usar y tratar mis datos personales conforme a la Política de Privacidad y Tratamiento de Datos Personales, disponible en SGC, en página web de la entidad.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('3. Que me ha sido informada la (s) finalidad (es) de la recolección de los datos personales, la cual se encuentra expuesta públicamente en cartelera, página web, medios plegables de la factura y contrato de servicio'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('4. Es de carácter facultativo o voluntario responder preguntas que versen sobre Datos Sensibles o sobre menores de edad.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('5. Mis derechos como titular de los datos son los previstos en la Constitución y la ley, especialmente el derecho a conocer, actualizar, rectificar y suprimir mi información personal, así como el derecho a revocar el consentimiento otorgado para el tratamiento de datos personales.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('6. Los derechos pueden ser ejercidos a través de los canales dispuestos por Sistemas y Telecomunicaciones del Oriente S.A.S. y observando la Política de Privacidad y Tratamiento de Datos Personales de Sistemas y Telecomunicaciones del Oriente S.A.S.'),0,'J',false);
         $this->MultiCell(0,5,utf8_decode('7. Mediante la página web de la entidad (https://www.amigored.com.co/), podré radicar cualquier tipo de requerimiento relacionado con el tratamiento de mis datos personales.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('8 Sistemas y Telecomunicaciones del Oriente S.A.S. garantizará la confidencialidad, libertad, seguridad, veracidad, transparencia, acceso y circulación restringida de mis datos y se reservará el derecho de modificar su Política de Privacidad y Tratamiento de Datos Personales en cualquier momento. Cualquier cambio será informado y publicado oportunamente en la página web.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('9. Teniendo en cuenta lo anterior, autorizo de manera voluntaria, previa, explícita, informada e inequívoca a la empresa Sistemas y Telecomunicaciones del Oriente S.A.S. para tratar mis datos personales, tomar mi huella y fotografía de acuerdo con su Política de Privacidad y Tratamiento de Datos Personales para los fines relacionados con su objeto y en especial para fines legales, contractuales, misionales descritos en la Política de Privacidad y Tratamiento de Datos Personales.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('10. La información obtenida para el Tratamiento de mis datos personales la he suministrado de forma voluntaria y es verídica.'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('Se firma en la ciudad de '.$data["municipio"].' ('.$data["departamento"].') a los '. date("d", strtotime($data["fecha_contrato"])).' días del mes de '. strftime("%B", strtotime($data["fecha_contrato"])).' del año '.date("Y", strtotime($data["fecha_contrato"]))),0,'J',false);

        $this->Ln(5);
	        
	}

	function inf2(){

		$data = $this->datos;

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('futura-bdcn-bt-bold','',10);

	    $this->Cell(0,7,utf8_decode('AUTORIZACIÓN PARA CONSULTA Y REPORTE A CENTRALES DE INFORMACIÓN COMERCIAL SOBRE EL CLIENTE'),0,0,'C');
	    $this->Ln();
	    $this->Ln();

	    $this->SetTextColor(70,70,70);
	    $this->SetFont('calibri','',9.5);
	    $this->MultiCell(0,5,utf8_decode('Yo '. $data["nombre_suscriptor"].', identificado con la C.C. '.$data["identificacion"].', autorizo de manera irrevocable a Sistemas y Telecomunicaciones del Oriente S.A.S, o a quien sea en un futuro acreedor de esta obligación, para:'),0,'J',false);
	    $this->Ln(5);

	    $this->MultiCell(0,5,utf8_decode('a. Consultar, en cualquier tiempo, en las centrales de riesgo toda la información relevante para conocer mi desempeño como deudor, mi capacidad de pago o para valorar el riesgo futuro de concederme crédito.'),0,'J',false);
	    $this->MultiCell(0,5,utf8_decode('b. Reportar a las centrales de información de riesgo, que administren base de datos, la información sobre el comportamiento de las obligaciones de contenido patrimonial que adquiera para con Sistemas y Telecomunicaciones del Oriente S.A.S. cualquier título y las facture por parte de Sistemas y Telecomunicaciones del Oriente S.A.S. en relación con los servicios que está a los terceros con quienes han celebrado convenios de facturación, me hayan prestado, de tal forma que estas presenten una información veraz, pertinente, completa, actualizada y exacta, de mi desempeño como deudor después de haber cruzado y procesado diversos datos útiles para obtener una información significativa.'),0,'J',false);
	    $this->MultiCell(0,5,utf8_decode('c. Enviar la información mencionada a las centrales de riesgo de manera directa y, también, por intermedio de la superintendencia Bancaria o las demás entidades públicas que ejercen funciones de vigilancia y control, con el fin de que éstas puedan tratarla, analizarla, clasificarla y luego suministrarla a dichas centrales.'),0,'J',false);
	    $this->MultiCell(0,5,utf8_decode('d. Conservar la información reportada, en la base de datos de las centrales de riesgo, con las debidas actualizaciones y durante el periodo necesario señalado en sus reglamentos.'),0,'J',false);
	    
	    $this->Ln();

	    $this->MultiCell(0,5,utf8_decode('Declaro haber leído cuidadosamente el contenido de esta cláusula y haberla comprendido a cabalidad, razón por la cual entiendo sus alcances y sus implicaciones.'),0,'J',false);

	    $this->Ln(5);
	    
	}

	function inf3(){

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->MultiCell(0,7,utf8_decode('ANEXO 1 AL CONTRATO DE PRESTACIÓN DE SERVICIOS DE TELECOMUNICACIONES, PARA PREVENIR Y CONTRARRESTAR LA EXPLOTACIÓN Y LA PORNOGRAFÍA INFANTIL.'),0,'C',false);

        $this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',9);
        $this->MultiCell(0,5,utf8_decode('Las partes se comprometen de manera expresa y suscriben el presente documento en constancia, a dar cumplimiento a todas las disposiciones legales y reglamentarias sobre el adecuado uso de la red, y la prevención de acceso a páginas de contenido restringido, toda forma de explotación pornográfica, turismo sexual y demás formas de abuso de menores según lo previsto en la Ley 679 de 2001 y sus decretos reglamentarios. Así mismo se comprometen a implementar todas las medidas de tipo técnico que considere necesarias para prevenir dichas conductas.'),0,'J',false);
        $this->Ln(2);

        $this->MultiCell(0,5,utf8_decode('PROHIBICIONES. Los proveedores o servidores, administradores y usuarios de redes globales de información no podrán:'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('1. Alojar en su propio sitio imágenes, textos, documentos o archivos audiovisuales que impliquen directa o indirectamente actividades sexuales con menores de edad. '),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('2. Alojar en su propio sitio material pornográfico, en especial en modo de imágenes o videos, cuando existan indicios de que las personas fotografiadas o filmadas son menores de edad.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('3. Alojar en su propio sitio vínculos o "links", sobre sitios telemáticos que contengan o distribuyan material pornográfico relativo a menores de edad.'),0,'J',false);
        
        $this->Ln(2);

        $this->MultiCell(0,5,utf8_decode('DEBERES. Sin perjuicio de la obligación de denuncia consagrada en la ley para todos los residentes en Colombia, los proveedores,administradores y usuarios de redes globales de información deberán:'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('1. Denunciar ante las autoridades competentes cualquier acto criminal contra menores de edad de que tengan conocimiento, incluso de la difusión de material pornográfico asociado a menores.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('2. Combatir con todos los medios técnicos a su alcance la difusión de material pornográfico con menores de edad. '),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('3. Abstenerse de usar las redes globales de información para divulgación de material ilegal con menores de edad. '),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('4. Establecer mecanismos técnicos de bloqueo por medio de los cuales los usuarios se puedan proteger a sí mismos o a sus hijos de material ilegal, ofensivo o indeseable en relación con menores de edad. Se prohíbe expresamente el alojamiento de contenidos de pornografía infantil.'),0,'J',false);
        
        $this->Ln(1);

        $this->MultiCell(0,5,utf8_decode('SANCIONES ADMINISTRATIVAS: Los proveedores o servidores, administradores y usuarios que no cumplan o infrinjan lo establecido en el presente capítulo, serán sancionados por el Ministerio de Tecnologías de la Información y las Comunicacione sucesivamente de la siguiente manera:'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('1. Multas hasta de cien (100) salarios mínimos legales mensuales vigentes, que serán pagadas al Fondo Contra la Explotación Sexual de Menores, de que trata el artículo 24 de la Ley 679 de 2001.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('2. Suspensión de la correspondiente página electrónica. '),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('3. Cancelación de la correspondiente página electrónica. Para la imposición de estas sanciones se aplicará el procedimiento establecido en el Código de Procedimiento Administrativo y de lo Contencioso Administrativo, con observancia del debido proceso y criterios de adecuación, proporcionalidad y reincidencia. Parágrafo. El Ministerio de Tecnologías de la Información y las Comunicaciones adelantará las investigaciones administrativas pertinentes e impondrá, si fuere el caso, las sanciones previstas en este Título, sin perjuicio de las investigaciones penales que adelanten las autoridades competentes y de las sanciones a que ello diere lugar.'),0,'J',false);
        
        $this->Ln(1);
	        
	}

	    //---------------------- PAGINA 4 -----------------------------
	function inf4(){
       
		$data = $this->datos;

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->Cell(0,7,utf8_decode('ANEXO 2 CONDICIONES DE LA PRESTACIÓN DEL SERVICIO'),0,0,'C');
        $this->Ln();

        $this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',9);
        $this->MultiCell(0,5,utf8_decode('Conforme lo establecido en el contrato suscrito entre el Fondo Único de Tecnologías de la Información y las Comunicaciones y Sistemas y Telecomunicaciones del Oriente S.A.S, a continuación, le informamos:'),0,'J',false);
        $this->Ln(2);

        $this->MultiCell(0,5,utf8_decode('1. Que el proyecto de incentivos a la Oferta de Internet Fijo es financiado por el Fondo Único de Tecnologías de la Información y las Comunicaciones.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode("2. El usuario deberá pagar, de manera anticipada, la tarifa social mensual establecida, según el estrato correspondiente, a lo largo de la operación, es decir, dentro de los próximos ".$data['vigencia']." meses, sin que esto implique la existencia de cláusula de permanencia alguna.\nLas tarifas sociales establecidas para el pago mensual del servicio de Internet, según las condiciones técnicas y financieras establecidas en el proyecto, corresponden a $8.613 pesos para estrato 1 y $19.074 pesos para estrato 2 y para el caso de los inscritos al SISBEN IV y beneficiarios de la Ley 1699 de 2013 deberán oscilar en el rango señalado para los estratos 1 y 2 por una velocidad de Internet 5 Mbps Downstream /1 Mbps Upstream. El servicio de instalación no tiene costo alguno."),0,'J',false);       
        

        $this->MultiCell(0,5,utf8_decode('3. Las características del plan de Internet fijo, corresponden a: Parámetros de la velocidad de 5 Mbps Downstream /1 Mbps Upstream'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('4. Los datos de contacto de la mesa de ayuda son: '),0,'J',false);

        $this->setX(20);

        $this->Cell(0,5,utf8_decode('Línea de atención: : (607)6335080'),0,0,'L');
        $this->Ln();
        $this->Cell(0,5,utf8_decode('Línea gratuita: 018000945080 - 018000942422'),0,0,'L');
        $this->Ln();
        $this->Cell(0,5,utf8_decode('Oficina Principal:'),0,0,'L');
        $this->Ln();
        $this->Cell(0,5,utf8_decode('calle 35 # 17-77 Centro Edificio Bancoquia.'),0,0,'L');
        $this->Ln();
        $this->Cell(0,5,utf8_decode('Bucaramanga, Santander, Colombia'),0,0,'L');
        $this->Ln();

        $this->MultiCell(0,5,utf8_decode('5. Los puntos de pago autorizados: '),0,'J',false);

        $this->Cell(0,5,'-' . utf8_decode(' Botón PSE, desde cualquier entidad bancaria, a través de nuestra página de internet www.amigored.com.co/puntos-depago/'),0,'L');
        $this->Ln();
        $this->Cell(0,5,'-' . utf8_decode(' Puntos de atención de Efecty'),0,0,'L');
        $this->Ln();
        $this->Cell(0,5,'-' . utf8_decode(' Puntos de atención de Baloto'),0,0,'L');
        $this->Ln();
        

        $this->MultiCell(0,5,utf8_decode('6. Modalidad de facturación: digital y/o física, conforme lo autorice la Ley y el usuario.'),0,'J',false);
        $this->MultiCell(0,5,utf8_decode('7. Cargo que deberá asumir el usuario en caso de requerir una reinstalación del servicio por cambio de predio $60.000 + IVA'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('8. Cargo que deberá asumir el usuario en caso de requerir reposición de un equipo por daños $135.300 + IVA'),0,'J',false);

        $this->Ln();

        $this->MultiCell(0,5,utf8_decode('Se firma en constancia de recibido, a los '. date("d", strtotime($data['fecha_contrato'])).' días del mes de '.strftime("%B", strtotime($data['fecha_contrato'])).' del año '.date("Y", strtotime( $data['fecha_contrato']))),0,'J',false);        
	}

	function firmas(){

	    $data = $this->datos;

        $this->SetTextColor(70,70,70);

        $this->Cell(0,5,'Acepto:',0,0);

        //Firma Beneficiario
		if(!empty($data['firma'])){	    
	    	$this->Image('storage/'.$data['firma'], 30,null, 65 , 0,'JPG');
	    }
        
        $this->Ln(2);

        $this->Cell(20,5,'NOMBRE:',0,0);
        $this->Cell(60,5,utf8_decode($data['nombre_suscriptor']),'T',0,'L');
        $this->Ln();
        $this->Cell(20,5,'CC/CE:',0,0);
        $this->Cell(60,5,utf8_decode($data['identificacion']),0,0,'L');
        $this->Ln();

        if (!empty($data['fecha_contrato'])) {
            $this->Cell(20,5,'FECHA:',0,0);
            $this->Cell(60,5,date("Y/m/d", strtotime($data['fecha_contrato'])),0,0,'L');
            $this->Ln();   
          }  

        if (!empty($data['direccion'])) {
             
            $this->Cell(20,5,'DIRECCION:',0,0);
            $this->Cell(60,5,utf8_decode($data['direccion']),0,0,'L');
            $this->Ln();
            $this->Cell(20,5,'MUNICIPIO:',0,0);
            $this->Cell(60,5,utf8_decode($data['municipio'] . ' (' . $data['departamento'] . ')'),0,0,'L');
            $this->Ln();
            $this->Cell(35,5,'CORREO ELECTRONICO:',0,0);
            $this->Cell(60,5,utf8_decode($data['correo']),0,0,'L');
            $this->Ln();
        }

        $this->Ln();
	}

	function firmas_2(){
		$data = $this->datos;
		
		$this->ln();
	    
		if(!empty($data['firma'])){	    
	    	$this->Image("storage/".$data['firma'], 15,$this->GetY() + 5, 65 , 0,'JPG');
	    }
		
	    $this->Cell(90, 30, '', 'L T R', 0, 'C');
	    $this->Ln();

	    $this->SetFont('calibri','',8);
	    $this->Cell(90, 4, utf8_decode("FIRMA"), 'L R B', 0, 'C');
	    $this->ln();

	    $this->Cell(45, 4, utf8_decode($data['tipo_documento']).': ' . $data['identificacion'], 'L B', 0, 'L');
	    $this->Cell(45, 4, 'FECHA: ' . $data['fecha_contrato'], 'L R B', 0, 'L');
	    $this->ln(6);
	}

	function pie(){
	    $this->SetY(-23);
	    
	    $this->SetTextColor(70,70,70);
	    $this->SetFont('calibri','',8);
	    $y = $this->GetY();
	    $this->SetDrawColor(101,108,122);
	    $this->Line(15,$y - 5,195,$y - 5);

	    $this->Cell(0,5,utf8_decode('Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 - 302 PBX: (607) 6334050 '), 0,0,'C');
	    $this->Ln();
	    $this->Cell(0,5,utf8_decode('Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co.'), 0,0,'C');
	    $this->Ln();
	    $this->Cell(0,5,utf8_decode('GC F 004 Versión 04 11 Feb 2020'), 0,0,'C');
	    $this->Ln();        

	    $this->Image('img/logo_mastic.png', 15 ,$y - 1, 26, 0,'PNG');
	} 

	  /*function Footer()
	  {
	    $this->SetY(-10);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('futura-bdcn-bt-bold','',8);
	    $this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
	  }  */
}
?>