{{csrf_field()}}
<input type="hidden" name="proyecto_id" value="{{(isset($proyecto->ProyectoID))? $proyecto->ProyectoID: ''}}">
<div class="row">
  <div class="form-group col-md-4">
    <label>*Nombre</label>
    <input type="text" class="form-control" name="nombre" id="nombre" value="" required>
  </div>
  <div class="form-group col-md-4">
    <label>*Fecha Inicio</label>
    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="" required>
  </div>
  <div class="form-group col-md-4">
    <label>*Fecha Fin</label>
    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="" required>
  </div> 
  <div class="form-group col-md-12">
    <label>Descripcion</label>
    <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
  </div>
             
  <div class="form-group col-md-4">
    <label>*Total Accesos</label>
    <input type="number" class="form-control" name="total_accesos" id="total_accesos" value="" required>
  </div>
  <div class="form-group col-md-4">
    <label>Fecha Aprob. Interventoria</label>
    <input type="date" class="form-control" name="fecha_aprobacion_interventoria" id="fecha_aprobacion_interventoria" value="">
  </div>
  <div class="form-group col-md-4">
    <label>Fecha Aprob. Supervisión</label>
    <input type="date" class="form-control" name="fecha_aprobacion_supervision" id="fecha_aprobacion_supervision" value="">
  </div>
  <div class="form-group col-md-4">
    <label>*Estado</label>
    <select class="form-control" name="estado" id="estado" required>
      <option value="">Elija un estado</option>
      @foreach($estados_metas as $estado)
        <option value="{{$estado}}">{{$estado}}</option>
      @endforeach
    </select>
  </div>
</div>