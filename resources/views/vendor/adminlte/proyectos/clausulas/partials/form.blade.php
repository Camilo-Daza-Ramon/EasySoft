{{csrf_field()}}
<input type="hidden" name="proyecto_id" value="{{(isset($proyecto->ProyectoID))? $proyecto->ProyectoID: ''}}">
<div class="row">
  <div class="form-group col-md-6">
    <label>*Mes</label>    
    <select class="form-control" name="numero_mes" id="numero_mes" required>
      <option value=""> Elija una opcion</option>
      @for($i = 1; $i<=$proyecto->vigencia; $i++)
        <option value="{{$i}}" {{(in_array($i,array_column($clausulas, "numero_mes")))? 'disabled' : ''}}>MES {{$i}}</option>
      @endfor
    </select>
  </div>
  <div class="form-group col-md-6">
    <label>*Valor</label>
    <input class="form-control" type="number" name="valor" id="valor" required>
  </div>
</div>