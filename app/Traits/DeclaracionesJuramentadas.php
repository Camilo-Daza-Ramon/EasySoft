<?php

namespace App\Traits;

use App\Custom\PlantillasContratos\findeter\Declaracion;
use App\Custom\p8\Declaracion873;
use App\Custom\DeclaracionPDF;

use PDF;


trait DeclaracionesJuramentadas {

    public function declaracion_findeter($destino, $data, $ruta){

        $pdf = new Declaracion('P','mm',array(215.9,279.4)); 

        $pdf->AddFont('calibri', '', 'calibri.php'); // Calibri ya está disponible
        $pdf->AddFont('Futura BdCn BT Bold', '', 'Futura BdCn BT Bold.php'); // Ajustado al archivo existente
        $pdf->AddFont('Futura Md BT Bold', '', 'Futura Md BT Bold.php'); // Ajustado al archivo existente
        $pdf->AddFont('futura-md-bt', '', 'futura-md-bt.php'); // Ajustado al archivo existente

        $pdf->SetFont('Futura BdCn BT Bold', '', 12); // Correcto
        $pdf->SetFont('Futura Md BT Bold', '', 12); // Correcto
        $pdf->SetFont('futura-md-bt', '', 12); // Correcto

        $pdf->AliasNbPages();
        
        // //Primera página
        $pdf->AddPage();
        
        //margen del pie de pagina
        $pdf->SetAutoPageBreak(true,5);

        #Pagina 1
        $pdf->informacion($data);

        $pdf->firma($data);

        $declaracion = $pdf->Output($destino,$ruta);

        return $declaracion;
    }

    public function declaracion_lp15($destino, $data, $ruta){

        $cabecera = array("Nombres apellidos", "Documento de identidad", "Parentesco");
        $filas = array(array("","",""),array("","",""),array("","",""));

        $pdf = new DeclaracionPDF('P','mm',array(215.9,279.4));
              
        $pdf->AddFont('calibri', '', 'calibri.php'); // Calibri ya está disponible
        $pdf->AddFont('Futura BdCn BT Bold', '', 'Futura BdCn BT Bold.php'); // Ajustado al archivo existente
        $pdf->AddFont('Futura Md BT Bold', '', 'Futura Md BT Bold.php'); // Ajustado al archivo existente
        $pdf->AddFont('futura-md-bt', '', 'futura-md-bt.php'); // Ajustado al archivo existente

        $pdf->SetFont('Futura BdCn BT Bold', '', 12); // Correcto
        $pdf->SetFont('Futura Md BT Bold', '', 12); // Correcto
        $pdf->SetFont('futura-md-bt', '', 12); // Correcto

        $pdf->AliasNbPages();
        
        // //Primera página
        $pdf->AddPage();
        
        //margen del pie de pagina
        $pdf->SetAutoPageBreak(true,5);

        #Pagina 3
        $pdf->logos2();
        $pdf->informacion2($data);
        
        $pdf->BasicTable($cabecera,$filas);
        
        $pdf->informacion3($data);
        $pdf->firmas2($data);
        $pdf->pie2();

        $declaracion = $pdf->Output($destino,$ruta);

        return $declaracion;
    }

    public function declaracion_lp18($destino, $data, $ruta){
        
        $pdf = new Declaracion873('P','mm',array(215.9,279.4));
        
        $pdf->AddFont('calibri', '', 'calibri.php'); // Calibri ya está disponible
        $pdf->AddFont('Futura BdCn BT Bold', '', 'Futura BdCn BT Bold.php'); // Ajustado al archivo existente
        $pdf->AddFont('Futura Md BT Bold', '', 'Futura Md BT Bold.php'); // Ajustado al archivo existente
        $pdf->AddFont('futura-md-bt', '', 'futura-md-bt.php'); // Ajustado al archivo existente

        $pdf->SetFont('Futura BdCn BT Bold', '', 12); // Correcto
        $pdf->SetFont('Futura Md BT Bold', '', 12); // Correcto
        $pdf->SetFont('futura-md-bt', '', 12); // Correcto

        $pdf->AliasNbPages();
        
        // //Primera página
        $pdf->AddPage();
        
        //margen del pie de pagina
        $pdf->SetAutoPageBreak(true,5);

        #Pagina 3
        $pdf->logos2();
        $pdf->informacion2($data);
        
        $pdf->informacion3($data);
        $pdf->firmas2($data);
        $pdf->pie2();

        $declaracion = $pdf->Output($destino,$ruta);

        return $declaracion;
    }

}