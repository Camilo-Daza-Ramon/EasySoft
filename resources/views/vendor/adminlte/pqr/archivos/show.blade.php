<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-image"></i>  Evidencias
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span id="icon-opciones" class="fa fa-gears"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          @permission('pqrs-archivos-crear')
          <li>
            <a href="#" data-toggle="modal" data-target="#addFoto">
              <i class="fa fa-plus"></i>  Agregar
            </a>
          </li>
          @endpermission          
        </ul>
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
          <th>Tamaño</th>
          <th>Acciones</th>
        </tr>                   
      </thead>
      <tbody>

      @if($pqr->archivos->count() > 0)
        <?php  $i = 0; ?>
        @foreach($pqr->archivos as $archivo)
        <tr>
          <td>{{$i+=1}}</td>
          <td>
            <a href="{{Storage::url($archivo->ruta)}}" target="_black">{{$archivo->Comentario}}</a>
          </td>
          <td>{{$archivo->Tipo}}</td>
          <td> MB</td>
          <td>
            <a href="{{Storage::url($archivo->ruta)}}" class="btn btn-xs btn-default" title="Descargar" download><i class="fa fa-download"></i></a>
            @permission('pqrs-archivos-eliminar')
              <form action="{{route('pqr.archivos.destroy', [$pqr->PqrId, $archivo->PqrArcId])}}" method="post" style="display: inline-block;">
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
        <td class="text-muted text-center" colspan="5">NO HAY REGISTROS</td>
      </tr>
      @endif
      </tbody>
    </table>
  </div>
</div>