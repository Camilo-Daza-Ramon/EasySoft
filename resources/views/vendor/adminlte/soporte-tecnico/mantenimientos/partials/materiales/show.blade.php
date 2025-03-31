<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-wrench"></i>  Materiales - Insumos
    @if($mantenimiento->estado != 'CERRADO')
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span id="icon-opciones" class="fa fa-gears"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          @permission('mantenimientos-materiales-crear')
          <li>
            <a data-id_mantenimiento="{{$mantenimiento_id}}" id="btn-add-material" href="#" data-toggle="modal" data-target="#addMaterial">
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
          <th>Cantidad</th>
          <th>Uni. Medida</th>
          <th>Descripcion</th>
          <th>Acciones</th>
        </tr>                   
      </thead>
      <tbody class="products-list product-list-in-box">
        @if($mantenimiento->materiales->count() > 0)
          <?php  $i = 0; ?>
          @foreach($mantenimiento->materiales as $material)
          <tr class="product-info">
            <td>{{$i+=1}}</td>
            <td>{{$material->Cantidad}}</td>
            <td>{{$material->Unidad}}</td>
            <td >
              
              <span class="product-title">{{$material->inventario->Descripcion}}</span>
              <span class="product-description text-lowercase">{{$material->Descripcion}}</span>
              
            </td>
            <td>
            @if($mantenimiento->estado != 'CERRADO')
              @permission('mantenimientos-materiales-editar')
              <button class="btn btn-xs btn-primary" onclick="editarMaterial('{{$mantenimiento_id}}', '{{$material->MaterialMantEqId}}')" data-toggle="modal" data-target="#addMaterial">
                <i class="fa fa-edit"></i>
              </button>
              @endpermission
              @permission('mantenimientos-materiales-eliminar')
                <form action="{{route('mantenimientos.materiales.destroy', [$mantenimiento_id, $material->MaterialMantEqId])}}" method="post" style="display: inline-block;">
                  <input type="hidden" name="_method" value="delete">
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
            <td colspan="7" class="text-center text-muted">NO HAY REGISTROS</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>