<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-image"></i>  Fotografias
    @if($mantenimiento->estado != 'CERRADO')
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span id="icon-opciones" class="fa fa-gears"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          @permission('mantenimientos-archivos-crear')
          <li>
            <a id="btn-agregar-archivo"  data-id_mantenimiento="{{$mantenimiento_id}}" href="#" data-toggle="modal" data-target="#addFoto">
              <i class="fa fa-plus"></i>  Agregar
            </a>
          </li>
          @endpermission          
        </ul>
      </div>
    @endif
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Tamaño</th>
          <th>Acciones</th>
        </tr>                   
      </thead>
      <tbody>
        @if($mantenimiento->archivos->count() > 0)
          <?php  $i = 0; ?>
          @foreach($mantenimiento->archivos as $archivo)
          <tr>
            <td>{{$i+=1}}</td>
            <td>
              <a href="{{Storage::url($archivo->archivo)}}" target="_black">{{$archivo->nombre}}</a>
            </td>
            <td>{{$archivo->tipo_archivo}}</td>
            <td> MB</td>
            <td>
            @if($mantenimiento->estado != 'CERRADO')
              @permission('mantenimientos-archivos-editar')
              <button onclick="editarArchivo('{{$archivo->id}}')" id="btn-editar-archivo-{{$archivo->id}}" class="btn btn-xs btn-primary" data-id_archivo="{{$archivo->id}}" data-id_mantenimiento="{{$mantenimiento_id}}" data-nombre="{{$archivo->nombre}}" data-toggle="modal" data-target="#addFoto">
                <i class="fa fa-edit"></i>
              </button>
              @endpermission
              @permission('mantenimientos-archivos-eliminar')
                <form action="{{route('mantenimientos.archivos.destroy', [$mantenimiento_id, $archivo->id])}}" method="post" style="display: inline-block;">
                  <input type="hidden" name="_method" value="delete">
                  <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">

                  <input type="hidden" name="_token" value="{{csrf_token()}}">
                  <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                </form>
              @endpermission
            @endif
            </td>
          </tr>
          @endforeach
        @else
        <tr>
          <td colspan="5" class="text-center text-muted">NO HAY REGISTROS</td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>