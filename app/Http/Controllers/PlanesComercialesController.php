<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\PlanComercial;
use App\Proyecto;
use App\Traits\Planes;

class PlanesComercialesController extends Controller
{
    use Planes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('planes-comerciales-crear')) {

            $plan_comercial = new PlanComercial;
            $plan_comercial->ProyectoId = $request->proyecto;
            $plan_comercial->nombre = $request->nombre;
            $plan_comercial->DescripcionPlan = $request->descripcion;
            $plan_comercial->Estrato = $request->estrato;
            $plan_comercial->VelocidadInternet = $request->velocidad_descarga;
            $plan_comercial->ValorDelServicio = $request->valor;
            $plan_comercial->TipoDePlan = $request->tipo;
            $plan_comercial->Status = $request->estado;

            if ($plan_comercial->save()) {

                $plan_comercial->proyecto_municipio()->attach($request->plan_municipios);

                return redirect()->route('proyectos.show', $request->proyecto)->with('success','Plan agregado correctamente!');
            }else{
                return redirect()->route('proyectos.show', $request->proyecto)->with('error','Error al crear el plan!');
            }

        }else{
            abort(403);
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
        if (Auth::user()->can('planes-comerciales-editar')) {

            $plan_comercial = PlanComercial::select('PlanId','ProyectoId','DescripcionPlan','Estrato','VelocidadInternet','ValorDelServicio','Status','TipoDePlan','nombre')->findOrFail($id);

            $municipios = $plan_comercial->proyecto_municipio;
            
            /*$proyectos = Proyecto::where('Status', 'A')->get();
            $estratos = array(1,2,3,'GENERAL'); 
            $estados = array('estado' => 
                array('nombre' => 'ACTIVO', 'valor' => 'A'), 
                array('nombre' => 'INACTIVO', 'valor' => 'I')
            );            
            $tipos_planes = array('GENERAL', 'TARIFA SOCIAL', 'EMPRESARIAL');*/

            return response()->json([
                'plan_comercial' => $plan_comercial,
                'municipios' => $municipios
                /*'estratos' => $estratos, 
                'estados' => $estados,
                'tipos_planes' => $tipos_planes*/
            ]);

        }else{
            abort(403);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('planes-comerciales-editar')) {
            $plan_comercial = PlanComercial::find($id);
            $plan_comercial->ProyectoId = $request->proyecto;
            $plan_comercial->nombre = $request->nombre;
            $plan_comercial->DescripcionPlan = $request->descripcion;
            $plan_comercial->Estrato = $request->estrato;
            $plan_comercial->VelocidadInternet = $request->velocidad_descarga;
            $plan_comercial->ValorDelServicio = $request->valor;
            $plan_comercial->TipoDePlan = $request->tipo;
            $plan_comercial->Status = $request->estado;

            $municipios_array = array();

            foreach ($plan_comercial->proyecto_municipio as $municipios) {

                $municipios_array[] = strval($municipios->id);
            }


            if ($plan_comercial->save()) {

                #si $municipios_array esta vacio y $request->plan_municipios esta lleno, se quiere agregar municipios al plan.
                if (empty($municipios_array) && !empty($request->plan_municipios)) {
                    $plan_comercial->proyecto_municipio()->attach($request->plan_municipios);
                }

                #si $municipios_array esta lleno y $request->plan_municipios esta vacio, significa que se quiere quitar los municipios de ese plan.
                if (!empty($municipios_array) && empty($request->plan_municipios)) {
                    $plan_comercial->proyecto_municipio()->detach($municipios_array);
                }

                #si $municipios_array esta lleno y $request->plan_municipios esta lleno, se quiere actualizar los municipios del plan.
                if (!empty($municipios_array) && !empty($request->plan_municipios)) {

                    #en el caso cuando se quiere agregar un nuevo municipio
                    $agregar_municipios = array_diff($request->plan_municipios,$municipios_array);

                    #en el caso cuando se quiere quitar un municipio
                    $eliminar_municipios = array_diff($municipios_array,$request->plan_municipios);

                    if (!empty($agregar_municipios)) {
                        $plan_comercial->proyecto_municipio()->attach($agregar_municipios);
                    }

                    if (!empty($eliminar_municipios)) {
                        $plan_comercial->proyecto_municipio()->detach($eliminar_municipios);
                    }
                }
                

                return redirect()->route('proyectos.show', $request->proyecto)->with('success','Plan Actualizado correctamente!');
            }else{
                return redirect()->route('proyectos.show', $request->proyecto)->with('error','Error al actualizar el plan!');
            }

        }else{
            abort(403);
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
        if (Auth::user()->can('planes-comerciales-eliminar')) {

            $plan_comercial = PlanComercial::findOrFail($id);

            $proyecto = $plan_comercial->ProyectoId;

            if ($plan_comercial->delete()) {
                return redirect()->route('proyectos.show', $proyecto)->with('success','Plan eliminado correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al eliminar el plan!');
            }
        }else{
            abort(403);
        }
    }

    public function ajax(Request $request){
        
        $planes = [];

        if($request->ajax()){
            $planes = $this->listar($request->proyecto, $request->estrato, $request->municipio);
        }        

        return response()->json($planes);
    }
    
}
