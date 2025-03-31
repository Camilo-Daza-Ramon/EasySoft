<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-list"></i>  Detalles
      <div class="pull-right">
        @permission('proyectos-editar')
          <a href="{{route('proyectos.edit', $proyecto->ProyectoID)}}" class="btn btn-default float-bottom btn-sm">
            <i class="fa fa-edit"></i>  Editar          
          </a>
        @endpermission
      </div>
    </h2>
  </div> 
  <div class="panel-body">
    <table class="table">
      <tr>
        <th>Proyecto</th>
        <td>{{$proyecto->NumeroDeProyecto}}</td>
        <th># Contrato</th>
        <td>{{$proyecto->NumeroDeContrato}}</td>
      </tr>
      <tr>
        <th>Descripción</th>
        <td colspan="3">{{$proyecto->DescripcionProyecto}}</td>
      </tr>
      <tr>
        <th>Vigencia</th>
        <td>{{$proyecto->vigencia}} MESES</td>
        <th>Fecha de Finalización del Proyecto</th>
        <td>{{$proyecto->fecha_fin_proyecto}}</td>        
      </tr>
      <tr>
        <th>Tipo Facturación</th>
        <td>{{$proyecto->tipo_facturacion}}</td>
        <th>Día Corte Facturación</th>
        <td>{{$proyecto->dia_corte_facturacion}} de cada mes.</td>                            
      </tr>                                     
      <tr>
        <th>Limite Meses en Mora</th>
        <td>{{$proyecto->limite_meses_mora}}</td>
        <th>% Interes Mora</th>
        <td>{{$proyecto->porcentaje_interes_mora}}</td>
      </tr>
      <tr>
        <th>Clausula de Permanencia</th>
        <td>{{($proyecto->clausula_permanencia) ? 'SI':'NO'}}</td>
        <th>Declaración juramentada de nuevo usuario?</th>
        <td>{{($proyecto->acta_juramentada) ? 'SI':'NO'}}</td>
      </tr>
      <tr>        
        <th>Estado</th>
        <td>
          @if($proyecto->Status == 'A')
            <span class="label label-success">ACTIVO</span>
          @else
            <span class="label label-default">INACTIVO</span>
          @endif
        </td> 
      </tr>
      <tr>
        <th>Condiciones Del Plan</th>
        <td colspan="3">
          <p class="text-justify">{{$proyecto->condiciones_plan}}</p>
        </td>
      </tr>
      <tr>
        <th>Condiciones del Servicio</th>
        <td colspan="3">
          <p class="text-justify">{{$proyecto->condiciones_servicio}}</p>
        </td>
      </tr>
    </table>
  </div>
</div>