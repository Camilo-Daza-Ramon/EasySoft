<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-folder-open-o"></i>  Archivos
        
        <div class="box-tools pull-right">
          @role(['admin', 'comercial', 'agente-noc'])
            @if(count($cliente->archivos) == 0)
              <button class="btn btn-sm" id="sincronizar_fotos"> <i class="fa fa-refresh"></i> <span class="hidden-xs">Sincronizar</span></button>
            @endif
          @endrole

          @permission('clientes-archivos-crear')
            <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addArchivo">
                <i class="fa fa-plus"></i>  <span class="hidden-xs">Agregar</span>
            </div>
          @endpermission
        </div>
        
      </h2>
    </div>
    <div class="panel-body table-responsive">
      <table class="table table-hover">
        <tbody>
          <tr>
            <th style="width: 10px">#</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Tamaño</th>
            <th>Estado</th>
            @role('vendedor')
              <th>Subsanar</th>
            @endrole

            @role(['admin', 'comercial', 'agente-noc'])
            <th>Acciones</th>
            @endrole
          </tr>
          <?php $i=0;?>
           @if(count($cliente->archivos) > 0)
            @foreach($cliente->archivos as $archivo)                    
              <tr>
                <td>{{$i+=1}}</td>
                <td> 
                  <label id="archivo-{{$archivo->id}}" data-toggle="modal" data-target="#modal-attachment" data-tipo="{{$archivo->tipo_archivo}}" data-archivo="{{Storage::url($archivo->archivo)}}" style="cursor: pointer;">{{$archivo->nombre}}</label>
                </td>
                <td>{{$archivo->tipo_archivo}}</td>
                <td>
                  @if(Storage::disk('public')->exists($archivo->archivo))
                    {{number_format((float)((Storage::size('public/' .$archivo->archivo)) / 1e+6), 2, '.', '')}} MB
                  @endif
                </td>
                <td>
                  @if($archivo->estado == 'EN REVISION')
                    <span class="label label-warning">{{$archivo->estado}}</span>
                  @else
                    
                    @if($archivo->estado == 'RECHAZADO')
                      <span class="label label-danger">{{$archivo->estado}}</span>
                    @else
                      {{$archivo->estado}}
                    @endif
                  @endif                          
                </td>
                @role('vendedor')
                  @if($archivo->estado == 'RECHAZADO')
                    <td>
                      @permission('clientes-archivos-editar')
                      <form action="{{route('archivosclientes.update', $archivo->id)}}" role="search" method="POST" class="navbar-form navbar-left" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="cliente_id" value="{{$cliente->ClienteId}}">
                        <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }}">
                          <input type="file" class="form-control input-sm" name="archivo" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i>  Subir</button>
                      </form>
                      @endpermission
                    </td>
                  @else
                  <td></td>
                  @endif
                @endrole
                @permission('clientes-archivos-eliminar')
                <td>
                  <form action="{{route('archivosclientes.delete', $archivo->id)}}" method="post">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
                @endpermission
              </tr>
            @endforeach

            <div class="modal fade" id="modal-attachment" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-body">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <div id="presentacion">
                        
                      </div> 
                    </div>
                  </div>
                </div>
              </div>
          @endif
        </tbody>
      </table>
    </div>
    @if($cliente->Status == 'RECHAZADO')
      <div class="panel-footer">
        <h3>Motivo Rechazo:</h3>
        <span class="text-mute"><b>{{$cliente->MotivoDeRechazo}}</b></span>
        <p>{{$cliente->ComentarioRechazo}}</p>
      </div>
    @endif

    @permission('clientes-archivos-crear') 
        @include('adminlte::clientes.partials.archivos.add')
    @endpermission
  </div>
</div>

@section('mis_scripts1')
<script type="text/javascript">
  $('#modal-attachment').on('show.bs.modal', function (event) {
      var a = $(event.relatedTarget) // Button that triggered the modal
      var tipo = a.data('tipo');
      var recipient = a.data('archivo') // Extract info from data-* attributes
      
      var modal = $(this)
      if (tipo == 'pdf') {
        modal.find('#presentacion').html('<iframe src="'+ recipient +'" width="100%" height="600" style="height: 85vh;"></iframe>');        
      }else{        
        modal.find('#presentacion').html('<img src="'+ recipient +'" id="img-attachment" class="img-responsive" width="100%">');
      }
  });
  
</script>
@endsection