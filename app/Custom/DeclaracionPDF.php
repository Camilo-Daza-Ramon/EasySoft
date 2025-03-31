<?php

namespace App\Custom;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;

class DeclaracionPDF extends Fpdf
{  
  function logos2(){
    $this->Image('img/logos_proyecto.jpg' , 10 ,5, 180 , 0,'jpg');
        
    $this->Ln();
    $this->SetY(30);
  }  
  
  

    function informacion2($datos){
        $data = $datos;

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->Cell(0,7,utf8_decode('DECLARACION JURAMENTADA'),0,0,'C');
        $this->Ln();
        $this->Ln();

        $this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode('Yo, '.$data["nombre_suscriptor"].', mayor de edad, identificado(a) con la cédula de ciudadanía No. '.$data["identificacion"].', expedida en '.$data["documento_expedicion"].' obrando como futuro usuario del servicio de acceso a internet fijo de Sistemas y Telecomunicaciones del Oriente S.A.S. declaro bajo la gravedad de juramento, que:'),0,'J',false);
        $this->Ln();

        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        $this->Cell($this->GetStringWidth("PRIMERO: "),5,"PRIMERO: ",0,0,'L');

        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode("Mi núcleo familiar se encuentra conformado por las siguientes personas:"),0,'J',false);
        $this->Ln(5);        
    }

    function informacion3($datos){
        $data = $datos;

        $this->SetTextColor(70,70,70);

        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        $this->Cell($this->GetStringWidth("SEGUNDO: "),5,"SEGUNDO: ",0,0,'L');

        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode("Residimos en el predio, ubicado en la dirección ".$data['direccion'].", del Municipio ".$data['municipio']." del Departamento de ".$data['departamento']."."),0,'J',false);
        $this->Ln(2);

        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        $this->Cell($this->GetStringWidth("TERCERO: "),5,"TERCERO: ",0,0,'L');

        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode("Somos usuarios nuevos, es decir, no hemos contado con el servicio de internet fijo durante los (6) seis meses anteriores a la firma de la presente declaración."),0,'J',false);
        $this->Ln(2);

        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        $this->Cell($this->GetStringWidth("CUARTO: "),5,"CUARTO: ",0,0,'L');

        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode("Declaro que pertenezco a: (Seleccionar la que sea aplicable)"),0,'J',false);
        $this->Ln(2);

        $this->Cell(20,4,utf8_decode("A) Estrato 1 "),0,0);
        $x = $this->GetX();
        $this->Cell(4,4,utf8_decode(""),1,0);

        if ($data['tipo_beneficiario'] == 'Estrato 1') {
            $y = $this->GetY();
            $this->Line($x,$y,($x + 4),($y + 4));
            $this->Line($x,($y + 4),($x + 4),$y);
        }

        $this->Ln(5);


        $this->Cell(20,4,utf8_decode("B) Estrato 2"),0,0);
        $x = $this->GetX();
        $this->Cell(4,4,"",1,0);

        if ($data['tipo_beneficiario'] == 'Estrato 2') {
            $y = $this->GetY();
            $this->Line($x,$y,($x + 4),($y + 4));
            $this->Line($x,($y + 4),($x + 4),$y);
        }

        $this->Ln(5);


        $this->Cell(20,4,utf8_decode("C) SISBEN IV "),0,0);
        $x = $this->GetX();
        $this->Cell(4,4,utf8_decode(""),1,0);

        if ($data['tipo_beneficiario'] == 'SISBEN IV') {
            $y = $this->GetY();
            $this->Line($x,$y,($x + 4),($y + 4));
            $this->Line($x,($y + 4),($x + 4),$y);
        }

        $this->Ln(5);

        $this->Cell(50,4,utf8_decode("D) Beneficiario Ley 1699 de 2013"),0,0);
        $x = $this->GetX();
        $this->Cell(4,4,utf8_decode(""),1,0);

        if ($data['tipo_beneficiario'] == 'Ley 1699 de 2013') {
            $y = $this->GetY();
            $this->Line($x,$y,($x + 4),($y + 4));
            $this->Line($x,($y + 4),($x + 4),$y);
        }

        $this->Ln(7);

        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        $this->Cell($this->GetStringWidth("QUINTO: "),5,"QUINTO: ",0,0,'L');

        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode("De conformidad con el Artículo 188 del Código General del Proceso de manera libre, consiente, voluntaria, espontánea y de acuerdo con la verdad, rindo la presente declaración; e igualmente conozco la responsabilidad penal que implica faltar a la verdad u ocultarla parcialmente o totalmente en una actuación judicial o administrativa; tal como lo establece el Artículo 442 del Código Penal.\nIgualmente, autorizo a que se verifique por cualquier medio la información aportada, y en caso de falsedad, a que se desplieguen las acciones contempladas en la Ley."),0,'J',false);
        $this->Ln(2);

        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        $this->Cell($this->GetStringWidth("SEXTO: "),5,"SEXTO: ",0,0,'L');

        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode("No me encuentro como beneficiario de otros proyectos de masificación de accesos, financiados por el Fondo Único de TIC."),0,'J',false);
        $this->Ln(2);

        $this->SetFont('futura-bdcn-bt-bold','',9.5);
        $this->Cell($this->GetStringWidth(utf8_decode("SÉPTIMO: ")),5, utf8_decode("SÉPTIMO: "),0,0,'L');

        $this->SetFont('calibri','',9.5);
        $this->MultiCell(0,5,utf8_decode("Manifiesto, que todo lo declarado anteriormente es verdadero y para tal efecto firmo, este documento, el día ". date("d", strtotime($data['fecha_contrato']))." del mes de ".strftime("%B", strtotime($data['fecha_contrato']))." de " .date("Y", strtotime( $data['fecha_contrato']))),0,'J',false);
        $this->Ln(2);
    }

    function firmas2($datos){
        $data = $datos;
       
        $this->SetTextColor(70,70,70);

        $this->SetX(20);
        
        if(!empty($data['firma'])){	    
	    	$this->Image("storage/".$data['firma'], 20 + 15,$this->GetY() +10, 60 , 0,'JPG');
	    }

        $this->Cell(130, 30, '', 'L T R', 0, 'C');
        $this->Ln();

        $this->SetX(20);
        $this->SetFont('calibri','',8);
        $this->Cell(130, 4, utf8_decode("Firma:"), 'L R B', 0, 'L');
        $this->ln();

        $this->SetX(20);
        $this->Cell(80, 4,'Nombre y Apellidos: ' . utf8_decode($data['nombre_suscriptor']), 'L B', 0, 'L');
        $this->Cell(50, 4, 'Cedula: ' . $data['identificacion'], ' R B', 0, 'L');
        $this->ln();
        $this->SetX(20);
        $this->Cell(80, 4, utf8_decode('Correo electrónico: ' .$data['correo']), 'L B', 0, 'L');
        $this->Cell(50, 4, utf8_decode('Teléfono: ' . $data['telefono']), ' R B', 0, 'L');
        $this->ln(6);


        
        
        
        $this->Ln();

        //Firma del Beneficiario
        //$this->Image($firma, 140 ,221, 50 , 0,'JPG');
    }



    function pie2(){
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