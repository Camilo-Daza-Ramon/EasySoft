<div class="panel panel-default">
    <div class="panel-heading bg-blue">
        <h2 class="panel-title"><i class="fa fa fa-check-square-o"></i> Propiedades

            <div class="btn-group pull-right">
                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle"
                    data-toggle="dropdown">
                    <span id="icon-opciones" class="fa fa-gears"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @permission('infraestructura-propiedades-crear')
                    <li>
                        <a href="#" data-infra_id="{{$infraestructura->id}}" id="btn-add-propiedades" data-toggle="modal" data-target="#addPropiedades">
                            <i class="fa fa-plus"></i> Agregar
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
                    <th>Valor</th>
                    <th>Unidad de Medida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($infraestructura->propiedades->count() > 0)
                <?php $i = 0; ?>
                @foreach($infraestructura->propiedades as $prop)
                <tr>
                    <td>{{$i+=1}}</td>
                    <td>{{$prop->nombre}}</td>
                    <td>{{$prop->valor}}</td>
                    <td>{{$prop->unidad_medida}}</td>
                    <td>
                        @permission('infraestructura-propiedades-editar')
                        <button class="btn btn-xs btn-primary" onclick="editarPropiedad('{{$infraestructura->id}}', '{{$prop->id}}')" data-toggle="modal" data-target="#addPropiedades">
                            <i class="fa fa-edit"></i>
                        </button>
                        @endpermission
                        @permission('infraestructura-propiedades-eliminar')
                        <form action="{{route('infraestructuras.propiedades.destroy', [$infraestructura->id, $prop->id])}}" method="post" style="display: inline-block;">
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
                    <td colspan="6" class="text-center text-muted">NO HAY REGISTROS</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>