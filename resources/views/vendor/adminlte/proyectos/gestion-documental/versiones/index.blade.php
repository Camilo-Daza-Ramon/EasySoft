<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Titulo</th>
            <th>Versión</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if(count($versiones) > 0)

        <?php $i=1; ?>
        @foreach($versiones as $version)
        <tr>
            <td>{{$i++}}</td>
            <td>
                @permission('documental-versiones-ver')
                    <a href="#" data-toggle="modal" data-target="#versionShow" data-id="{{$version->id}}" data-documental="{{$documental_proyecto->id}}" data-titulo="{{$version->titulo}} {{$version->version}}">{{$version->titulo}}</a>
                @else
                    {{$version->titulo}}
                @endpermission
            </td>
            <td>{{$version->version}}</td>
            <td>{{$version->estado}}</td>
            <td>
                @permission('documental-versiones-archivos-crear')
                <button type="button" class="btn btn-purple btn-xs" data-toggle="modal" data-target="#archivoAdd"
                    data-id="{{$version->id}}" data-documental="{{$documental_proyecto->id}}" title="Subir Archivos"><i class="fa fa-upload"></i></button>
                @endpermission

                @permission('documental-versiones-editar')
                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#versionEdit"
                    data-id="{{$version->id}}" data-documental="{{$documental_proyecto->id}}" title="Editar"><i class="fa fa-edit"></i></button>
                @endpermission

                @permission('documental-versiones-eliminar')
                <form style="display: inline-block;" action="{{route('documental-proyectos.versiones.destroy', [$documental_proyecto->id, $version->id])}}"
                    method="post">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <button type="submit" class="btn btn-danger btn-xs"
                        onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                @endpermission
            </td>
        </tr>
        @endforeach

        @else
        <tr>
            <td colspan="5" class="text-center">No hay Registros.</td>
        </tr>

        @endif

    </tbody>
</table>