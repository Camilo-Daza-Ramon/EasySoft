{{csrf_field()}}
<div class="row">
  <div class="form-group col-md-12">
    <label>*Pregunta:</label>
    <input type="text" class="form-control" name="pregunta" id="pregunta" value="{{ (Session::has('errors')) ? old('pregunta', '') : '' }}" required>
  </div>

  <div class="form-group col-md-4">
    <label>*Tipo:</label>
    <select name="tipo" class="form-control" required>
      <option value="">Elija un tipo</option>
      @foreach($tipos_preguntas as $tipo_pregunta)
        <option value="{{$tipo_pregunta['tipo']}}">{{$tipo_pregunta['descripcion']}}</option>
      @endforeach
    </select>
  </div> 

  <div class="form-group col-md-4">
    <label>*Estado:</label>
    <select name="estado" class="form-control" required>
      <option value="">Elija un estado</option>
      <option value="ACTIVA">ACTIVA</option>
      <option value="INACTIVA">INACTIVA</option>
    </select>
  </div>

  <div class="form-group col-md-4">
    <div class="checkbox mt-3">
      <label>
        <input type="checkbox" name="obligatorio" value="1">
         Obligatorio
      </label>
    </div>
  </div>  

  <div class="form-group col-md-12">
    <label>Respuestas:</label>
    <select class="form-control js-example-basic-multiple-respuestas" name="respuestas[]" multiple="multiple"></select>
  </div>
</div>