<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Periodo</th>            
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if(count($informes_mensuales) > 0)

        <?php $i=1; ?>
        @foreach($informes_mensuales as $mes)
        <tr>
            <td>{{$i++}}</td>
            <td>
                <a href="?periodo={{$mes->id}}">{{strtoupper(strftime('%B %Y', strtotime($mes->periodo)))}}</a>
            </td>            
            <td>

                @permission('documental-mensuales-editar')
                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#mensualEdit"
                    data-id="{{$mes->id}}" data-documental="{{$documental_proyecto->id}}" title="Editar"><i class="fa fa-edit"></i></button>
                @endpermission

                @permission('documental-mensuales-eliminar')
                <form style="display: inline-block;" action="{{route('documental-proyectos.mensuales.destroy', [$documental_proyecto->id, $mes->id])}}"
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
            <td colspan="3" class="text-center">No hay Registros.</td>
        </tr>

        @endif

    </tbody>
</table>