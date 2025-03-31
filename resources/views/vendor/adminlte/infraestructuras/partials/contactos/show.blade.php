<div class="panel panel-default">
    <div class="panel-heading bg-blue">
        <h2 class="panel-title"><i class="fa fa-users"></i> Contactos

            <div class="btn-group pull-right">
                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle"
                    data-toggle="dropdown">
                    <span id="icon-opciones" class="fa fa-gears"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @permission('infraestructura-contactos-crear')
                    <li>
                        <a href="#" data-infra_id="{{$infraestructura->id}}" id="btn-add-contactos" data-toggle="modal" data-target="#addContactos">
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
                    <th>Celular</th>
                    <th>Teléfono</th>
                    <th>Cargo Presentativo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($infraestructura->contactos->count() > 0)
                <?php $i = 0; ?>
                @foreach($infraestructura->contactos as $contacto)
                <tr>
                    <td>{{$i+=1}}</td>
                    <td>{{$contacto->nombre}}</td>
                    <td>{{$contacto->celular}}</td>
                    <td>{{$contacto->telefono}}</td>
                    <td>{{$contacto->cargo_presentativo}}</td>
                    <td>
                        @permission('infraestructura-contactos-editar')
                        <button class="btn btn-xs btn-primary" onclick="editarContacto('{{$infraestructura->id}}', '{{$contacto->id}}')" data-toggle="modal" data-target="#addContactos">
                            <i class="fa fa-edit"></i>
                        </button>
                        @endpermission
                        @permission('infraestructura-contactos-eliminar')
                        <form action="{{route('infraestructuras.contactos.destroy', [$infraestructura->id, $contacto->id])}}" method="post" style="display: inline-block;">
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