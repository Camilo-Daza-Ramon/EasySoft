<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\HistorialFacturaPagoV;
use App\Permission;
use App\Role;
use App\User;
use Error;
use Excel;

class ExportarController extends Controller
{

    public function estado($usuario)
    {


        Excel::create('Clientes', function ($excel) use ($usuario) {

            $excel->sheet('Productos', function ($sheet) use ($usuario) {

                $datos = "";

                $datos = Cliente::select('Identificacion', 'MotivoDeRechazo', 'ComentarioRechazo', 'DireccionDeCorrespondencia', 'Barrio', 'NombreEdificio_o_Conjunto', 'Municipios.NombreMunicipio', 'Municipios.NombreDepartamento', 'Fecha', 'Clientes.Status')->where([['user_id', $usuario], ['Clientes.Status', 'RECHAZADO']])
                    ->LeftJoin('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                    ->get();

                $sheet->fromArray($datos);
            });
        })->export('xls');
    }


    public function efecty()
    {

        $filename = "efecty.txt";
        $handle = fopen($filename, 'w+');

        fputs($handle, '"01"|REFERENCIA|VALOR|FECHA|NOMBRES|APELLIDOS|CAMPO3|CAMPO4|CAMPO5' . chr(13) . chr(10));

        $facturas = Cliente::selectRaw("Clientes.Identificacion as cedula, 
            CASE 
                WHEN historial_factura_pagoV.total_deuda <= 0 THEN 
                    PlanesComerciales.ValorDelServicio 
                ELSE historial_factura_pagoV.total_deuda 
            END as total, 
            Clientes.NombreBeneficiario as nombre, 
            Clientes.Apellidos as apellido,
            Proyectos.NumeroDeProyecto as proyecto, 
            Municipios.NombreMunicipio as municipio, 
            Municipios.NombreDepartamento as departamento")
            ->join('historial_factura_pagoV', 'Clientes.ClienteId', '=', 'historial_factura_pagoV.ClienteId')
            ->join('Proyectos', 'Proyectos.ProyectoID', '=', 'Clientes.ProyectoId')
            ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
            ->join('PlanesComerciales', 'Clientes.PlanComercial', '=', 'PlanesComerciales.PlanId')
            ->where([['Clientes.Status', 'ACTIVO'], ['Municipios.Status', 'A']])
            ->whereNotIn('Clientes.Identificacion', [1140838564, 900365750, 824002355, 1109001326, 1070949893, 1074188063, 1094162828, 28742462, 24197062, 20897886, 1074185700, 1070707095, 1097890489, 20660025, 1055834092, 51738866, 71411234, 1059814012, 1035850670, 1109006321, 1110503169, 1105670628, 1109004817, 28716537, 1073155220, 21176721, 65713225, 28823134, 65718172, 28509365, 20405223, 65715595, 65710911, 5944846, 93294901, 93293912, 1104703958, 65716613, 1007252715, 65714775, 65711494, 65710775, 28816982, 1104701949, 40205635, 93124171, 1121890114, 93294694, 93298965, 28824233, 65716764, 1104697260, 1007404003, 93286923, 65718653, 1104704094, 1002652671, 31886488, 1055834834, 24363548, 24370844, 1055837051, 24364254, 24372440, 1055837614, 1002642334]);

        $mora = HistorialFacturaPagoV::selectRaw("Clientes.Identificacion as cedula, 
            total_deuda as total, 
            Clientes.NombreBeneficiario as nombre, 
            Clientes.Apellidos as apellido, 
            Proyectos.NumeroDeProyecto as proyecto, 
            Municipios.NombreMunicipio as municipio, 
            Municipios.NombreDepartamento as departamento")
            ->join('Clientes', 'historial_factura_pagoV.ClienteId', '=', 'Clientes.ClienteId')
            ->join('Proyectos', 'Proyectos.ProyectoID', '=', 'Clientes.ProyectoId')
            ->join('ProyectosUbicaciones', 'Clientes.UbicacionId', '=', 'ProyectosUbicaciones.UbicacionId')
            ->join('Municipios', 'ProyectosUbicaciones.MunicipioId', '=', 'Municipios.MunicipioId')
            ->union($facturas)
            ->where('total_deuda', '>', 0)
            ->whereNotNull('total_deuda')
            ->whereIn('Clientes.EmpresaFacturaId', [1, 6])
            ->whereNotIn('Clientes.Identificacion', [1140838564, 900365750, 824002355])
            ->whereNotIn('Clientes.Status', ['ACTIVO'])
            ->get();

        $pymes = array(
            array('identificacion' => 1140838564, 'nombre' => 'ROGER ENRIQUE', 'apellido' => 'SANCHEZ VARELA', 'valor' => 107100, 'proyecto' => 'PYMES'),
            array('identificacion' => 900365750, 'nombre' => 'INVERSIONES', 'apellido' => 'MERK JAGUA SAS', 'valor' => 150000, 'proyecto' => 'PYMES'),
            array('identificacion' => 824002355, 'nombre' => 'CONCEJO', 'apellido' => 'MUNICIPAL DE LA JAGUA DE IBIRICO', 'valor' => 202300, 'proyecto' => 'PYMES')
        );

        $total = 0;
        $cantidad = 0;

        $caracteres_especiales = array(
            "á" => "a",
            "é" => "e",
            "í" => "i",
            "ó" => "o",
            "ú" => "u",
            "ñ" => "n",
            "Á" => "A",
            "É" => "E",
            "Í" => "I",
            "Ó" => "O",
            "Ú" => "U",
            "Ñ" => "N"
        );

        foreach ($mora as $factura) {
            fputs($handle, '"02"|"' . $factura->cedula . '"|' . number_format($factura->total, 2, '.', '') . '|' . date('Y-m-d h:i:s') . '|"' . str_replace(array_keys($caracteres_especiales), array_values($caracteres_especiales), $factura->nombre) . '"|"' . str_replace(array_keys($caracteres_especiales), array_values($caracteres_especiales), $factura->apellido) . '"|"' . $factura->proyecto . '"|"' . str_replace(array_keys($caracteres_especiales), array_values($caracteres_especiales), $factura->departamento) . '"|"' . str_replace(array_keys($caracteres_especiales), array_values($caracteres_especiales), $factura->municipio) . '"' . chr(13) . chr(10));

            $total += number_format($factura->total, 2, '.', '');
            $cantidad += 1;
        }

        foreach ($pymes as $pyme) {
            fputs($handle, '"02"|"' . $pyme["identificacion"] . '"|' . number_format($pyme["valor"], 2, '.', '') . '|' . date('Y-m-d h:i:s') . '|"' . $pyme["nombre"] . '"|"' . $pyme["apellido"] . '"|"' . $pyme["proyecto"] . '"|""|""' . chr(13) . chr(10));

            $total += $pyme["valor"];
            $cantidad += 1;
        }

        fputs($handle, '"03"|' . $cantidad . '|' . number_format($total, 2, '.', '') . '|' . date('Y-m-d h:i:s') . '|||||' . chr(13) . chr(10));


        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return response()->download($filename, 'Efecty.txt', $headers);
    }

    public function exportarRoles(Request $request)
    {
        if (!Auth::user()->can('roles-exportar')) {
            abort(403);
            return;
        }

        $palabra = $request->get('palabra');
        $roles = $palabra != null 
            ? Role::where('name', 'like', '%' . request()->get('palabra') . '%')->get()
            : Role::all();
        
        if ($roles == null || $roles->count() == 0) {
            return redirect()->back()->with('warning', 'No tienes registros para exportar');
        }

        Excel::create('roles', function ($excel) use ($roles) {

            $excel->sheet('lista-roles', function ($sheet) use ($roles) {

                $datos = [];
                foreach ($roles as $rol) {
                    $i = 0; 
                    $datos[] = array(
                        'NOMBRE' => $rol->name,
                        'NOMBRE EN PANTALLA' => $rol->display_name,
                        'DESCRIPCION' => $rol->description,
                        'PERMISOS' => $rol->perms->reduce(function ($carry, $item) use  ($rol, &$i){
                            $carry .= $item->display_name;
                            if (count($rol->perms) - 1 != $i) {
                                $carry .= ' | ';
                            }
                            $i++;
                            return $carry;
                        })
                    );
                }

                $sheet->fromArray($datos, true, 'A1', true);
            });
        })->export('xlsx');
    }

    public function exportarPermisos(Request $request)
    {
        if (!Auth::user()->can('permisos-exportar')) {
            abort(403);
            return;
        }

        $palabra = $request->get('palabra');
        $permisos = $palabra != null 
            ? Permission::where('display_name', 'like', '%' . request()->get('palabra') . '%')->get()
            : Permission::all();
        
        if ($permisos == null || $permisos->count() == 0) {
            return redirect()->back()->with('warning', 'No tienes registros para exportar');
        }

        Excel::create('permisos', function ($excel) use ($permisos) {

            $excel->sheet('permisos-roles', function ($sheet) use ($permisos) {

                $datos = [];
                foreach ($permisos as $permiso) {

                    $i = 0; 

                    $datos[] = array(
                        'NOMBRE' => $permiso->nombre,
                        'NOMBRE EN PANTALLA' => $permiso->display_name,
                        'DESCRIPCION' => $permiso->description,
                        'ROLES' => $permiso->roles->reduce(function ($carry, $item) use  ($permiso, &$i){
                            $carry .= $item->display_name;
                            if (count($permiso->roles) - 1 != $i) {
                                $carry .= ' | ';
                            }
                            $i++;
                            return $carry;
                        })
                    );
                }

                $sheet->fromArray($datos, true, 'A1', true);
            });
        })->export('xlsx');
    }

}
