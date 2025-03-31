<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-map-marker"></i>  Municipios
      <div class="btn-group pull-right">
        @permission('proyectos-municipios-crear')
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addMunicipio">
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
          <th>Municipio</th>
          <th>Departamento</th>
          <th>Meta</th>
          <th>Total Accesos</th>
          <th>Acción</th>
        </tr>        
      </thead>
      <tbody>
        <?php $i = 0; $total_accesos_meta = 0; ?>
        @foreach($proyecto->proyecto_municipio as $proyecto_municipio)
          @if(count($proyecto_municipio->meta) > 0)
            @foreach($proyecto_municipio->meta as $meta)
            <tr>
              <td>{{$i+=1}}</td>
              <td>{{$proyecto_municipio->municipio->NombreMunicipio}}</td>
              <td>{{$proyecto_municipio->municipio->NombreDepartamento}}</td>
              <td>{{$meta->meta->nombre}}</td>
              <td>{{$meta->total_accesos}}</td>
              <td>
                @permission('proyectos-municipios-editar')
                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editMunicipio"  data-id="{{$proyecto_municipio->id}}" data-meta-id="{{$meta->id}}"><i class="fa fa-edit"></i></button>
                @endpermission

                @permission('proyectos-municipios-eliminar')
                <form action="{{route('proyectos-municipios.destroy', $proyecto_municipio->id)}}" method="post" style="display: inline-block;">
                      <input type="hidden" name="_method" value="delete">
                      <input type="hidden" name="_token" value="{{csrf_token()}}">
                      <input type="hidden" name="meta_id" value="{{$meta->id}}">

                      <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                  </form>
                @endpermission
              </td>
            </tr>
            <?php $total_accesos_meta +=  $meta->total_accesos;?>
            @endforeach
          @else
            <tr>
              <td>{{$i+=1}}</td>
              <td>{{$proyecto_municipio->municipio->NombreMunicipio}}</td>
              <td>{{$proyecto_municipio->municipio->NombreDepartamento}}</td>
              <td></td>
              <td></td>
              <td>
                @permission('proyectos-municipios-editar')
                <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editMunicipio"  data-id="{{$proyecto_municipio->id}}"><i class="fa fa-edit"></i></button>
                @endpermission

                @permission('proyectos-municipios-eliminar')
                <form action="{{route('proyectos-municipios.destroy', $proyecto_municipio->id)}}" method="post" style="display: inline-block;">
                      <input type="hidden" name="_method" value="delete">
                      <input type="hidden" name="_token" value="{{csrf_token()}}">
                      <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                  </form>
                @endpermission
              </td>
            </tr>
          @endif
          
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-right">TOTAL:</th>
          <td colspan="2">{{$total_accesos_meta}}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>