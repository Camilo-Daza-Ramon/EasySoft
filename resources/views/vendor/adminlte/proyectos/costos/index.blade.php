<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-dollar"></i>  Costos
      <div class="btn-group pull-right">
        @permission('proyectos-costos-crear')
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addCosto">
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
          <th>Concepto</th>
          <th>Descripcion</th>
          <th>Iva</th>
          <th>Valor</th>
          <th>Acciones</th>               
        </tr>                   
      </thead>
      <tbody>
        @foreach($proyecto->costo as $costo)
        <tr>                      
          <td>{{$costo->concepto}}</td>
          <td>{{$costo->descripcion}}</td>
          <td>{{$costo->iva}}</td>
          <td>${{number_format($costo->valor,0,',','.')}}</td>
          <td>
            @permission('proyectos-costos-editar')
              <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editCosto"  data-id="{{$costo->id}}"><i class="fa fa-edit"></i></button>
            @endpermission

            @permission('proyectos-costos-eliminar')
              <form action="{{route('proyectos-costos.destroy', $costo->id)}}" method="post" style="display: inline-block;">
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