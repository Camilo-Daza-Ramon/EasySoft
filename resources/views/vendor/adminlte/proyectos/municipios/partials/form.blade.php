{{csrf_field()}}
<input type="hidden" name="proyecto_id" value="{{(isset($proyecto->ProyectoID))? $proyecto->ProyectoID: ''}}">
<div class="row">
  <div class="form-group col-md-6">
    <label>*Departamento</label>
    <select class="form-control" name="departamento" id="departamento" required>
      <option value="">Elija un departamento</option>
      @foreach($departamentos as $departamento)
          <option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
      @endforeach
    </select>
  </div>

  <div class="form-group col-md-6">
    <label>*Municipio</label>
    <select class="form-control" name="municipio" id="municipio" required>
      <option value="">Elija un municipio</option>
    </select>
  </div>

  <div class="form-group col-md-6">
    <label>Meta</label>
    <select class="form-control" name="meta" id="meta">
      <option value="">Elija una Meta</option>
      @foreach($metas as $meta)
          <option value="{{$meta->id}}">{{$meta->nombre}}</option>
      @endforeach
    </select>
  </div>
             
  <div class="form-group col-md-6">
    <label>Total Accesos</label>
    <input type="number" class="form-control" name="total_accesos" id="total_accesos" value="">
  </div>
</div>