<?php

namespace App\Custom\PlantillasContratos\findeter;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;

class Declaracion extends Fpdf
{
    function informacion($datos){
        $data = $datos;

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->MultiCell(0,5,utf8_decode("DECLARACIÓN JURAMENTADA PARA ACCEDER AL BENEFICIO DEL PROYECTO DE \nINTERNET FIJO RESIDENCIAL - CONVOCATORIA PAF-MINTICFOMENTO-PS-013-2024"),0,'C', false);
        $this->Ln();

        $this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',10);
        $this->MultiCell(0,5,utf8_decode("Para efecto de la suscripción del Contrato el (la) Señor (a): ".$data['nombre_suscriptor']." \n declara bajo gravedad de juramento:"),0,'J',false);
        $this->Ln();

        $this->MultiCell(0,5,utf8_decode("1. Que soy nuevo usuario de internet, es decir, que ni yo ni los miembros de mi núcleo familiar que residen en el mismo predio en el que se instalará el servicio de internet fijo, han contado con la prestación del servicio de internet fijo, al menos durante los últimos seis (6) meses anteriores a la suscripción del contrato de servicios de Internet Fijo Residencial. \n2. Que no soy suscriptor del servicio ni usuario existente que se beneficia de otros proyectos de masificación de accesos, que ha sido financiado el servicio de Internet a hogares. \n3. Que yo me encuentro domiciliado en la dirección que aparece en el recibo de servicio público de agua, energía, gas o en la constancia de la autoridad territorial correspondiente y en la cual se instalará el servicio. \n4. Que la documentación que adjunto como requisito para ser beneficiario del Proyecto Líneas de Fomento Conectividad en Hogares del Ministerio TIC es verdadera y acepto que Sistemas y Telecomunicaciones del Oriente S.A.S desconecten el servicio de internet y demás servicios fijos, en caso de que Sistemas y Telecomunicaciones del Oriente S.A.S  llegara a demostrar que alguno de los documentos no corresponde con la realidad. Así mismo, autorizo de manera expresa a Sistemas y Telecomunicaciones del Oriente S.A.S para que entregue a FINDETER y a la interventoría designada, copia del contrato suscrito y los soportes correspondientes, para efectos de verificar las condiciones de acceso a los beneficios del proyecto. \n5. Que la documentación que entrego es: I) Copia de Cédula de Ciudadanía; II) Recibo de servicio público (agua, energía o gas) o constancia de la autoridad territorial que corresponde al predio donde se instalará el servicio de internet, el cual es de estrato 1 o 2; IV. III) Foto de la fachada del predio en el que resido y en donde se instalará el servicio de Internet. \n6. Que fui informado que para ser beneficiario del Proyecto Líneas de Fomento Conectividad en Hogares no estoy obligado(a) a contratar adicionalmente otros servicios ofertados por Sistemas y Telecomunicaciones del Oriente S.A.S, sin embargo, de manera voluntaria los puedo contratar. \n7. Finalmente declaro que he sido informado, respecto a las condiciones del servicio de internet fijo subsidiado (sin pago) y consistente de la información suministrada por Sistemas y Telecomunicaciones del Oriente S.A.S, firmo la presente declaración."),0,'J',false);
        $this->Ln(5);

        $this->MultiCell(0,5,utf8_decode("Acepto las condiciones descritas anteriormente:"),0,'J',false);

    }

    function firma($datos){

	    $data = $datos;

        $this->SetTextColor(70,70,70);

        //Firma Beneficiario
		if(!empty($data['firma'])){	    
	    	$this->Image('storage/'.$data['firma'], 15,null, 65 , 0,'JPG');
	    }
        
        $this->Ln(2);

        $this->Cell(80,10,'Firma Suscriptor','T',0,'L');
        $this->Ln();
        $this->Cell(10,10,'C.C:',0,0);
        $this->Cell(40,10,$data['identificacion'],'B',0,'L');

        $this->Cell(42,10,utf8_decode('Fecha suscripción Contrato:'),0,0);
        $this->Cell(38,10,date("Y/m/d", strtotime($data['fecha_contrato'])),'B',0,'L');

        $this->Ln();


        $this->Cell(15,10,utf8_decode('Teléfono:'),0,0);
        $this->Cell(23,10,$data['telefono'],'B',0,'L');

        $this->Cell(30,10,utf8_decode('Correo electrónico:'),0,0);
        $this->Cell(60,10,utf8_decode($data['correo']),'B',0,'L');

        $this->Ln();

        $this->Cell(35,10,utf8_decode('Dirección de domicilio:'),0,0);
        $this->Cell(95,10,$data['direccion'],'B',0,'L');

        $this->Ln();

        $this->Cell(20,10,utf8_decode('Municipio:'),0,0);
        $this->Cell(110,10,utf8_decode($data['municipio'] . ' - ' . $data['departamento']),'B',0,'L');

        $this->Ln();
	}
}

?>