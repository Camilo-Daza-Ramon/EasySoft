{{csrf_field()}}
<input type="hidden" name="proyecto_id" value="{{(isset($proyecto->ProyectoID))? $proyecto->ProyectoID: ''}}">
<div class="row">
  <div class="form-group col-md-4">
    <label>*Concepto</label>    
    <select class="form-control" name="concepto" id="concepto" required>
      <option value=""> Elija una opcion</option>
      @foreach($conceptos_costos as $costo)
        <option value="{{$costo}}">{{$costo}}</option>
      @endforeach
    </select>
  </div>  
  <div class="form-group col-md-4">
    <label>*IVA</label>
    <select class="form-control" name="iva" id="iva" required>
      <option value="">Elija una opción</option>
      <option value="SI">SI</option>
      <option value="NO">NO</option>
    </select>
  </div> 
  <div class="form-group col-md-4">
    <label>*Valor</label>
    <input class="form-control" type="number" name="valor" id="valor" required>
  </div>
  <div class="form-group col-md-12">
    <label>*Descripcion</label>
    <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
  </div>
</div>