<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-suitcase"></i>  Planes Comerciales
      <div class="pull-right">
        @permission('planes-comerciales-crear')
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addPlanComercial">
            <i class="fa fa-plus"></i>  Agregar
        </div>
        @endpermission
      </div>
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Estrato</th>
          <th>Vel. Internet</th>
          <th>Valor</th>
          <th>Municipio</th>
          <th>Estado</th>
          <th style="width:70px;">Accion</th>
        </tr>
        
      </thead>
      <tbody>
        <?php $i = 0; ?>
        @foreach($planes_comerciales as $plan_comercial)
          <tr class="products-list">
            <td>{{$i+=1}}</td>
            <td>
              {{$plan_comercial->nombre}}
              <span class="product-description">{{$plan_comercial->DescripcionPlan}}</span>
            </td>
            <td>{{$plan_comercial->TipoDePlan}}</td>
            <td>{{$plan_comercial->Estrato}}</td>
            <td>{{$plan_comercial->VelocidadInternet}} MB</td>
            <td>
              ${{number_format($plan_comercial->ValorDelServicio,0,',','.')}}
              
            </td>
            <td>
              @foreach($plan_comercial->proyecto_municipio as $proyecto_municipio)
                <span class="badge bg-default">{{$proyecto_municipio->municipio->NombreMunicipio}}</span>
              @endforeach
            </td>
            <td>{{$plan_comercial->Status}}</td>
            <td>
              @permission('planes-comerciales-editar')
              <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editPlanComercial"  data-id="{{$plan_comercial->PlanId}}"><i class="fa fa-edit"></i></button>
              @endpermission

              @permission('planes-comerciales-eliminar')
              <form action="{{route('planes.destroy', $plan_comercial->PlanId)}}" method="post" style="display: inline-block;">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                </form>
              @endpermission

            </td>
          </tr>

        @endforeach                   
      </tbody>
    </table>
  </div>
</div>