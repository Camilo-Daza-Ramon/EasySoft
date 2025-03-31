<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-tag"></i>  Tipos de Beneficiarios
      <div class="pull-right">
        @permission('proyectos-tipos-beneficiarios-crear')
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addTipoBeneficiario">
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
          <th>Descripcion</th>
          <th>Estado</th>          
          <th style="width:70px;">Accion</th>
        </tr>
        
      </thead>
      <tbody>

        @if(!empty($proyecto->tipos_beneficiarios))
            <?php $i = 0; ?>
            @foreach($proyecto->tipos_beneficiarios as $tipo_beneficiario)
            <tr>
                <td>{{$i+=1}}</td>
                <td>{{$tipo_beneficiario->nombre}}</td>
                <td>{{$tipo_beneficiario->descripcion}}</td>
                <td>{{$tipo_beneficiario->estado}}</td>
                <td>
                    @permission('proyectos-tipos-beneficiarios-editar')
                        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editTipoBeneficiario"  data-id="{{$tipo_beneficiario->id}}" data-proyecto-id="{{$tipo_beneficiario->proyecto_id}}"><i class="fa fa-edit"></i></button>
                    @endpermission

                    @permission('proyectos-tipos-beneficiarios-eliminar')
                        <form action="{{route('proyectos.tipos-beneficiarios.destroy', [$proyecto->ProyectoID, $tipo_beneficiario->id])}}" method="post" style="display: inline-block;">
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