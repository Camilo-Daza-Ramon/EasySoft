<div class="panel panel-default">
    <div class="panel-heading bg-blue">
        <h2 class="panel-title"><i class="fa fa-list"></i> Pruebas Adelantadas

        @if($mantenimiento->estado != 'CERRADO')
            <div class="btn-group pull-right">
                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle"
                    data-toggle="dropdown">
                    <span id="icon-opciones" class="fa fa-gears"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @permission('mantenimientos-pruebas-crear')
                    <li>
                        <a href="#" data-toggle="modal" data-target="#addPrueba">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </li>
                    @endpermission
                </ul>
            </div>
        @endif
        </h2>
    </div>
    <div class="panel-body table-responsive">
        <div class="row">
            <div class="col-md-12">
                @if($mantenimiento->pruebas->count() > 0)
                    <table class="table table-hover tabla-lista">
                        @foreach($mantenimiento->pruebas as $prueba)
                        <tr>
                            <td class="text-success text-big text-center" style="width:20px;"><i class="fa fa-check-square-o"></i></td>
                            <th>
                                {{$prueba->tipo->DescipcionFallo}}
                                @if($mantenimiento->estado != 'CERRADO')
                                <div class="tools">
                                    @permission('mantenimientos-pruebas-eliminar')
                                        <form action="{{ route('mantenimientos.pruebas.destroy', [$mantenimiento_id, $prueba->id]) }}" method="post">
                                            <input type="hidden" name="_method" value="delete">
                                            <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">

                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"> <i class="fa fa-trash-o"></i></button>
                                        </form>
                                    @endpermission
                                </div>
                                @endif
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