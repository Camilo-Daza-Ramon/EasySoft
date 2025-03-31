<div class="panel panel-default">
    <div class="panel-heading bg-blue">
        <h2 class="panel-title"><i class="fa fa-desktop"></i> Nodos Dependientes
        </h2>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-12">
                @if($infraestructura->hijos->count() > 0)
                <table class="table table-hover tabla-lista">
                    @foreach($infraestructura->hijos as $dep)
                    <tr>
                        <td class="text-success text-big text-center" style="width:20px;"><i class="fa fa-check-square-o"></i></td>
                        <th>
                            <a href="{{route('infraestructuras.show', $dep->id)}}">{{$dep->nombre}}</a>
                            <div class="tools">
                                @permission('infraestructura-dependientes-eliminar')
                                <form action="{{ route('infraestructuras.dependientes.destroy', [$infraestructura->id, $dep->id]) }}" method="post">
                                    <input type="hidden" name="_method" value="delete">

                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"> <i class="fa fa-trash-o"></i></button>
                                </form>
                                @endpermission
                            </div>
                        </th>
                    </tr>
                    @endforeach
                </table>

                @else
                <p class="text-center text-muted text-big">NO HAY REGISTROS</p>
                @endif
            </div>

        </div>
    </div>
</div>