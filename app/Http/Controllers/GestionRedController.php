<?php

namespace App\Http\Controllers;

use App\Departamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Municipio;
use App\PlataformaDeRed;
use App\PlataformaRedAcceso;
use App\PlataformaRedInstruccion;
use App\Proyecto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GestionRedController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('gestion-red-listar')) {
            return abort(403);
        }

        $proyectos = Proyecto::all();
        $departamentos = Departamento::all();

        $plataformas = PlataformaDeRed::with(['proyecto', 'municipios', 'instruccion', 'acceso',])
            ->buscarPorProyecto(request()->get('proyecto'))->buscarPorDepartamento(request()->get('departamento'))
            ->buscarPorMunicipio(request()->get('municipio'))->paginate(10);
        return view('adminlte::red.gestion.index', compact(['plataformas', 'proyectos', 'departamentos']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can(['gestion-red-crear', 'gestion-red-editar'])) {
            $instrucciones = PlataformaRedInstruccion::all();
            $municipios = [];
            $proyectos = Proyecto::with("municipio")->get();
            $datos_acceso = PlataformaRedAcceso::all();
            $plataforma = null;
            return view('adminlte::red.gestion.create', compact([
                'instrucciones',
                'municipios',
                'proyectos',
                'datos_acceso',
                'plataforma'
            ]));
        }

        return abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        PlataformaRedInstruccionController $instruccionController,
        PlataformaRedAccesoController $datoAccesoController
    ) {
        try {
            if (!Auth::user()->can('gestion-red-crear')) {
                return abort(403);
            }
            DB::beginTransaction();


            $this->validate($request, [
                'nombre' => 'required|string',
                'link' => 'required|string',
                'proyecto' => 'required|integer|exists:Proyectos,ProyectoID',
                'municipios' => 'required|array|min:1',
                'instrucciones' => 'required|integer',
                'datos_acceso' => 'required|integer',
            ]);

            
            $plataforma = new PlataformaDeRed();
            $plataforma->nombre = $request->get('nombre');
            $plataforma->link = $request->get('link');
            $plataforma->instruccion_id = $request->instrucciones != 0 ? $request->instrucciones : null;
            $plataforma->dato_acceso_id = $request->datos_acceso != 0 ? $request->datos_acceso : null;
            $plataforma->proyecto_id = $request->get('proyecto');
            
            if ($plataforma->save()) {
                $plataforma->municipios()->attach($request->get('municipios'));
                if ($request->get('instrucciones') == 0) {
                    $instruccion_id = $instruccionController->store($request, $plataforma->id);
                    if (!$instruccion_id) {
                        DB::rollBack();
                        return redirect()->route('gestion.index')->with('error', 'Problemas al crear la plataforma');
                    } else {
                        $plataforma->instruccion_id = $instruccion_id;
                    }
                }


                if ($request->get('datos_acceso') == 0) {
                    $dato_acceso_id = $datoAccesoController->store($request);
                    if (!$dato_acceso_id) {
                        DB::rollBack();
                        return redirect()->route('gestion.index')->with('error', 'Problemas al crear la plataforma');
                    } else {
                        $plataforma->dato_acceso_id = $dato_acceso_id;
                    }
                }

                $plataforma->save();

                DB::commit();
                return redirect()->route('gestion.index')->with('success', 'Plataforma creada correctamente');
            } else {
                DB::rollBack();
                return redirect()->route('gestion.index')->with('error', 'Problemas al crear la plataforma');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('gestion-red-editar')) {
            $plataforma = PlataformaDeRed::with('municipios:MunicipioId,NombreDepartamento,NombreMunicipio')->findOrFail($id);
            $instrucciones = PlataformaRedInstruccion::all();
            $municipios = [];
            $proyectos = Proyecto::with("municipio")->get();
            $datos_acceso = PlataformaRedAcceso::all();

            return view('adminlte::red.gestion.edit', compact([
                'plataforma',
                'instrucciones',
                'municipios',
                'proyectos',
                'datos_acceso'
            ]));
        }
        return abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PlataformaRedInstruccionController $instruccionController, PlataformaRedAccesoController $datoAccesoController)
    {
        try {
            if (!Auth::user()->can('gestion-red-editar')) {
                return abort(403);
            }
            DB::beginTransaction();


            $this->validate($request, [
                'nombre' => 'required|string',
                'link' => 'required|string',
                'proyecto' => 'required|integer|exists:Proyectos,ProyectoID',
                'municipios' => 'required|array|min:1',
                'instrucciones' => 'required|integer',
                'datos_acceso' => 'required|integer',
            ]);

            $plataforma =   PlataformaDeRed::findOrFail($id);
            $plataforma->nombre = $request->get('nombre');
            $plataforma->link = $request->get('link');
            $plataforma->instruccion_id = $request->instrucciones != 0 ? $request->instrucciones : null;
            $plataforma->dato_acceso_id = $request->datos_acceso != 0 ? $request->datos_acceso : null;
            $plataforma->proyecto_id = $request->get('proyecto');


            if ($plataforma->update()) {
                $plataforma->municipios()->sync($request->get('municipios'));

                if ($request->get('instrucciones') == 0) {
                    $instruccion_id = $instruccionController->store($request, $plataforma->id);

                    if (!$instruccion_id) {
                        DB::rollBack();
                        return redirect()->route('gestion.index')->with('error', 'Problemas al actualizar la plataforma');
                    } else {
                        $plataforma->instruccion_id = $instruccion_id;
                    }
                }

                if ($request->get('datos_acceso') == 0) {
                    $dato_acceso_id = $datoAccesoController->store($request);

                    if (!$dato_acceso_id) {
                        DB::rollBack();
                        return redirect()->route('gestion.index')->with('error', 'Problemas al actualizar la plataforma');
                    } else {
                        $plataforma->dato_acceso_id = $dato_acceso_id;
                    }
                }

                $plataforma->update();

                DB::commit();
                return redirect()->route('gestion.index')->with('success', 'Plataforma actualizada correctamente');
            } else {
                DB::rollBack();
                return redirect()->route('gestion.index')->with('error', 'Problemas al actualizar la plataforma');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('gestion-red-eliminar')) {
            $instruccionController = new PlataformaRedInstruccionController();
            $datoAccesoController = new PlataformaRedAccesoController();
            $plataforma = PlataformaDeRed::findOrFail($id);
            $instruccion_id = $plataforma->instruccion_id;
            $dato_acceso_id = $plataforma->dato_acceso_id;
            if ($plataforma->delete()) {
                $instruccionController->destroy($instruccion_id);
                $datoAccesoController->destroy($dato_acceso_id);
                return redirect()->route('gestion.index')->with('success', 'Plataforma eliminada correctamente');
            } else {
                return redirect()->route('gestion.index')->with('error', 'Error al eliminar la plataforma');
            }
        }

        return abort(403);
    }
}
