<?php 

namespace App\Custom\PlantillasContratos;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;


class AmigoRed extends Fpdf
{
	public $datos;

	function logos(){
    $this->Image('img/amigored1.png' , 10 ,5, 0 , 20 ,'png');
        
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
	    $this->MultiCell(90,8, utf8_decode("CONTRATO ÚNICO\nDE SERVICIOS FIJOS"),0, 'R', true);
	    $this->Image('img/amigored1.png', 10 ,5.5, 26 , 0,'PNG');
	    $this->Ln(2);

		$this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("Este contrato explica las condiciones para la prestación de los servicios entre usted y SISTEMAS Y TELECOMUNICACIONES DEL ORIENTE S.A.S identificada con NIT 804.003.326-6 operador de la marca Amigo Red, por el que pagará mínimo mensualmente $" . $data['valor_pagar']." por ". $data['cantidad_megas'] ." Megas.\nEste contrato tendrá vigencia de " .$data['vigencia']. " meses, contados a partir del ". $data['fecha_contrato'] ." El plazo máximo de instalación es de 15 días hábiles.\nAcepto que mi contrato se renueve  sucesiva y automáticamente"),0, 'J', false);
	    $this->Cell(36,4, 'por un plazo igual al inicial', 0, 0 , 'L', false);

	    $x = $this->GetX();
		//$this->SetTextColor(27,27,27);
	    $this->Cell(4,4, 'SI', 1, 0 , 'L', false);
		//$this->SetTextColor(255,255,255);
	    $this->Cell(50,4, '*', 0, 0 , 'L', false);
	    
	    //Equis
	    $y = $this->GetY();
	    /*$this->Line($x,$y,($x + 4),($y + 4));
	    $this->Line($x,($y + 4),($x + 4),$y);*/
	    $this->Ln(7);

		$this->SetTextColor(255,255,255);
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

	    $this->MultiCell(90,4, utf8_decode("Servicios adicionales ____________NO______________ Usted se compromete a pagar oportunamente el precio acordado.\nEl servicio se activará a más tardar el día ___/___ /___. "),0, 'J', false);

	    $this->MultiCell(90,4, utf8_decode("Tipo de Facturación: " . $data['tipo_cobro']),0, 'J', false);


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
	    $this->MultiCell(90,4, utf8_decode($data['condiciones_plan']),'L R', 'J', false);

	    $this->SetTextColor(255,255,255);
	    $this->SetFont('futura-bdcn-bt-bold','',10);
	    $this->MultiCell(90,6, utf8_decode("OTROS COSTOS"), 'L R', 'C', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('futura-bdcn-bt-bold','',9);
	    $this->Cell(75,5,"CONCEPTO", 'L',0, 'C');
	    $this->Cell(15,5,"VALOR", 'L R',0, 'C');
	    $this->Ln();

	    $this->SetFont('calibri','',9);

	    foreach ($data['costos'] as $costo) {
		    $descripcion = "";

		    $iva = "";

		    if (!empty($costo->descripcion)) {
		    	$descripcion = " (".$costo->descripcion.")";
		    }

		    if ($costo->iva == "SI") {
		    	$iva = "*";
		    }

		    $y = $this->GetY();

		    $this->MultiCell(75,4,utf8_decode($costo->concepto . $descripcion . $iva), 'L T', 'L', false);

		    $alto = $this->GetY() - $y;

			$this->SetXY(85,$y);

		    $this->Cell(15,$alto, "$".number_format($costo->valor, 0, ',','.') , 'L T R',0, 'R');
		    $this->Ln();

		    //$this->SetY($y);
		    //$this->MultiCell(15,5,"$".number_format($costo->valor, 0, ',','.') , 'L T R', 'R', false);

		    

		    //$this->Cell(75,5,utf8_decode($costo->concepto . $descripcion . $iva), 'L T',0, 'L');
		    //$this->Cell(15,5,"$".number_format($costo->valor, 0, ',','.') , 'L T R',0, 'R');
		    //$this->Ln();

		}

		$this->SetFillColor(208,206,206);
		$this->MultiCell(90,4, utf8_decode("* Más IVA."), 1, 'L', true);


		/*
		$this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
        $this->SetFont('futura-bdcn-bt-bold','',10);
	    $this->MultiCell(90,6, utf8_decode("SERVICIOS ADICIONALES"), 'L R', 'C', true);

	    
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('futura-bdcn-bt-bold','',9);
	    $this->Cell(75,5,"NOMBRE", 'L T',0, 'C');
	    $this->Cell(15,5,"VALOR", 'L T R',0, 'C');
	    $this->Ln();

	    $this->SetFont('calibri','',9);
	    $this->Cell(75,5,"", 'L T',0, 'C');
	    $this->Cell(15,5,"", 'L T R',0, 'C');
	    $this->Ln();    

	    
	    $this->SetFillColor(208,206,206);
	    $this->Cell(75,6, "Valor Total:",'L T B',0, 'R');
	    $this->Cell(15,6, '$' . $data['valor_pagar'],'R T B',0,'L', true);
	    $this->ln();

	    */
		

	    /*$this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('futura-bdcn-bt-bold','',10);
	    $this->MultiCell(90,6, utf8_decode("EQUIPOS ENTREGADOS"), 'L R', 'C', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('futura-bdcn-bt-bold','',9);
	    $this->Cell(15,5,"TIPO", 'L',0, 'C');
	    $this->Cell(60,5,"SERIAL", 'L',0, 'C');
	    $this->Cell(15,5,"CANT.", 'L R',0, 'C');
	    $this->Ln();

	    $this->SetFont('calibri','',9);
	    $this->Cell(15,5,"", 'L T',0, 'C');
	    $this->Cell(60,5,"", 'L T',0, 'C');
	    $this->Cell(15,5,"", 'L T R',0, 'C');
	    $this->Ln();

	    $this->Cell(15,5,"", 'L T B',0, 'C');
	    $this->Cell(60,5,"", 'L T B',0, 'C');
	    $this->Cell(15,5,"", 'L T R B',0, 'C');
	    $this->Ln(7);*/


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
	    $this->MultiCell(90,4, utf8_decode("1) Pagar oportunamente los servicios prestados, incluyendo los intereses de mora, reconexión cuando haya incumplimiento;\n 2) suministrar información verdadera;\n 3) hacer uso adecuado de los equipos y los servicios;\n 4) no divulgar ni acceder a pornografía infantil; Según ley 679 de 2001 - ley 1098 de 2006;\n 5) avisar a las autoridades cualquier evento de robo o hurto de elementos de la red, como el cable;\n 6) No cometer o ser partícipe de actividades de fraude."),0, 'J', false);
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

	    $this->SetX(105,5);	

	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->Cell(75, 7, utf8_decode('ACEPTO CLÁUSULA DE PERMANENCIA MÍNIMA'), 0, 0, 'L', true);
	    $x = $this->GetX();
		$this->SetTextColor(27,27,27);
	    $this->Cell(7, 7, 'SI', 1, 0, 'C', false);
		$this->SetTextColor(255,255,255);
	    $this->Cell(8, 7, '*', 0, 0, 'L', true);

	    //Equis
	    $y = $this->GetY();

	    /*$this->Line($x,$y,($x + 7),($y + 7));
	    $this->Line($x,($y + 7),($x + 7),$y);*/
	    $this->Ln();

	    $this->SetX(105,5);

	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(27,27,27);
	    $this->MultiCell(90,4, utf8_decode('En consideración a que le estamos otorgando un descuento respecto del valor del cargo por conexión, o le diferimos el pago del mismo, se incluye la presente cláusula de permanencia mínima. En la factura encontrará el valor a pagar si decide terminar el contrato anticipadamente.'),0, 'J', false);

	    $this->Ln(2);

	    $this->SetX(105,5);	    
	    $this->SetFont('futura-bdcn-bt-bold','',10);
	    $this->MultiCell(90,7, 'CLAUSULA DE PERMANENCIA INTERNET','L T R','C');

	    $this->SetFont('calibri','',9);

	    $this->SetX(105,5);
	    $this->Cell(70, 5, utf8_decode("Valor total del cargo por conexión"), 'L T ',0,'L');	    
	    $this->Cell(20, 5, "$0.00", 'L T R', 0, 'L');
	    $this->Ln();

	    $this->SetX(105,5);
	    $this->MultiCell(70, 4, utf8_decode("Suma que le fue descontada o diferida del valor total del cargo por conexión"), 'L T R','L');
	    $this->SetX(105,5);
	    $this->SetXY($this->GetX() + 70, $this->GetY() - 8);
	    $this->Cell(20, 8, "$0.00", 'L T R', 0, 'L');
	    $this->Ln();

	    $this->SetX(105,5);
	    $this->Cell(70, 5, utf8_decode("Fecha de inicio de la permanencia mínima"), 'L T R',0,'L');
	    $this->Cell(20, 5, "N/A", 'L T R', 0, 'L');
	    $this->Ln();

	    $this->SetX(105,5);
	    $this->Cell(70, 5, utf8_decode("Fecha de finalización de la permanencia mínima"), 'L T R B',0,'L');
	    $this->Cell(20, 5, "N/A", 'L T R B', 0, 'L');
	    $this->Ln(7);

	    $this->SetX(105,5);
	    $this->SetTextColor(255,255,255);
	    $this->MultiCell(90, 5, utf8_decode("Valor a pagar si termina el contrato anticipadamente según el mes"), 0, 'C', true);

	    $this->SetX(105,5);
	    $this->SetTextColor(27,27,27);

	    #titulos
	    $i = 1;

	    if(count($data['clausulas_permanencia']) > 0){

		    $columnas = count($data['clausulas_permanencia']);
		    $ancho_celda = 90 / ($columnas/2);

		    $valores_clausulas = array();
		    foreach ($data['clausulas_permanencia'] as $clausula) {



		    	$this->Cell($ancho_celda, 4, "Mes " .$clausula->numero_mes, ($i == 1) ? 'L R' : 'R', 0, 'C', false);
		    	$valores_clausulas[] = $clausula->valor;

		    	if ($i == ($columnas/2)){
		    		$this->Ln();
		    		$this->SetX(105,5);

		    		for ($j=0; $j < count($valores_clausulas); $j++) { 
		    			$this->Cell($ancho_celda, 4, '$'.number_format($valores_clausulas[$j], 0,'.',','), ($j == 0) ? 'L R B' : 'R B', 0, 'C', false);
		    		}

		    		$this->Ln();
		    		$this->SetX(105,5);

		    		$valores_clausulas = array();

		    		$i = 0;
		    	}

		    	$i+=1;
		    }

		}

	    $this->Ln();
	    

	    
	    
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
	    $this->MultiCell(90,8, utf8_decode('TERMINACIÓN'), 0,'L', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode("Usted puede terminar el contrato en cualquier momento sin penalidades. Para esto debe realizar una solicitud a través de cualquiera de nuestros Medios de Atención mínimo 3 días hábiles antes del corte de facturación (su corte de facturación es el día ".$data['dia_corte_facturacion']." de cada mes). Si presenta la solicitud con una anticipación menor, la terminación del servicio se dará en el siguiente periodo de facturación. Así mismo, usted puede cancelar cualquiera de los servicios contratados, para lo que  le informaremos las condiciones en las que serán prestados los servicios no cancelados y actualizaremos el contrato. Así mismo, si el operador no inicia la prestación del servicio en el plazo acordado, usted puede pedir la restitución de su dinero y la terminación del contrato."),0, 'J', false);



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
	    $this->Cell(90, 4, utf8_decode($data['tipo_documento']).': '. $data['identificacion'], 'L R B', 0, 'L');
	    $this->ln(6);

	    $this->SetFillColor(85,64,111);
	    $this->SetTextColor(255,255,255);
	    $this->SetFont('calibri','',10);
	    $this->MultiCell(90,5, utf8_decode("CÓMO COMUNICARSE CON NOSOTROS\n(MEDIOS DE ATENCIÓN)"), 0,'L', true);

	    $this->SetFont('calibri','',18);
	    $this->Cell(8, 24, '1', 0, 0,'C', true);

	    $this->SetFont('calibri','',9);
	    $this->SetTextColor(27,27,27);
	    $this->MultiCell(82, 4, utf8_decode('Nuestros medios de atención son: Calle 35 # 17-77 Edificio Bancoquia Oficina 301 Barrio Centro, Bucaramanga - Santander, www.amigored.com.co, Facebook: @AmigoRedOficial, servicioalcliente@amigored.com.co, 018000945080 - (607)6335080 - 018000942422 - (607)6334050 - 3187934119 .'), 'R B','J');

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
	    $this->MultiCell(90,8, 'CAMBIO DE DOMICILIO', 0,'L', true);

	    $this->SetTextColor(27,27,27);
	    $this->SetFont('calibri','',9);
	    $this->MultiCell(90,4, utf8_decode('Usted puede cambiar de domicilio y continuar con el servicio siempre que sea técnicamente posible. Si desde el punto de vista técnico no es viable el traslado del servicio, usted puede ceder su contrato a un tercero o terminarlo pagando el valor de la cláusula de permanencia mínima si está vigente.'),0, 'J', false);
	    $this->Ln();



	    #COLUMNA 2

	    $this->SetXY(105,5);
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
	    $this->MultiCell(90,4, utf8_decode('En caso de suspensión del servicio por mora en el pago, podremos cobrarle un valor por reconexión que corresponderá estrictamente a los costos asociados a la operación de reconexión. En caso de servicios empaquetados procede máximo un cobro de reconexión por cada tipo de conexión empleado en la prestación de los servicios. Costo reconexión: $'. $data['reconexion']) ,0, 'J', false);
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
	    $this->MultiCell(90,4, utf8_decode($data['condiciones_servicio']),1, 'J', false);
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
	    $this->Cell(45, 4, utf8_decode($data['tipo_documento']).': ' . $data['identificacion'], 'L B', 0, 'L');
	    $this->Cell(45, 4, 'FECHA: ' . $data['fecha_contrato'], 'L R B', 0, 'L');
	    $this->ln(6);

	    $this->SetX(105);
	    $this->SetFont('calibri','',8.5);
	    $this->MultiCell(90,4, utf8_decode("Consulte el régimen de protección de usuarios en www.crcom.gov.co"), 0,'L', false);

	    $this->Ln(8);
	    $this->SetX(105);
	    $this->MultiCell(90,6, "ASESOR COMERCIAL: " . utf8_decode($data['asesor_comercial']),1,'L', false);
	}

	function tratamiento_datos_personales(){

		$data = $this->datos;

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->Cell(0,7,utf8_decode('FORMATO DE AUTORIZACIÓN DE TRATAMIENTO PROTECCIÓN DE DATOS PERSONALES'),0,0,'C');
        $this->Ln();
        $this->Ln();

        $this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode('Yo '. $data["nombre_suscriptor"].', identificado con ' . $data['tipo_documento'] . ' ' .$data["identificacion"] .', de '.$data["documento_expedicion"].', con domicilio en la '.$data["direccion"].' de la ciudad de '.$data["municipio"] .' ('. $data["departamento"].'), dando cumplimiento a lo dispuesto en la Ley 1581 de 2012, "Por el cual se dictan disposiciones generales para la protección de datos personales" y de conformidad con lo señalado en el Decreto 1377 de 2013, manifiesto de forma libre y espontánea que:'),0,'J',false);
        $this->Ln(2);


        $this->MultiCell(0,5,utf8_decode('1. Autorizo a Sistemas y Telecomunicaciones del Oriente S.A.S. (SISTECO S.A.S.) para recolectar, almacenar, conservar, usar, suprimir, actualizar, compartir y circular mis datos personales, incluyendo datos demográficos, económicos, biométricos, de servicios, comerciales y de localización, con fines de cumplimiento de obligaciones contractuales, legales, comerciales o publicitarios, tanto para beneficio propio como de terceros con los que SISTECO S.A.S. haya celebrado convenios y/o contratos, o de aquellos que, en virtud de dichos convenios o contratos, actúan como interventores, auditores u otros colaboradores. Estos datos serán utilizados de conformidad con las Políticas de Tratamiento de Datos Personales disponibles en www.sisteco.com.co. Como titular, tengo derecho a conocer, actualizar, rectificar, suprimir los datos y revocar la autorización, salvo excepciones legales. Los datos biométricos, considerados sensibles, serán usados exclusivamente para la verificación de identidad y suscripción de contratos, y su tratamiento es opcional.'),0,'J',false);        

        $this->MultiCell(0,5,utf8_decode('2. Con la firma de este documento manifiesto que he sido informado por la empresa Sistemas y Telecomunicaciones del Oriente S.A.S. quien actuará como responsable del Tratamiento de datos personales de los cuales soy titular y que, conjunta o separadamente podrá recolectar, usar y tratar mis datos personales conforme a la Política de Privacidad y Tratamiento de Datos Personales, disponible en SGC, en página web de la entidad.'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('3. Que me ha sido informada la (s) finalidad (es) de la recolección de los datos personales de acuerdo a la política de datos que se encuentra expuesta públicamente en la página web https://sisteco.com.co y https://www.amigored.com.co'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('4. Es de carácter facultativo o voluntario responder preguntas que versen sobre Datos Sensibles o sobre menores de edad.'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('5. Mis derechos como titular de datos, previstos en la Constitución y la ley, incluyen el acceso, actualización, rectificación y supresión de mi información personal, así como la posibilidad de revocar el consentimiento otorgado. Estos derechos pueden ejercerse a través de los canales dispuestos por SISTECO S.A.S., conforme a la Política de Privacidad disponible en su página web.'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('6. Los derechos pueden ser ejercidos a través de los canales dispuestos por SISTECO S.A.S. y observando la Política de Privacidad y Tratamiento de Datos Personales de SISTECO S.A.S.'),0,'J',false);

         $this->MultiCell(0,5,utf8_decode('7. Mediante la página web de la entidad (https://www.amigored.com.co/), podré radicar cualquier tipo de requerimiento relacionado con el tratamiento de mis datos personales.'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('8. SISTECO S.A.S. garantiza la confidencialidad, libertad, seguridad, veracidad, transparencia, acceso y circulación restringida de mis datos, y se reserva el derecho de modificar su Política de Privacidad y Tratamiento de Datos Personales en cualquier momento. Cualquier cambio será informado y publicado oportunamente en su página web.'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('9. Autorizo de manera voluntaria, previa, explícita, informada e inequívoca a SISTECO S.A.S. para tratar mis datos personales, tomar mi huella y fotografía de acuerdo con su Política de Privacidad, para los fines relacionados con su objeto social y fines legales, contractuales y misionales descritos en dicha política.'),0,'J',false);

        $this->MultiCell(0,5,utf8_decode('10. La información obtenida para el Tratamiento de mis datos personales la he suministrado de forma voluntaria y es verídica'),0,'J',false);

		$this->Ln(5);

        $this->MultiCell(0,5,utf8_decode('Se firma en la ciudad de '.$data["municipio"].' ('.$data["departamento"].') a los '. date("d", strtotime($data["fecha_contrato"])).' días del mes de '. strftime("%B", strtotime($data["fecha_contrato"])).' del año '.date("Y", strtotime($data["fecha_contrato"]))),0,'J',false);

        $this->Ln(5);
	        
	}

	function autorizacion_centrales(){

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

	function prevenir_pornografia(){

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

	function anexo_findeter(){

		$data = $this->datos;

		$this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->MultiCell(0,5,utf8_decode("ANEXO 2 DEL CONTRATO \nBENEFICIO PROYECTO LÍNEAS DE FOMENTO CONECTIVIDAD EN HOGARES"),0,'C', false);
        $this->Ln();

        
        $this->MultiCell(0,5,utf8_decode("Información relevante para el usuario"),0,'J',false);
        $this->Ln();

        $fecha = $data['fecha_contrato'];

        if(!empty($data['fecha_contrato']) && $data['fecha_contrato'] != '__________'){

        	$fecha = date("Y-m-d",strtotime($data['fecha_contrato']."+ 8 month"));

        }

		$this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',10);
        $this->MultiCell(0,5,utf8_decode("Señor usuario, usted es beneficiario del proyecto líneas de fomento conectividad en hogares financiado por el Ministerio TIC, para llevar el servicio de Internet fijo a 21.417 hogares de estratos 1 y 2, con una velocidad de navegación por cada acceso de 25 Mbps de bajada y 5 Mbps de subida. Este beneficio estará vigente a partir de la activación del servicio y hasta el $fecha o por un periodo de 8 meses contados a partir de la aprobación por parte de la Interventoría del proyecto, fecha que a partir de la cual el usuario deberá asumir el pago. Para la prestación de este servicio no se requiere la adquisición de otros bienes y servicios, por lo que SISTEMAS Y TELECOMUNICACIONES DEL ORIENTE S.A.S no se podrá suspender o terminar la prestación del servicio por no contratar servicios adicionales."),0,'J',false);
        $this->Ln(5);

        $this->MultiCell(0,5,utf8_decode("En caso de requerir soporte técnico o para atender cualquier tipo de solicitud relativa a su servicio de Internet puede comunicarse al Número 01 8000 942 422 o a los siguientes números fijos locales: (607)6334050 - 3187934119"),0,'J',false);
		$this->Ln(5);

		$this->MultiCell(0,5,utf8_decode("El costo de la reinstalación del servicio por cambio de predio es de $60.000 más IVA. \nEl costo de reposición del equipo en caso de daño es de $135.300 más IVA.\nCosto por metro de fibra Adicional (Si aplica) $1.800 más IVA."),0,'J',false);
		$this->Ln(5);

        $this->MultiCell(0,5,utf8_decode("Las tarifas se ajustarán anualmente de acuerdo al incremento del salario mínimo mensual legal vigente (En caso que aplique)."),0,'J',false);

	}


	function pie(){
	    $this->SetY(-23);
	    
	    $this->SetTextColor(70,70,70);
	    $this->SetFont('calibri','',8);
	    $y = $this->GetY();
	    $this->SetDrawColor(101,108,122);
	    $this->Line(15,$y - 5,195,$y - 5);

	    $this->Cell(0,5,utf8_decode('Punto de Atención: Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 PBX: (607) 6334050 '), 0,0,'C');
	    $this->Ln();
	    $this->Cell(0,5,utf8_decode('Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co.'), 0,0,'C');
	    $this->Ln();
	    //$this->Image('img/logo_mastic.png', 15 ,$y - 1, 26, 0,'PNG');
	} 

	/*function Footer()
	{
	    $this->SetY(-10);
	    $this->SetTextColor(27,27,27);
	    $this->SetFont('futura-bdcn-bt-bold','',8);
	    $this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
	}  */

	// Simple table
    function BasicTable($header, $data)
    {
        $ancho = 195;
        // Header
        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        foreach($header as $col)
            if ($ancho == 195) {
                $this->Cell(80,7,$col,1,0,'C');
                $ancho = $ancho - 80;
            }else{
                $this->Cell($ancho/2,7,$col,1,0,'C');
            }
            
        $this->Ln();

        // Data
        $this->SetFont('calibri','',9.5);
        foreach($data as $row)
        {  
            $ancho = 195;
            foreach($row as $col)
                if ($ancho == 195) {
                    $this->Cell(80,6,$col,1);
                    $ancho = $ancho - 80;
                }else{
                    $this->Cell($ancho/2,6,$col,1);
                }
            $this->Ln();
        }
        $this->Ln();
    }
}
?>