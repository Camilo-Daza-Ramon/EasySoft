<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-question-circle"></i>  Preguntas
      <div class="pull-right">
        @permission('proyectos-preguntas-crear')
          <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addPregunta">
            <i class="fa fa-plus"></i>  Agregar
        </div>
        @endpermission
      </div>
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Pregunta</th>
          <th>Tipo</th>
          <th>Obligatoriedad</th>
          <th>Respuestas</th>
          <th>Estado</th>          
          <th style="width:70px;">Accion</th>
        </tr>        
      </thead>
      <tbody>

        @if($proyecto->preguntas()->count() > 0)
            <?php $i = 0; ?>
            @foreach($proyecto->preguntas as $pregunta)
            <tr>
                <td>{{$i+=1}}</td>
                <td>{{$pregunta->pregunta}}</td>
                <td>{{$pregunta->tipo}}</td>
                <td>{{($pregunta->obligatoriedad)? 'SI' : ''}}</td>
                <td>
                  <?php $respuestas = json_decode($pregunta->opciones_respuesta, true);?>
                  @if(!empty($respuestas))
                    <ul style="list-style: circle;">
                    @foreach($respuestas as $respuesta)
                      <li>
                        {{$respuesta}}
                      </li>
                    @endforeach
                    </ul>
                  @endif
                </td>
                <td>{{$pregunta->estado}}</td>
                <td>
                    @permission('proyectos-preguntas-editar')
                        <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editPregunta"  data-id="{{$pregunta->id}}" data-proyecto-id="{{$pregunta->proyecto_id}}"><i class="fa fa-edit"></i></button>
                    @endpermission

                    @permission('proyectos-preguntas-eliminar')
                        <form action="{{route('proyectos.preguntas.destroy', [$proyecto->ProyectoID, $pregunta->id])}}" method="post" style="display: inline-block;">
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
                <td colspan="7"> <p class="text-big text-gray text-center">NO HAY REGISTROS</p></td>
            </tr>
        @endif                 
      </tbody>
    </table>
  </div>
</div>