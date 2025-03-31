{{csrf_field()}}
<div class="row">
  <div class="form-group col-md-8">
    <label>*Nombre</label>
    <input type="text" class="form-control" name="nombre" id="nombre" value="" required>
  </div>
  <div class="form-group col-md-4">
    <label>*Estado</label>
    <select name="estado" class="form-control" required>
      <option value="">Elija un estado</option>
      <option value="ACTIVO">ACTIVO</option>
      <option value="INACTIVO">INACTIVO</option>
    </select>
  </div>
  <div class="form-group col-md-12">
    <label>Descripcion</label>
    <textarea name="descripcion" class="form-control"></textarea>
  </div>
</div>