<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReporteOntFallida;
use Illuminate\Support\Facades\Auth;

class ReporteOntFallidoController extends Controller
{
    public function index ()
    {
        if (!Auth::user()->can('reporte-onts-listar')) {
            return abort(403);
        }
        $reportes = ReporteOntFallida::buscarPorSerial(request()->get("palabra"))
            ->buscarPorCedula(request()->get("palabra"))
            ->paginate(15);
        return view('adminlte::red.onts.reportes.index', compact('reportes'));
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('reporte-onts-eliminar')) {
            return abort(403);
        }
        $reporte = ReporteOntFallida::findOrFail($id);
        if ($reporte->delete()) {
            return redirect()->route('red.reporte.onts')->with('success', 'Reporte ONT eliminado correctamente');
        }
        return redirect()->route('red.reporte.onts')->with('error', 'Error al eliminar el reporte');
    }
}
