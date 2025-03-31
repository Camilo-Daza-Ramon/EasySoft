<?php

namespace App\Custom\p8;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;

class Declaracion873 extends Fpdf
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
        $this->MultiCell(0,5,utf8_decode('Yo '.$data["nombre_suscriptor"].', que resido en la dirección señalada en este formato, mayor de edad e identificado como aparece al pie de mi firma, por medio del presente escrito, Declaro bajo la gravedad de Juramento, que soy un nuevo usuario, es decir que ni yo, ni los miembros de mi núcleo familiar que residen en el mismo predio para el que se requiere la conexión, hemos contado con la prestación del servicio de Internet Fijo, al menos durante los seis meses anteriores a la presente suscripción.'),0,'J',false);
        $this->Ln();
    }

    function informacion3($datos){
        $data = $datos;

        $this->MultiCell(0,5,utf8_decode('Así mismo, para ser beneficiario del Proyecto Incentivos a la Demanda de Internet fijo II del Fondo Único de Tecnologías de la Información y las Comunicaciones, entrego la siguiente documentación: I) Copia de mi cédula de ciudadanía II) Recibo de servicio público (agua o energía) o constancia de la autoridad territorial que corresponde al predio donde se instalará el servicio de internet, el cual es de estrato 1 o 2. III) Foto de la fachada del predio en la que resido y en donde se instalará el servicio de internet, en la que aparece legible la nomenclatura de la dirección.'), 0,'J',false);
        $this->Ln();

        $this->MultiCell(0,5,utf8_decode('Declaro igualmente que la documentación que adjunto como requisito para ser beneficiario del proyecto, es verdadera.'),0,'J',false);
        $this->Ln();

        $this->MultiCell(0,5,utf8_decode("Se firma en constancia de recibido, a los ". date("d", strtotime($data['fecha_contrato']))." días del mes de ".strftime("%B", strtotime($data['fecha_contrato']))." de " .date("Y", strtotime( $data['fecha_contrato']))),0,'J',false);

        $this->Ln();
        $this->MultiCell(0,5,'Acepto:',0,'J',false);
        $this->Ln();
    }

    function firmas2($datos){
        $data = $datos;
       
        $this->SetTextColor(70,70,70);

        $this->SetX(10);

        if(!empty($data['firma'])){	    
            $this->Image("storage/".$data['firma'], 10 + 15,$this->GetY() +10, 60 , 0,'JPG');
	    }
        
        $this->Cell(80, 30, '', 'B', 0, 'C');
        $this->Ln();

        $this->Cell(80, 5,'NOMBRE: ' . utf8_decode($data['nombre_suscriptor']), 0, 0, 'L');
        $this->Ln();
        $this->Cell(80, 5,'CC/CE: ' . $data['identificacion'], 0, 0, 'L');
        $this->Ln();
        $this->Cell(80, 5, utf8_decode('TELÉFONO: ' . $data['telefono']), 0, 0, 'L');
        $this->Ln();
        $this->Cell(80, 5, utf8_decode('DIRECCIÓN: ' . $data['direccion'] . ' ' . $data['barrio']), 0, 0, 'L');
        $this->Ln();
        $this->Cell(80, 5, utf8_decode('MUNICIPIO: ' . $data['municipio'] . ' ('. $data['departamento'] . ')'), 0, 0, 'L');
        $this->Ln();
        $this->Cell(80, 5, utf8_decode('CORREO ELECTRONICO: ' . $data['correo']), 0, 0, 'L');
        $this->Ln();
        $this->Cell(80, 5, utf8_decode('PREDIO A CONECTAR: ' . $data['direccion']. ' ' . $data['barrio']), 0, 0, 'L');
        $this->Ln();
        $this->Cell(80, 5, utf8_decode('MUNICIPIO: ' . $data['municipio'] . ' ('. $data['departamento'] . ')'), 0, 0, 'L');
        $this->Ln();

        
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