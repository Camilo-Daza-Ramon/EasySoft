{{csrf_field()}}
<div class="row">
  <div class="form-group col-md-6">
    <label>*Alias</label>
    <input type="text" class="form-control" name="alias" id="alias" value="" required>
  </div>

  <div class="form-group col-md-6">
    <label>*Nombre</label>
    <input type="text" class="form-control" name="nombre" id="nombre" value="" readonly required>
  </div>
  
  <div class="form-group col-md-4">
    <label>*Tipo</label>
    <select name="tipo" class="form-control" required>
      <option value="">Elija un tipo</option>
      <option value="OBLIGATORIO">OBLIGATORIO</option>
      <option value="OPCIONAL">OPCIONAL</option>
    </select>
  </div>
  <div class="form-group col-md-4">
    <label>*Estado</label>
    <select name="estado" class="form-control" required>
      <option value="">Elija un estado</option>
      <option value="ACTIVO">ACTIVO</option>
      <option value="INACTIVO">INACTIVO</option>
    </select>
  </div>

  <div class="form-group col-md-4">
    <div class="checkbox mt-3">
      <label>
        <input type="checkbox" name="coordenadas" value="1">
         Coordenadas
      </label>
    </div>
  </div>

  <div class="form-group col-md-12">
    <label>Descripcion</label>
    <textarea name="descripcion" class="form-control"></textarea>
  </div>
</div>