<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-tags"></i>  Clausula de Permanencia
      <div class="btn-group pull-right">
        @permission('proyecto-clausula-crear')
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addClausula">
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
          <th>Mes</th>
          <th>Valor</th>
          <th>Acciones</th>                   
        </tr>                   
      </thead>
      <tbody>
        @foreach($proyecto->clausula as $clausula)
        <tr>                      
          <td>MES {{$clausula->numero_mes}}</td>
          <td>${{number_format($clausula->valor,0,',','.')}}</td>
          <td>
            @permission('proyectos-clausulas-editar')
              <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editClausula"  data-id="{{$clausula->id}}"><i class="fa fa-edit"></i></button>
            @endpermission

            @permission('proyectos-clausulas-eliminar')
              <form action="{{route('proyectos-clausulas.destroy', $clausula->id)}}" method="post" style="display: inline-block;">
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