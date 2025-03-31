<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-folder-open-o"></i>  Archivos
        
        <div class="box-tools pull-right">
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addArchivo">
              <i class="fa fa-plus"></i>  <span class="hidden-xs">Agregar</span>
          </div>
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
            <th>Acciones</th>
          </tr>
          <?php $i=0; $ids = 0;?>
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
                  @else
                    NO EXISTE EL ARCHIVO
                  @endif
                </td>
                <td>
                  @if($archivo->estado == 'EN REVISION')
                      <?php $ids += 1; ?>
                      <div class="btn-group">
                        <button type="button" id="estado-{{$archivo->id}}" class="btn btn-warning btn-xs">{{$archivo->estado}}</button>
                        <button type="button" id="toggle-{{$archivo->id}}" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                          <li><a onclick="actualizar_archivo('APROBADO', {{$archivo->id}});">Aprobar</a></li>
                          <li><a onclick="actualizar_archivo('RECHAZADO', {{$archivo->id}});">Rechazar</a></li>
                        </ul>
                      </div>                    
                  @else
                    
                    @if($archivo->estado == 'RECHAZADO')
                      <span class="label label-danger">{{$archivo->estado}}</span>
                    @else
                      {{$archivo->estado}}
                    @endif

                  @endif                          
                </td>
                <td>
                  <form action="{{route('archivosclientes.delete', $archivo->id)}}" method="post">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
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
    
    <div class="panel-footer">
      <h3>Motivo Rechazo:</h3>
      <span class="text-mute"><b>{{$cliente->MotivoDeRechazo}}</b></span>
      <p>{{$cliente->ComentarioRechazo}}</p>
    </div>
        @include('adminlte::clientes.partials.archivos.add')
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

  var ids = {{$ids}};

  if (ids == 0) {
    console.log(ids);
    $('#auditar').removeAttr('disabled');
  }

  function actualizar_archivo(estado, id){     

    var parameters = {
      estado : estado,
      '_token' :  $('input:hidden[name=_token]').val(),
      '_method' : 'PUT'
    };      

    $.post("/archivosclientes/" + id,parameters, function(data){
      if(data.result == 'success'){
        $('#estado-'+ id).removeClass('btn-warning');
        $('#toggle-'+id).removeClass('btn-warning');

        if (estado == 'APROBADO') {
          $('#estado-'+ id).addClass('btn-success');
          $('#toggle-'+id).addClass('btn-success');
        }else{
          $('#estado-'+ id).addClass('btn-danger');
          $('#toggle-'+id).addClass('btn-danger');
        }

        $('#estado-'+ id).text(estado);

        ids = ids - 1;
        if (ids == 0) {
          console.log(ids);
          $('#auditar').removeAttr('disabled');
        }
      }else{
        toastr.options.positionClass = 'toast-bottom-right';
	  		toastr.error(data.mensaje);
      }
    });
  }

  $('#estado').on('change', function(){
    if ($(this).val() == 'APROBADO') {
      $('#motivo_rechazo').hide(2000);
      $('#observaciones').hide(2000);
    }else{
      $('#motivo_rechazo').show(2000);
      $('#observaciones').show(2000);
    }
  });
</script>
@endsection