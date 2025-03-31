<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-users"></i>  Metas
      <div class="btn-group pull-right">
        @permission('proyectos-metas-crear')
          <button type="button" class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addMeta">
            <i class="fa fa-plus"></i>  Agregar          
          </button>
        @endpermission
      </div>
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Nombre</th>
          <th>Descripcion</th>
          <th>Inicio</th>
          <th>Fin</th>                  
          <th>Estado</th>
          <th>Aprob. Interventoria</th>
          <th>Aprob. Supervision</th>
          <th>Total Accesos</th>
          <th style="width:70px;">Accion</th>
        </tr>               
      </thead>
      <tbody>
        <?php $i=0; $total_acesos_generales_meta = 0;?>
        @foreach($proyecto->meta as $meta)
          <tr>
            <td>{{$i+=1}}</td>
            <td>{{$meta->nombre}}</td>
            <td>{{$meta->descripcion}}</td>
            <td>{{$meta->fecha_inicio}}</td>
            <td>{{$meta->fecha_fin}}</td>                           
            <td>{{$meta->estado}}</td>
            <td>{{$meta->fecha_aprobacion_interventoria}}</td>
            <td>{{$meta->fecha_aprobacion_supervision}}</td>
            <td>{{$meta->total_accesos}}</td>
            <td>
              @permission('planes-comerciales-editar')
            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editMeta"  data-id="{{$meta->id}}"><i class="fa fa-edit"></i></button>
            @endpermission

            @permission('planes-comerciales-eliminar')
            <form action="{{route('metas.destroy', $meta->id)}}" method="post" style="display: inline-block;">
                  <input type="hidden" name="_method" value="delete">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
                  <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
              </form>
            @endpermission
            </td>
          </tr>
          <?php $total_acesos_generales_meta +=  $meta->total_accesos;?>
        @endforeach               
      </tbody>
      <tfoot>
        <tr>
          <th colspan="8" class="text-right">TOTAL:</th>
          <td colspan="2">{{$total_acesos_generales_meta}}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>