<?php

namespace App\Custom;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\This;

class ActaMantenimiento extends Fpdf
{

    private $mantenimiento;
    private $tipo_mantenimiento;
    private $margin_left = 5;
    private $margin_right = 5;
    private $margin_top = 5;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $mantenimiento, $tipo)
    {
        parent::__construct($orientation, $unit, $size);
        $this->mantenimiento = $mantenimiento;
        $this->tipo_mantenimiento = $this->getTipoMantenimiento($tipo);
    }

    function Header()
    {
        $this->Image('img/amigored1.png', $this->margin_left+2, $this->margin_top, 30, 0, 'PNG');
        $this->Rect($this->margin_left, $this->margin_top, 35, 18);
        $texto_header = "REPORTE DE MANTENIMIENTO ";
        $texto_header_posx = 55;
        if ($this->tipo_mantenimiento == "preventivo") {
            $texto_header .= 'PREVENTIVO';
        } else if ($this->tipo_mantenimiento == "correctivo") {
            $texto_header .= 'CORRECTIVO';
        } else {
            $texto_header .= 'CORRECTIVO MASIVO';
            $texto_header_posx = 48;
        }
        $this->SetXY($texto_header_posx, 6 + $this->margin_top);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(150, 5, utf8_decode($texto_header), 0, 'C');
        $this->Rect(35 + $this->margin_left, $this->margin_top, 125, 18);

        $this->Image('img/logo_sisteco.png', 174 - $this->margin_right, 3 + $this->margin_top, 35, 0, 'PNG');
        $this->Rect(165, $this->margin_top, 40, 18);

        $this->SetY(18);
        //$this->Ln();
    }

    function body()
    {
        $this->SetFont('Arial', 'B', 8);

        /** Datos del Mantenimiento y del cliente si es correctivo acceso*/
        if ($this->tipo_mantenimiento == "correctivo") {
            $this->mostrarCliente();
        }
        $this->mostrarDatosMant();

        /** Datos de los clientes si es masivo o preventivo */
        $this->setFillColor(0, 145, 63);

        if ($this->tipo_mantenimiento == "preventivo" || $this->tipo_mantenimiento == "correctivo_masivo") {
            $this->clientesMasivo();
        }

        $this->diagnosticos();

        $this->direcciones();

        $this->pruebas();

        if ($this->tipo_mantenimiento != 'preventivo') {
            $this->soluciones();
    
            $this->fallas();
        }


        if (isset($this->mantenimiento->paradas_reloj) && $this->mantenimiento->paradas_reloj->count() !== 0) {
            $this->paradaDeReloj();
        }

        $this->observaciones();

        $this->materiales();

        $this->equipos();

        
        $this->fotos();
        $this->firmas();
    }

    function Footer()
    {
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

        $this->Cell(0, 10, utf8_decode('Página '.$this->PageNo() . ' de {nb}'), 0, 1, 'R');


    }

    private function renderListCells($lista, $tipo_lista = "", $num_cols = 3)
    {
        $this->SetY($this->GetY());
        $i = 0;
        $posx = 0;
        $posy = $this->GetY();
        $cols = $num_cols;
        foreach ($lista as $value) {
            if ($i == $cols) {
                $i = 0;
                $posx = 0;
                $posy = $this->GetY();
            }
            $tipo_fallo = $tipo_lista === 'diagnostico' ? $value->diagnostico : $value->tipo;
            if ($tipo_fallo) {
                $fallo = $tipo_fallo->DescipcionFallo;
                $this->printCell($fallo, $posx, $posy, (200 / $cols));
                $i++;
                $posx += (200 / $cols);
            }
        }
    }

    private function fotos()
    {
        $this->AddPage();

        $this->SetFont('Arial', 'B', 9);

        $this->printCell('REGISTRO FOTOGRÁFICO', 0, $this->GetY(), 200, true);
        $this->SetY($this->GetY());

        $this->SetFont('Arial', 'B', 7);
        if (!isset($this->mantenimiento->archivos) || $this->mantenimiento->archivos->count() == 0) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO EXISTEN REGISTROS DE ARCHIVOS O FOTOS'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $this->SetY($this->GetY()+5);


        $this->SetFont('Arial', '', 9);

        $y = $this->GetY();
        $cols = 2;
        $w = (200 / $cols);
        $h = 70;
        $this->mantenimiento->archivos->each(function ($el) use (&$i, &$y, $cols, $w, $h) {

            if ($i == $cols) {
                $i = 0;
                $y = $this->GetY() + 5;
            }

            if (($y + $h + 20) > $this->GetPageHeight()) {
                $this->AddPage();
                $this->SetY($this->margin_top);
                $y = $this->GetY()+18;
            }

            if (trim($el->tipo_archivo) == 'jpg' || trim($el->tipo_archivo) == 'jpeg' || trim($el->tipo_archivo) == 'png') {
                $filePath = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $el->archivo));
                $this->SetXY(($w * $i), $y);
                $this->Cell(100, 10, utf8_decode($el->nombre), 0, 0, 'C');
                $this->Image($filePath, ($w * $i) + $this->margin_left + 10, $y+10, 0, $h-10, trim($el->tipo_archivo));
                $this->SetY($y+$h);
                $this->Rect(($w * $i) + $this->margin_left, $y, $w, $h+5, 'D');
                $i += 1;
            }
        });
    }

    private function firmas()
    {
        $this->SetFont('Arial', 'B', 9);
        if ($this->GetY() > 230) {
            $this->AddPage();
            $this->SetY($this->margin_top+13);
        }

        $y = $this->GetY();
        $ycontratista = $y;
        $cols = 1;
        $w = (200 / $cols);
        $h_rect_firma = 25;
        $padding_y_firma = 5;
        $padding_x_firma = 25;
        if ($this->tipo_mantenimiento === 'correctivo') {
            
            $cols = 2;
            $w = (200 / $cols);
            
            $h_titulo = $this->GetY();
            $this->printCell('CLIENTE', 0, $y+2, $w, false, false, 'C');
            $h_titulo = $this->GetY() - $h_titulo;
            if (isset($this->mantenimiento->firma)) {
                $firma = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $this->mantenimiento->firma));
                if (file_exists($firma)) {
                    $firmaSize = getimagesize($firma);
                    $wImage = $firmaSize[0]/20;
                    $hImage = $firmaSize[1]/20;
                    $h_rect_firma = $hImage+$h_titulo+$padding_y_firma;
                    $this->Rect($this->margin_left, $y + 5, $w, $h_rect_firma);
                    $this->Image($firma, $this->margin_left + $padding_x_firma, $y + 15, 
                    $wImage > $w ? $w-$padding_x_firma : 0, $hImage);
                } else {
                    $this->Rect($this->margin_left, $y + 5, $w, $h_rect_firma);
                }
            } else {
                $this->Rect($this->margin_left, $y + 5, $w, $h_rect_firma);
            }

            $this->SetY($this->GetY()+$h_rect_firma-7);
            $this->SetFont('Arial', 'B', 8);

            $y = $this->GetY();

            $this->printCell('NOMBRE:', 0, $y, 20, false);

            $this->SetFont('Arial', '', 8);

            $this->printCell($this->mantenimiento->nombre . " - " . $this->mantenimiento->parentezco , $this->GetX() - $this->margin_left, $y, 80, false);

            $y = $this->GetY();
            $this->SetFont('Arial', 'B', 8);

            $this->printCell('CEDULA:', 0, $y, 20, false);

            $this->SetFont('Arial', '', 8);

            $this->printCell($this->mantenimiento->cedula, $this->GetX() - $this->margin_left, $y, 80, false);
        }

        $y = $ycontratista;
        $inicioX = $this->tipo_mantenimiento === 'correctivo' ? $w : 0;
        $this->SetFont('Arial', 'B', 9);

        $h_titulo = $this->GetY();
        $this->printCell('CONTRATISTA', $inicioX, $y+2, $w, false, false, 'C');
        $h_titulo = $this->GetY() - $h_titulo;
        if (isset($this->mantenimiento->usuario_atiende->firma)) {
            $firma = public_path('storage' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $this->mantenimiento->usuario_atiende->firma));
            if (file_exists($firma)) {
                $firmaSize = getimagesize($firma);
                $hImage = $firmaSize[1]/20;
                $h_rect_firma = $h_rect_firma > $hImage+$h_titulo+$padding_y_firma 
                    ? $h_rect_firma : $hImage+$h_titulo+$padding_y_firma;
                $this->Rect($inicioX + 5, $y + 5, $w, $h_rect_firma);
                $this->Image($firma, $inicioX + $padding_x_firma, $h_rect_firma > 25 ? $y+15 : $y+12, 50, $hImage);
            } else {
                $this->Rect($inicioX + 5, $y + 5, $w, $h_rect_firma);
            }
        } else {
            $this->Rect($inicioX + 5, $y + 5, $w, $h_rect_firma);
        }

        $this->SetY($y+$h_rect_firma);
        $this->SetFont('Arial', 'B', 8);

        $y = $this->GetY();
        $wcelda = $this->tipo_mantenimiento === 'correctivo' ? 20 : 40;
        $wcampo = $this->tipo_mantenimiento === 'correctivo' ? 80 : 160;

        $this->printCell('NOMBRE:', $inicioX, $y, $wcelda, false);

        $this->SetFont('Arial', '', 8);

        $this->printCell($this->mantenimiento->usuario_atiende->name, $this->GetX() - $this->margin_left, $y, $wcampo, false);

        $y = $this->GetY();

        $this->SetFont('Arial', 'B', 8);

        $this->printCell('CEDULA:', $inicioX, $y, $wcelda, false);

        $this->SetFont('Arial', '', 8);

        $this->printCell($this->mantenimiento->usuario_atiende->cedula 
        ? $this->mantenimiento->usuario_atiende->cedula : 'N.A', $this->GetX() - $this->margin_left, $y, $wcampo, false);
    }

    private function equipos()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('EQUIPOS', 0, $this->GetY(), 200, true);

        $this->SetFont('Arial', 'B', 7);
        if (!isset($this->mantenimiento->equipos) || $this->mantenimiento->equipos->count() == 0) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO EXISTEN REGISTROS DE EQUIPOS'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $y = $this->GetY() + 5;
        $cols = 4;
        $w = (200 / $cols);
        $this->SetFont('Arial', 'B', 9);
        $this->printCell('EQUIPO', 0, $y, $w);
        $this->printCell('SERIAL', 1 * $w, $y, $w);
        $this->printCell('OBSERVACIONES', 2 * $w, $y, $w);
        $this->printCell('REALIZO CAMBIO', 3 * $w, $y, $w);

        $this->SetFont('Arial', '', 7);

        $this->mantenimiento->equipos->each(function ($el) use ($w) {
            $y = $this->GetY();

            $ancho_equipo = $this->GetStringWidth($el->Equipo);
            $ancho_serial = $this->GetStringWidth($el->Serial ? $el->Serial : 'N.A');
            $ancho_obs = $this->GetStringWidth($el->Observaciones ? $el->Observaciones : 'N.A');

            $lineas_equipo = ceil($ancho_equipo / ($w - 5));
            $lineas_serial = ceil($ancho_serial / ($w - 5));
            $lineas_obs = ceil($ancho_obs / ($w - 5));
            $h_max = (max($lineas_equipo, $lineas_serial, $lineas_obs) * 5);

            $this->printMultiCell(
                $el->Equipo,
                0,
                $y,
                $w,
                false,
                $lineas_equipo > 1 ? 0 : $h_max
            );
            $this->printMultiCell(
                $el->Serial,
                1 * $w,
                $y,
                $w,
                false,
                $lineas_serial > 1 ? 0 : $h_max
            );
            $this->printMultiCell(
                $el->Observaciones,
                2 * $w,
                $y,
                $w,
                false,
                $lineas_obs > 1 ? 0 : $h_max
            );
            $this->printMultiCell($el->RealizoCambio, 3 * $w, $y, $w, false, $h_max);
            $this->SetY($this->GetY() - 5);
        });
    }

    private function materiales()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('MATERIALES', 0, $this->GetY() - 5, 200, true);

        $this->SetFont('Arial', '', 7);
        if (!isset($this->mantenimiento->materiales) || $this->mantenimiento->materiales->count() == 0) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO EXISTEN REGISTROS DE MATERIALES'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $y = $this->GetY();

        $w_item = 60; // ITEM
        $w_desc = 80; // DESCRIPCIÓN
        $w_qty = 30;  // CANTIDAD
        $w_unit = 30; // UNIDAD DE MEDIDA

        $this->SetFont('Arial', 'B', 8);
        $this->printCell('ITEM', 0, $y, $w_item);
        $this->printCell('DESCRIPCIÓN', $w_item, $y, $w_desc);
        $this->printCell('CANTIDAD', $w_item + $w_desc, $y, $w_qty);
        $this->printCell('UNIDAD DE MEDIDA', $w_item + $w_desc + $w_qty, $y, $w_unit);

        $this->SetFont('Arial', '', 7);

        $this->mantenimiento->materiales->each(function ($el) use ($w_item, $w_desc, $w_qty, $w_unit) {
            $y = $this->GetY();

            $ancho_item = $this->GetStringWidth($el->inventario->Descripcion);
            $ancho_desc = $this->GetStringWidth($el->Descripcion ? $el->Descripcion : 'N.A');

            $lineas_item = ceil($ancho_item / $w_item);
            $lineas_desc = ceil($ancho_desc / $w_desc);

            $h_max = max($lineas_item, $lineas_desc) * 5;

            $this->printMultiCell(
                $el->inventario->Descripcion,
                0,
                $y,
                $w_item,
                false,
                $h_max == $lineas_item * 5 ? 0 : $h_max
            );
            $this->printMultiCell(
                $el->Descripcion ? $el->Descripcion : 'N.A',
                $w_item,
                $y,
                $w_desc,
                false,
                $h_max == $lineas_desc * 5 ? 0 : $h_max
            );
            $this->printMultiCell($el->Cantidad, $w_item + $w_desc, $y, $w_qty, false, $h_max);
            $this->printMultiCell($el->Unidad ? $el->Unidad : 'N.A', $w_item + $w_desc + $w_qty, $y, $w_unit, false, $h_max);

            $this->SetY($y + $h_max);
        });
    }

    private function observaciones()
    {

        $this->SetFont('Arial', 'B', 9);

        $this->printCell('OBSERVACIONES', 0, $this->GetY(), 200, true);

        $this->SetFont('Arial', '', 7);
        if (!isset($this->mantenimiento->Observaciones)) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO EXISTEN OBSERVACIONES'), 1, 1, 'C', 0);
            return;
        }

        $this->SetFont('Arial', '', 8);
        $this->printMultiCell(
            $this->mantenimiento->Observaciones,
            0,
            $this->GetY(),
            200
        );
    }

    private function paradaDeReloj()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('PARADAS DE RELOJ', 0, $this->GetY(), 200, true);

        $y = $this->GetY() + 5;
        $cols = 3;
        $w = (200 / $cols);
        $this->SetFont('Arial', 'B', 9);
        $this->printCell('Descripición', 0, $y, $w);
        $this->printCell('Fecha Hora Inicio', 1 * $w, $y, $w);
        $this->printCell('Fecha Hora Fin', 2 * $w, $y, $w);

        $this->SetFont('Arial', '', 7);

        $this->mantenimiento->paradas_reloj->each(function ($el) use ($w) {
            $y = $this->GetY();
            $this->printMultiCell($el->DescripcionParada, 0, $y, $w);
            $h = $this->GetY() - $y - 5;
            $this->printMultiCell(
                $el->InicioParadaDeReloj . ' ' . $el->HoraInicio . ':' . ($el->MinInicio > 9
                    ? $el->MinInicio : str_pad($el->MinInicio, 2, "0", STR_PAD_LEFT)),
                1 * $w,
                $y,
                $w,
                false,
                $h
            );
            $this->printMultiCell(
                $el->FinParadaDeReloj . ' ' . $el->HoraFin . ':' . ($el->MinFin > 9
                    ? $el->MinFin : str_pad($el->MinFin, 2, "0", STR_PAD_LEFT)),
                2 * $w,
                $y,
                $w,
                false,
                $h
            );
            $this->SetY($this->GetY() - 5);
        });
    }

    private function fallas()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('FALLAS', 0, $this->GetY(), 200, true);

        $this->SetFont('Arial', '', 7);
        if (!isset($this->mantenimiento->fallas) || $this->mantenimiento->fallas->count() === 0) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO HAY FALLAS REPORTADAS'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $this->renderListCells($this->mantenimiento->fallas);
    }

    private function soluciones()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('SOLUCIONES', 0, $this->GetY(), 200, true);

        $this->SetFont('Arial', '', 7);
        if (!isset($this->mantenimiento->soluciones) || $this->mantenimiento->soluciones->count() === 0) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO HAY SOLUCIONES'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $this->renderListCells($this->mantenimiento->soluciones);
    }

    private function pruebas()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('PRUEBAS ADELANTADAS', 0, $this->GetY(), 200, true);

        $this->SetFont('Arial', '', 7);
        if ($this->mantenimiento->pruebas->count() === 0) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO HAY PRUEBAS'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $this->renderListCells($this->mantenimiento->pruebas);
    }

    private function direcciones()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('DIRECCIONES', 0, $this->GetY(), 200, true);

        $this->SetFont('Arial', 'B', 7);
        if ($this->mantenimiento->direcciones->count() === 0 && !isset($this->mantenimiento->cliente)) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO HAY DIRECCIONES'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $y = $this->GetY();
        $cols = 3;
        $w = (200 / $cols);
        $this->SetFont('Arial', 'B', 9);
        $this->printCell('Dirección', 0, $y, $w);
        $this->printCell('Barrio', 1 * $w, $y, $w);
        $this->printCell('Coordenadas', 2 * $w, $y, $w);

        $this->SetFont('Arial', '', 7);

        if ($this->mantenimiento->direcciones->count() > 0) {
            $this->mantenimiento->direcciones->each(function ($dir) use ($w) {
                $this->renderDireccion($dir, $w);
            });
        } else {
            $this->renderDireccion($this->mantenimiento->cliente, $w, false);
        }
    }

    private function renderDireccion($dir, $w, $desde_tb_direcciones = true)
    {
        $y = $this->GetY();
        $this->printMultiCell(
            $desde_tb_direcciones ? $dir->Direccion : $dir->DireccionDeCorrespondencia,
            0,
            $y,
            $w
        );
        $yMulti = $this->GetY();
        $h = $yMulti - $y - 5;
        $this->printMultiCell($dir->Barrio ? $dir->Barrio : 'N.A', 1 * $w, $y, $w, false, $h);
        $this->printMultiCell("Lat: " . $dir->Latitud . " / Long: " . $dir->Longitud, 2 * $w, $y, $w, false, $h);
        $this->SetY($this->GetY() - 5);
    }

    private function diagnosticos()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('DIAGNÓSTICOS', 0, $this->GetY() - 5, 200, true);
        $this->SetFont('Arial', '', 7);

        if (!isset($this->mantenimiento->diagnosticos) || $this->mantenimiento->diagnosticos->count() === 0) {
            $this->SetXY($this->margin_left, $this->GetY()+5);
            $this->Cell(200, 10, utf8_decode('NO HAY DIAGNÓSTICOS'), 1, 1, 'C', 0);
            $this->SetY($this->GetY() - 5);
            return;
        }

        $this->renderListCells($this->mantenimiento->diagnosticos, 'diagnostico');
    }

    private function clientesMasivo()
    {
        $this->SetFont('Arial', 'B', 9);

        $this->printCell('CLIENTES AFECTADOS', 0, $this->GetY() - 5, 200, true);

        $this->SetFont('Arial', '', 8);

        if ($this->mantenimiento->clientes->count() == 0) {
            $this->SetXY($this->margin_left, $this->GetY() + 5);
            $this->Cell(200, 10, utf8_decode('NO HAY CLIENTES AFECTADOS'), 1, 1, 'C', 0);
            return;
        }

        $strCedulas = $this->mantenimiento->clientes->reduce(function ($ac, $item) {
            $clienteIdentificacion = $item->cliente->Identificacion;
            return $ac . ', ' . $clienteIdentificacion;
        });
        
        $strCedulas = substr($strCedulas, 2);

        $arrCedulas = explode(' ', $strCedulas);
        
        $this->SetXY($this->margin_left, $this->GetY() + 5);

        if (count($arrCedulas) > 580) {
            $contador = ceil(count($arrCedulas) / 580);

            for ($i=0; $i < $contador; $i++) { 
                $min = $i == 0 ? 0 : 580 * $i;
                $max = 580*($i+1);
                $strCedulas = implode(" ", array_slice($arrCedulas, $min, $max));
                $this->MultiCell(200, 5, utf8_decode($strCedulas), 1, 'L', 0);
                if ($contador != $i+1) {
                    $this->AddPage();
                    $this->SetXY($this->margin_left, $this->GetY() + 5);
                }
            }

        } else {
            $this->MultiCell(200, 5, utf8_decode($strCedulas), 1, 'L', 0);
        }
    }

    private function getTipoMantenimiento($tipo)
    {
        if (strtolower($tipo) == 'preventivo') {
            return strtolower($tipo);
        } else if ($this->mantenimiento->TipoMantenimiento == 'REDT') {
            return 'correctivo_masivo';
        } else {
            return 'correctivo';
        }
    }

    private function mostrarDatosMant()
    {
        $inicioY = $this->GetY();
        if ($this->tipo_mantenimiento !== 'correctivo') $this->SetX(0);
        $inicioX = $this->GetX();
        $w = 48;

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("N° DE TICKET: ", $inicioX, $inicioY, $w);
        $this->SetFont('Arial', '', 8);
        $this->printCell(($this->tipo_mantenimiento === 'preventivo'
                ? $this->mantenimiento->NumeroDeMantenimiento : $this->mantenimiento->NumeroDeTicket),
            $inicioX + 22,
            $inicioY,
            $w,
            false,
            false
        );

        $this->SetX($inicioX + $w);
        $w = ($this->tipo_mantenimiento !== 'correctivo' ? 80 : 65);
        $inicioX = $this->GetX();
        $this->SetFont('Arial', 'B', 8);
        $this->printCell("TIPO DE MANTENIMIENTO: ", $inicioX, $inicioY, $w);
        $this->SetFont('Arial', '', 8);
        $this->printCell(
            str_replace('_', ' ', strtoupper($this->tipo_mantenimiento)),
            $inicioX + 38,
            $inicioY,
            $w,
            false,
            false
        );

        $this->SetX($inicioX + $w);
        $w = ($this->tipo_mantenimiento !== 'correctivo' ? 72 : 52);
        $inicioX = $this->GetX();
        $this->SetFont('Arial', 'B', 8);
        $this->printCell("FECHA DE CREACIÓN: ", $inicioX, $inicioY, $w);
        $this->SetFont('Arial', '', 8);
        $this->printCell(
            date("Y-m-d", strtotime($this->mantenimiento->Fecha)),
            $inicioX + 32,
            $inicioY,
            $w,
            false,
            false
        );

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("DEPARTAMENTO: ", 0, $inicioY + 5, 55);
        $this->SetFont('Arial', '', 8);
        $this->printCell(
            $this->mantenimiento->municipio->departamento->NombreDelDepartamento,
            25,
            $inicioY + 5,
            55,
            false,
            false
        );

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("MUNICIPIO: ", 55, $inicioY + 5, 50);
        $this->SetFont('Arial', '', 8);
        $this->printCell("MUNICIPIO: " . $this->mantenimiento->municipio->NombreMunicipio, 55, $inicioY + 5, 50);

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("VELOCIDAD BAJADA: ", 105, $inicioY + 5, 48);
        $this->SetFont('Arial', '', 8);
        $this->printCell(($this->mantenimiento->VelocidadDeBajada
            ? $this->mantenimiento->VelocidadDeBajada : 'N.A'), 136, $inicioY + 5, 50, false, false);

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("VELOCIDAD SUBIDA: ", 153, $inicioY + 5, 47);
        $this->SetFont('Arial', '', 8);
        $this->printCell(($this->mantenimiento->VelocidadDeSubida
            ? $this->mantenimiento->VelocidadDeSubida : 'N.A'), 183, $inicioY + 5, 55, false, false);

        $fecha_hora_inicio = $this->mantenimiento->fecha_cierre_hora_inicio;
        $fecha_hora_fin = $this->mantenimiento->fecha_cierre_hora_fin;

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("FECHA INICIO: ", 0, $inicioY + 10, 50);
        $this->SetFont('Arial', '', 8);
        $this->printCell(date('Y-m-d', strtotime($fecha_hora_inicio)), 21, $inicioY + 10, 50, false, false);

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("HORA INICIO: ", 50, $inicioY + 10, 50);
        $this->SetFont('Arial', '', 8);
        $this->printCell(date('H:m:s', strtotime($fecha_hora_inicio)), 69, $inicioY + 10, 50, false, false);

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("FECHA FIN: ", 100, $inicioY + 10, 50);
        $this->SetFont('Arial', '', 8);
        $this->printCell(date('Y-m-d', strtotime($fecha_hora_fin)), 117, $inicioY + 10, 50, false, false);

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("HORA FIN: ", 150, $inicioY + 10, 50);
        $this->SetFont('Arial', '', 8);
        $this->printCell(date('H:m:s', strtotime($fecha_hora_fin)), 165, $inicioY + 10, 50, false, false);
        $this->SetY($this->GetY() + 5);
    }

    private function mostrarCliente()
    {
        $cliente = $this->mantenimiento->cliente;

        $y = $this->GetY();

        $this->SetX(0);
        $w = 80;
        $x = $this->GetX();
        $this->SetFont('Arial', 'B', 8);
        $this->printCell("NOMBRE: ", $x, $y, $w);
        $this->SetFont('Arial', '', 8);
        $this->printCell(
            trim($this->mantenimiento->nombre),
            $x + 14,
            $y,
            $w,
            false,
            false
        );

        $this->SetX($x + $w);
        $w = 40;
        $x = $this->GetX();
        $this->SetFont('Arial', 'B', 8);
        $this->printCell("CÉDULA: ", $x, $y, $w);
        $this->SetFont('Arial', '', 8);
        $this->printCell($this->mantenimiento->cedula, $x + 13, $y, $w, false, false);

        $this->SetX($x + $w);
        $w = 80;
        $x = $this->GetX();
        $this->SetFont('Arial', 'B', 8);
        $this->printCell("CORREO: ", $x, $y, $w);
        $this->SetFont('Arial', '', 8);
        $this->printCell($cliente->CorreoElectronico, $x + 14, $y, $w, false, false);

        $this->SetX(0);
        $y = $this->GetY();
        $x = $this->GetX();
        $w = 35;

        $this->SetFont('Arial', 'B', 8);
        $this->printCell("TÉLEFONO: ", $x, $y, $w);
        $this->SetFont('Arial', '', 8);
        $this->printCell($cliente->TelefonoDeContactoMovil,
            $x + 17,
            $y,
            $w,
            false,
            false
        );

        $this->SetY($y);
        $this->SetX($x + $w);
    }

    private function printCell($text, $x, $y, $w, $is_header = false, $border = true, $align = 'L')
    {
        $this->SetXY($x + $this->margin_left, $this->margin_top + $y);
        $this->Cell($w, $is_header ? 5 : 5, utf8_decode($text), $border, 0, $is_header ? 'C' : $align, $is_header);
    }

    private function printMultiCell($text, $x, $y, $w, $is_header = false, $h = false)
    {
        $this->SetXY($x + $this->margin_left, $this->margin_top + $y);
        if (!$h && $is_header) {
            $h = 10;
        } else if (!$h && !$is_header) {
            $h = 5;
        }
        $this->MultiCell($w, $h, utf8_decode($text), 1, $is_header ? 'C' : 'L', $is_header);
    }
}
