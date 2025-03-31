<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-cloud"></i>  API de Facturación
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            <span id="icon-opciones" class="fa fa-gears"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          @if(empty($proyecto->facturacion_api))
                @permission('facturacion-electronica-api-crear')
                <li>
                    <a href="#" data-toggle="modal" data-target="#addAPI">
                        <i class="fa fa-plus"></i>  Agregar
                    </a>
                </li>
                @endpermission
            @else
                @permission('facturacion-electronica-api-editar')
                <li>
                    <a href="#" data-toggle="modal" data-target="#editAPI">
                        <i class="fa fa-edit"></i>  Editar
                    </a>
                </li>
                @endpermission
                @permission('facturacion-electronica-api-eliminar')
                <li>
                  <a href="#" id="eliminar-api">
                    <i class="fa fa-trash-o"></i>  Eliminar 
                  </a>
                   
                </li>
                @endpermission
            @endif
        </ul>
      </div>
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    <table class="table">
      <tbody>
        @if(!empty($proyecto->facturacion_api))
        <tr>
          <th>URL</th>
          <td>{{$proyecto->facturacion_api->url_api}}</td>
        </tr>
        <tr>
          <th>TOKEN</th>
          <td>{{$proyecto->facturacion_api->token_identificador}}</td>
        </tr>
        <tr>
          <th>CONTROLADOR</th>
          <td>{{$proyecto->facturacion_api->controlador}}</td>
        </tr>
        <tr>
          <th>ACCION</th>
          <td>{{$proyecto->facturacion_api->accion}}</td>
        </tr>
        @else
          Pendiente por Asignar.
        @endif
      </tbody>
    </table>
  </div>
</div>