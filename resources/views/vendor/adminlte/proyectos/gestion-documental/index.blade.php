<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border bg-blue">
                <h2 class="box-title">Gestion Documental</h2>

                <div class="btn-group pull-right">
                @permission('documental-proyectos-crear')
                <a type="button" class="btn btn-default btn-xs" href="#" data-toggle="modal" data-target="#documentalAdd">
                <i class="fa fa-plus"></i> Agregar</a>
                @endpermission
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($documental_lista) > 0)

                        <?php $i=1; ?>
                        @foreach($documental_lista as $documental)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                <a href="{{ !isset($documental->tipo) ? route('documental-carpetas.show', $documental->id) : route('documental-proyectos.show', $documental->id)}}">{{$documental->nombre}}</a>
                            </td>
                            <td>{{isset($documental->tipo) ? $documental->tipo : 'CARPETA' }}</td>
                            <td>

                            @permission('documental-proyectos-editar')
                                <button type="button"  class="btn btn-primary btn-xs" data-toggle="modal" data-target="#documentalEdit" data-id="{{$documental->id}}" data-tipo="{{isset($documental->tipo) ? $documental->tipo : 'CARPETA'}}" title="Editar"><i class="fa fa-edit"></i></button>
                            @endpermission

                            @permission('documental-proyectos-eliminar')
                                <form style="display: inline-block;" action="{{ !isset($documental->tipo) ? route('documental-carpetas.destroy', $documental->id) 
                                    : route('documental-proyectos.destroy', $documental->id)}}" method="post">
                                    <input type="hidden" name="_method" value="delete">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            @endpermission
                            </td>
                        </tr>
                        @endforeach

                    @else
                        <tr>
                            <td colspan="4" class="text-center">No hay Registros.</td>
                        </tr>

                    @endif
                    
                </tbody>
                </table>
            </div>            
        </div>
    </div>
</div>