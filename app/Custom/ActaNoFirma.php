<?php

namespace App\Custom;

use Codedge\Fpdf\Fpdf\Fpdf;

class ActaNoFirma extends Fpdf
{

    private $cliente;
    private $contrato;
    private $servicio;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $cliente) {
        parent::__construct($orientation, $unit, $size);
        $this->cliente = $cliente;
        $this->contrato = $cliente->contrato[0];
        $this->servicio = $this->contrato->servicio[0];
    }

    function Header()
    {
        $this->Image('img/header-acta-no-firma.png', 0, 0, 210, 0, 'PNG');
    }

    function body()
    {

        // Fondo
        $this->Image('img/fondo.png', 0, 45, 205, 267, 'PNG');


        $this->SetY(40);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(0, 10, utf8_decode('CONSTANCIA'), 0, 1, 'C');

        $this->Ln();

        $this->SetFont('Arial', '', 12);
        $texto = "Yo " .  $this->cliente->NombreBeneficiario . " " . $this->cliente->Apellidos . " identificado(a) con la Cédula de Ciudadanía No. " . $this->cliente->Identificacion . " De " . $this->cliente->ExpedidaEn .
            " en mi calidad de persona natural con deficiencias básicas en la capacidad de conocer la técnica de la escritura, " .
            "pero con la capacidad de entender los elementos básicos de un contrato, bajo la Gravedad del Juramento " .
            "conozco todos y cada uno de los Términos y Condiciones del Contrato Nro. " . $this->contrato->referencia . " del servicio de internet " .
            "con capacidad de " . $this->servicio->descripcion . " por valor de $" . $this->servicio->valor . " que celebre el día " . $this->contrato->fecha_inicio .
            " con la Marca Comercial Amigo Red.";

        $this->MultiCell(0, 5, utf8_decode($texto), 0, 'J');

        $this->Ln();
        $this->MultiCell(0, 5, utf8_decode('Por último, se deja en claro que el Contrato Suministrado con la Marca Comercial Amigo Red ' .
            'es totalmente valido ya que al momento de su aceptación cumplo con los Siguientes Dictámenes Jurídicos:'), 0, 'J');

        $this->Ln();
        $this->Cell(0, 10, utf8_decode('Decreto 960 de 1970'), 0, 1, 'J');

        $this->Ln(1);
        $this->SetFont('Arial', 'I', 10);
        $this->MultiCell(0, 5, utf8_decode('Artículo 69. Cuando se trate de personas que no sepan o no puedan firmar, en la diligencia de reconocimiento ' .
            'se leerá de viva voz el documento, de todo lo cual dejará constancia en el acta, que será suscrita por un testigo rogado por el compareciente, ' .
            'quien, además, imprimirá su huella dactilar, circunstancia que también se consignará en la diligencia indicando cuál fue la impresa.'), 0, 'J');


        $this->SetY(170);
        $this->SetFont('Arial', 'B', 12);
        $texto_huella = "DECLARANTE:\n\n\n\nHuella Dactilar\n(".$this->cliente->NombreBeneficiario . " " . $this->cliente->Apellidos . ")";
        $this->MultiCell(170, 5, utf8_decode($texto_huella), 0, 'L');

        $this->SetY(230);
        $texto_firma1 = "TESTIGO ACREDITADO 1:\n\n\n\n\n\nFIRMA\nNombres y Apellidos";
        $this->MultiCell(170, 5, utf8_decode($texto_firma1), 0, 'L');

        $this->SetXY(120, 230);
        $texto_firma2 = "TESTIGO ACREDITADO 2:\n\n\n\n\n\nFIRMA\nNombres y Apellidos";
        $this->MultiCell(170, 5, utf8_decode($texto_firma2), 0, 'L');
    }


    function Footer()
    {
        
        $this->Image('img/amigored_acta_no_firma.png', 10, 277, 30, 0, 'PNG');
        //$this->SetY(-12);

        $this->SetXY(20, 283);
        $this->SetFont('Arial', 'I', 7);
        $texto_direccion = "Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 - 302 PBX: +57 (7) 6334050
            Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co.
            ";
        $this->MultiCell(0, 3, utf8_decode($texto_direccion), 0, 'C');

        $this->SetXY(160, 280);

        $this->Cell(0, 10, utf8_decode('Página '.$this->PageNo() . ' de {nb}'), 0, 1, 'R');
            


    }
}
