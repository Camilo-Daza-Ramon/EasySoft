<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-tag"></i>  Documentacion
      <div class="pull-right">
        @permission('proyectos-documentacion-crear')
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addDocumentacion">
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
          <th>Alias</th>
          <th>Descripcion</th>
          <th>Coordenadas</th>
          <th>Tipo</th>
          <th>Estado</th>          
          <th style="width:70px;">Accion</th>
        </tr>
        
      </thead>
      <tbody>

        @if(!empty($proyecto->documentacion))
            <?php $i = 0; ?>
            @foreach($proyecto->documentacion as $documentacion)
            <tr>
                <td>{{$i+=1}}</td>
                <td>{{$documentacion->alias}}</td>
                <td>{{$documentacion->descripcion}}</td>
                <td>{{($documentacion->coordenadas)? 'SI' : ''}}</td>
                <td>{{$documentacion->tipo}}</td>
                <td>{{$documentacion->estado}}</td>
                <td>
                    @permission('proyectos-documentacion-editar')
                        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editDocumentacion"  data-id="{{$documentacion->id}}" data-proyecto-id="{{$documentacion->proyecto_id}}"><i class="fa fa-edit"></i></button>
                    @endpermission

                    @permission('proyectos-documentacion-eliminar')
                        <form action="{{route('proyectos.documentacion.destroy', [$proyecto->ProyectoID, $documentacion->id])}}" method="post" style="display: inline-block;">
                            <input type="hidden" name="_method" value="delete">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                        </form>
                    @endpermission
                </td>
            </tr>

            @endforeach  
        @else
            <tr>
                <td colspan="5"> <p class="text-big text-gray text-center">NO HAY REGISTROS</p></td>
            </tr>
        @endif                 
      </tbody>
    </table>
  </div>
</div>