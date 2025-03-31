<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Custom\PlantillasContratos\AmigoRed;
use App\Custom\ContratoPDF;
use App\Custom\p8\Contrato873PDF;

use PDF;

trait Contratos {

    public function amigored($destino, $data, $ruta){

        $pdf = new AmigoRed('P','mm',array(205.91,347.3));
        
        $pdf->AddFont('calibri','','calibri.php');
        $pdf->AddFont('futura-bdcn-bt-bold','','futura-bdcn-bt-bold.php');
        $pdf->AddFont('futura-md-bt','','futura-md-bt.php');
        $pdf->AddFont('futura-md-bt-bold','', 'futura-md-bt-bold.php');

        $pdf->AliasNbPages();

        // //Primera página
        $pdf->AddPage();
        
        //margen del pie de pagina
        $pdf->SetAutoPageBreak(true,5);
        $pdf->datos = $data;

        #pagina 1
        $pdf->pagina1();

        #Pagina 2
        $pdf->AddPage();
        $pdf->pagina2();

        #Pagina 3
        $pdf->AddPage();
        $pdf->logos();
        $pdf->tratamiento_datos_personales();
        $pdf->firmas_2();
        $pdf->pie();

        #Pagina 4
        $pdf->AddPage();
        $pdf->logos();
        $pdf->autorizacion_centrales();
        $pdf->firmas_2();                 
        $pdf->pie();

        #Pagina 5
        $pdf->AddPage();
        $pdf->logos();
        $pdf->prevenir_pornografia();
        $pdf->firmas_2();      
        $pdf->pie();


        if($data['proyecto'] == 12){
            #Pagina 6 ANEXO DE FINDETER
            $pdf->AddPage();
            $pdf->logos();
            $pdf->anexo_findeter();
            $pdf->pie();
        }

        $contrato = $pdf->Output($destino, $ruta);

        return $contrato;

    }

    public function lp015($destino, $data, $ruta){

        $pdf = new ContratoPDF('P','mm',array(205.91,347.3));
        $pdf->AddFont('calibri','','calibri.php');
        $pdf->AddFont('futura-bdcn-bt-bold','','futura-bdcn-bt-bold.php');
        $pdf->AddFont('futura-md-bt','','futura-md-bt.php');
        $pdf->AddFont('futura-md-bt-bold','', 'futura-md-bt-bold.php');

        $pdf->AliasNbPages();

        // //Primera página
        $pdf->AddPage();
        
        //margen del pie de pagina
        $pdf->SetAutoPageBreak(true,5);
        $pdf->datos = $data;

        #pagina 1
        $pdf->pagina1();

        #Pagina 2
        $pdf->AddPage();
        $pdf->pagina2();

        #Pagina 3
        $pdf->AddPage();
        $pdf->logos();
        $pdf->informacion();
        $pdf->firmas_2();
        $pdf->pie();

        #Pagina 4
        $pdf->AddPage();
        $pdf->logos();
        $pdf->inf2();
        $pdf->firmas_2();                 
        $pdf->pie();

        #Pagina 5
        $pdf->AddPage();
        $pdf->logos();
        $pdf->inf3();
        $pdf->firmas_2();      
        $pdf->pie();

        #Pagina 6
        $pdf->AddPage();
        $pdf->logos();
        $pdf->inf4();
        $pdf->firmas();
        $pdf->pie();

        $contrato = $pdf->Output($destino, $ruta);

        return $contrato;

    }

    public function lp018($destino, $data, $ruta){
        $pdf = new Contrato873PDF('P','mm',array(205.91,347.3));

        $pdf->AddFont('calibri','','calibri.php');
        $pdf->AddFont('futura-bdcn-bt-bold','','futura-bdcn-bt-bold.php');
        $pdf->AddFont('futura-md-bt','','futura-md-bt.php');
        $pdf->AddFont('futura-md-bt-bold','', 'futura-md-bt-bold.php');

        $pdf->AliasNbPages();

        // //Primera página
        $pdf->AddPage();
        
        //margen del pie de pagina
        $pdf->SetAutoPageBreak(true,5);
        $pdf->datos = $data;

        #pagina 1
        $pdf->pagina1();

        #Pagina 2
        $pdf->AddPage();
        $pdf->pagina2();

        #Pagina 3
        $pdf->AddPage();
        $pdf->logos();
        $pdf->informacion();
        $pdf->firmas_2();
        $pdf->pie();

        #Pagina 4
        $pdf->AddPage();
        $pdf->logos();
        $pdf->inf2();
        $pdf->firmas_2();                 
        $pdf->pie();

        #Pagina 5
        $pdf->AddPage();
        $pdf->logos();
        $pdf->inf3();
        $pdf->firmas_2();      
        $pdf->pie();

        #Pagina 6
        $pdf->AddPage();
        $pdf->logos();
        $pdf->inf4();
        $pdf->firmas();
        $pdf->pie();

        $contrato = $pdf->Output($destino, $ruta);

        return $contrato;

        
    }
    
}