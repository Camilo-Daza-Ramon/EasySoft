<?php 

namespace App\Custom;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;


class ActaTraslado extends Fpdf {

    function Header(){

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->Cell(50,25,'','L T B R',0,'C');
        $this->Image('img/amigored1.png' , 15,10, 40 , 0,'PNG');

        $this->Cell(90,12.5,utf8_decode('AMIGO RED'),'T B R',0,'C');
        $this->Cell(0,12.5,'FIC850-1','T B R',0,'C');
        $this->Ln();

        $this->SetX(60);

        $this->Cell(90,12.5,utf8_decode('FORMATO DE TRASLADO DE SERVICIO'),'B R',0,'C');
        

        $this->Cell(0,8,'Pagina '.$this->PageNo().'/{nb}','B R',0,'C');
        $this->Ln();
        $this->SetX(150);
        $this->Cell(0,4.5,utf8_decode('Versión 00'),'B R',0,'C');
        $this->Ln(8);        
    }

    function informacion_general($codigo_dane,$cun,$id_punto,$fecha,$departamento,$municipio){
        $this->SetTextColor(70,70,70);
        $this->SetFont('calibri','',10);

        $this->Cell(50,5,utf8_decode('Codigo Dane munipio'),0,0,'R');
        $this->Cell(45,5,$codigo_dane,'L T B R',0,'C');

        $this->Cell(45,5,utf8_decode('Numero Orden de trabajo'),0,0,'R');
        $this->Cell(0,5,$cun,'L T B R',0,'C');

        $this->Ln(6);

        $this->Cell(50,5,utf8_decode('ID Consecutivo Acceso'),0,0,'R');
        $this->Cell(45,5,$id_punto,'L T B R',0,'C');

        $this->Ln(8);

        $this->SetFillColor(48,84,150);
        $this->SetTextColor(255,255,255);

        $this->SetFont('futura-bdcn-bt-bold','',9);
        $this->Cell(0,5,utf8_decode('INFORMACIÓN GENERAL'),'L T B R',0,'C', true);
        $this->Ln();

        $this->SetTextColor(27,27,27);

        $this->Cell(90,5,utf8_decode('FECHA DEL TRASLADO'),'L B R',0,'C');
        $this->Cell(50,5,utf8_decode('DEPARTAMENTO'),'R B',0,'C');
        $this->Cell(0,5,utf8_decode('MUNICIPIO'),'R B',0,'C');
        $this->Ln();

        $this->SetFont('calibri','',9);

        $this->Cell(90,8,$fecha,'L R B',0,'C');
        $this->Cell(50,8,utf8_decode($departamento),'B R',0,'C');
        $this->Cell(0,8,$municipio,'B R',0,'C');
        $this->Ln(12);
    }

    function tecnico($tecnico_nombre,$tecnico_cedula,$tecnico_celular){
        $this->SetFillColor(48,84,150);
        $this->SetTextColor(255,255,255);

        $this->SetFont('futura-bdcn-bt-bold','',9);
        $this->Cell(0,5,utf8_decode('INFORMACIÓN DEL TECNICO QUE REALIZA LA INSTALACIÓN'),'L T B R',0,'C', true);
        $this->Ln();

        $this->SetTextColor(27,27,27);
        $this->Cell(80,5,utf8_decode('NOMBRE '),'L R B',0,'C');
        $this->Cell(40,5,utf8_decode('CONTRATISTA'),'R B',0,'C');
        $this->Cell(40,5,utf8_decode('CEDULA '),'R B',0,'C');
        $this->Cell(0,5,utf8_decode('TELEFONO'),'R B',0,'C');
        $this->Ln();

        $this->SetFont('calibri','',9);
        $this->Cell(80,8,utf8_decode($tecnico_nombre),'L R B',0,'C');
        $this->Cell(40,8,utf8_decode("BITT S.A.S"),'R B',0,'C');
        $this->Cell(40,8,$tecnico_cedula,'R B',0,'C');
        $this->Cell(0,8,$tecnico_celular,'R B',0,'C');
        $this->Ln(10);

    }

    function cliente($cliente_nombre,$cliente_cedula,$cliente_celular,$cliente_correo){
        $this->SetFillColor(191,143,0);
        $this->SetTextColor(255,255,255);

        $this->SetFont('futura-bdcn-bt-bold','',9);
        $this->Cell(0,5,utf8_decode('INFORMACIÓN DEL CLIENTE'),'L T B R',0,'C', true);
        $this->Ln();

        $this->SetTextColor(27,27,27);
        $this->Cell(80,5,utf8_decode('NOMBRE '),'L R B',0,'C');
        $this->Cell(40,5,utf8_decode('CEDULA'),'R B',0,'C');
        $this->Cell(40,5,utf8_decode('TELEFONO'),'R B',0,'C');
        $this->Cell(0,5,utf8_decode('CORREO'),'R B',0,'C');
        $this->Ln();

        $this->SetFont('calibri','',9);
        $this->Cell(80,8,utf8_decode($cliente_nombre),'L R B',0,'C');
        $this->Cell(40,8,utf8_decode($cliente_cedula),'R B',0,'C');
        $this->Cell(40,8,$cliente_celular,'R B',0,'C');
        $this->Cell(0,8,$cliente_correo,'R B',0,'C');
        $this->Ln(10);

    }

    function direcciones(){
        $this->SetFillColor(191,143,0);
        $this->SetTextColor(255,255,255);

        $this->SetFont('futura-bdcn-bt-bold','',9);
        $this->Cell(0,5,utf8_decode('INFORMACIÓN DEL NUEVO LUGAR DE RESIDENCIA'),'L T R',0,'C', true);
        $this->Ln();

        $this->SetTextColor(27,27,27);
    }

    function tipo_tecnologia(){
        $this->SetFont('futura-bdcn-bt-bold','',9);
        $this->Cell(118.5,8,utf8_decode('Tipo de Tecnologia implementada: (4G,4.5G, Wifi, HFC, xDSL, FTTH)'),'L B R',0,'C', false);

        $this->SetFont('calibri','',9);
        $this->Cell(0,8,'FTTH','L B R',0,'C', false);
        $this->Ln();

        $this->SetFont('futura-bdcn-bt-bold','',9);
        $this->Cell(80,8,utf8_decode('IDENTIFICACION DE LA RED:'),'L B R',0,'C', false);

        $this->SetFont('calibri','',9);
        $this->Cell(0,8,utf8_decode('AMIGORED'),'L B R',0,'C', false);
        $this->Ln(12);
    }


    function servicio_activo(){

        $this->Cell(50,12,utf8_decode('SERVICIO QUEDA ACTIVO'),0,0,'C');

        $x = $this->GetX();

        $this->Cell(5,5,utf8_decode('SI'),'L T B R',0,'C');

        $y12 = 0;

        
        //Equis
        $y = $this->GetY();            
        $this->Line($x,$y,($x + 5),($y + 5));
        $this->Line($x,($y + 5),($x + 5),$y);

        $y12 = $this->GetY();

        $this->Ln(7);

        $this->SetX($x);
        $this->Cell(5,5,utf8_decode('NO'),'L T B R',0,'C');

        $this->Ln(7);

       
        $this->SetXY(120,$y12);
        $this->Cell(60,12,utf8_decode('CUMPLE CON LA VELOCIDAD CONTRATADA'),0,0,'C');

        $x = $this->GetX();

        $this->Cell(5,5,utf8_decode('SI'),'L T B R',0,'C');
       
        //Equis
        $y = $this->GetY();
        $this->Line($x,$y,($x + 5),($y + 5));
        $this->Line($x,($y + 5),($x + 5),$y);           
        

        $this->Ln(7);

        $this->SetX($x);
        $this->Cell(5,5,utf8_decode('NO'),'L T B R',0,'C');

        $this->Ln(7);

    }

    function observaciones_generales($observaciones){
        $this->SetTextColor(255,255,255);
        $this->MultiCell(0,5,utf8_decode('OBSERVACIONES GENERALES DEL TRASLADO'),'L T B R','C',true);

        $this->SetFont('calibri','',9);
        $this->SetTextColor(27,27,27);
        $this->MultiCell(0,5,utf8_decode($observaciones),'L B R','L');
    }

    function firmas($cliente_cedula,$cliente_nombre,$cliente_firma,$tecnico_nombre,$tecnico_cedula,$tecnico_firma){
        $this->Ln();
        
        $y1 = $this->GetY();

        $y3 = $this->GetY();

        if ($y1 > 320) {
            $this->Ln(50);
        }

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $x = $this->GetX();
        $this->Cell(80,5,utf8_decode('Firma Cliente'),'L T R B',0,'C');
        $this->Ln();
        
        $this->Cell(80,30,'','L R',0,'B');
        $y = $this->GetY();
        $this->Ln();

        
        $this->Cell(80,5,'Firma','L R',0,'C');
        
        $this->Ln();
        $this->Cell(80,5,utf8_decode('Nombre'),'L T R',0,'L');
        $this->Ln();


        //DATOS
        $this->SetTextColor(70,70,70);
        $this->SetFont('futura-md-bt','',8);
        $this->Cell(80,5,utf8_decode($cliente_nombre),'R L',0,'B');
        $this->Ln();
        
        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->Cell(80,5,utf8_decode('Cédula'),'L R T',0,'L');

        $this->Ln();

        //DATOS
        $this->SetTextColor(70,70,70);
        $this->SetFont('futura-md-bt','',8);
        
        $this->Cell(80,5,$cliente_cedula,'L R B',0,'L');
        $this->Ln();        
            

        $this->Ln(8);
        
        
        //Firma del Técnico
        if(!empty($cliente_firma)){
            $this->Image('D:\\Awebsites\\ConstruyendoWebSite\\easy\\public\\storage\\' .$cliente_firma, $x+2 ,$y+2, 65 , 0,'JPG');
        }
        

        
        $this->SetXY(100,$y3);

        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);
        $this->Cell(80,5,utf8_decode('Firma contratista instalación '),'L T R B',0,'C');
        $this->Ln();

        $this->SetX(100);
        
        $this->Cell(80,30,'','L R',0,'B');    
        $y = $this->GetY();    
        $this->Ln();

        $this->SetX(100);
        $this->Cell(80,5,'Firma','L R',0,'C');        
        $this->Ln();

        $this->SetX(100);
        $this->Cell(80,5,utf8_decode('Nombre'),'L T R',0,'L');
        $this->Ln();

        $this->SetX(100);

        //DATOS
        $this->SetTextColor(70,70,70);
        $this->SetFont('futura-md-bt','',8);
        $this->Cell(80,5,utf8_decode($tecnico_nombre),'R L',0,'B');
        $this->Ln();
        
        $this->SetTextColor(27,27,27);
        $this->SetFont('futura-bdcn-bt-bold','',10);

        $this->SetX(100);
        $this->Cell(80,5,utf8_decode('Cédula'),'L R T',0,'L');

        $this->Ln();

        //DATOS
        $this->SetTextColor(70,70,70);
        $this->SetFont('futura-md-bt','',8);
        
        $this->SetX(100);
        $this->Cell(80,5,$tecnico_cedula,'L R B',0,'L');
        $this->Ln();            

        $this->Ln(8);

        //Firma del Técnico
        if(!empty($tecnico_firma)){
            $this->Image($tecnico_firma, $x+100 ,$y + 2, 50 , 0,'JPG');
        }
    }



    // Simple table
    function BasicTable($header, $data,$fondo,$ancho_personalizado){

        if ($fondo) {
            $this->SetFillColor(48,84,150);
            $this->SetTextColor(255,255,255);
        }


        $ancho_pagina = 195.9;
        //$ancho_personalizado = [10,null,20];

        $ancho_columna = $ancho_pagina;
        $total_cabecera = count($header);

        if (empty($ancho_personalizado)){
            $ancho_columna = $ancho_pagina / $total_cabecera;
        }else{
            $vacios = array();
            $vacios = array_keys($ancho_personalizado,null);

            foreach ($ancho_personalizado as $key1) {
                if (!empty($key1)) {
                    $ancho_pagina -= $key1;
                }
            }

            $ancho_columna = $ancho_pagina / count($vacios);
        }

        // Header
        $this->SetFont('futura-bdcn-bt-bold','',9.5);

        foreach($header as $key => $col)

            if (!empty($ancho_personalizado)){
                
                if (!empty($ancho_personalizado[$key])) {
                    $this->Cell($ancho_personalizado[$key],7,utf8_decode($col),1,0,'C', $fondo);

                }else{
                    $this->Cell($ancho_columna,7,utf8_decode($col),1,0,'C', $fondo);
                }
                    
                
            }else{
                $this->Cell($ancho_columna,7,utf8_decode($col),1,0,'C', $fondo);
            }                  
            
        $this->Ln();     

        

        // Data
        $this->SetFont('calibri','',9.5);
        $this->SetTextColor(27,27,27);
        
        foreach($data as $row)
        {
            $l = 0;
            foreach($row as $col){
                if (!empty($ancho_personalizado)){

                    if (!empty($ancho_personalizado[$l])) {
                        $this->Cell($ancho_personalizado[$l],6,utf8_decode($col),'L R B',0,'C');
                    }else{
                        $this->Cell($ancho_columna,6,utf8_decode($col),'L R B',0,'C');
                    }
                }else{
                    $this->Cell($ancho_columna,7,utf8_decode($col),1,0,'C', $fondo);
                }
                $l+=1;
            }                                                   
            $this->Ln();
        }
    }
	
}

?>