<div class="panel panel-default">
    <div class="panel-heading bg-blue">
        <h2 class="panel-title"><i class="fa fa-hdd-o"></i> Equipos

            <div class="btn-group pull-right">
                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle"
                    data-toggle="dropdown">
                    <span id="icon-opciones" class="fa fa-gears"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @permission('infraestructura-equipos-crear')
                    <li>
                        <a href="#" data-infra_id="{{$infraestructura->id}}" id="btn-add-equipos" data-toggle="modal" data-target="#addEquipos">
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
                    <th>Código</th>
                    <th>Serial</th>
                    <th>IP Gestión</th>
                    <th>Usuario</th>
                    <th>Password</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($infraestructura->equipos->count() > 0)
                <?php $i = 0; ?>
                @foreach($infraestructura->equipos as $eq)
                <tr>
                    <td>{{$i+=1}}</td>
                    <td><a href="{{url('inventarios/insumos').'/'.$eq->activo_fijo->insumo->InsumoId}}">{{$eq->activo_fijo->insumo->Codigo}}</a></td>
                    <td><a href="{{url('inventarios/insumos').'/'.$eq->activo_fijo->insumo->InsumoId.'?serial='.$eq->activo_fijo->Serial}}">{{$eq->activo_fijo->Serial}}</a></td>
                    <td>{{$eq->ip_gestion}}</td>
                    <td>{{$eq->usuario}}</td>
                    <td id="campo_password_{{$eq->id}}">
                        @if (isset($eq->password))
                        <input style="width: 100px;" disabled value="{{ Illuminate\Support\Facades\Crypt::decrypt($eq->password) }}" type="password">
                        <button onclick="copyName('{{$eq->id}}')" style="padding: 2px 10px;" class="btn btn-primary">
                            <i class="fa fa-copy"></i>
                        </button>
                        @endif
                    </td>
                    <td>
                        @permission('infraestructura-equipos-editar')
                        <button class="btn btn-xs btn-primary" onclick="editarEquipo('{{$infraestructura->id}}', '{{$eq->id}}')" data-toggle="modal" data-target="#addEquipos">
                            <i class="fa fa-edit"></i>
                        </button>
                        @endpermission
                        @permission('infraestructura-equipos-eliminar')
                        <form action="{{route('infraestructuras.equipos.destroy', [$infraestructura->id, $eq->id])}}" method="post" style="display: inline-block;">
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