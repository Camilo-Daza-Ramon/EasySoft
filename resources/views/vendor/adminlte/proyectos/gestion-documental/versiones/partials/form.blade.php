{{csrf_field()}}

<input type="hidden" name="periodo" class="form-control" value="{{(!empty($_GET['periodo']))? $_GET['periodo']: '' }}">
<div class="form-group{{ $errors->has('titulo') ? ' has-error' : '' }} col-md-12">
  <label>*Titulo</label>
  <input type="text" name="titulo" class="form-control" required>
</div>

<div class="form-group{{ $errors->has('version') ? ' has-error' : '' }} col-md-6">
  <label>*Version</label>
  <input type="number" name="version" class="form-control" min="0" step="0.1" required>
</div>



<div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-6">
  <label>*Estado</label>
  <select class="form-control" name="estado" required>
    <option value="">Elija una opción</option>
    @foreach($estados as $estado)
    <option value="{{$estado}}">{{$estado}}</option>
    @endforeach
  </select>
</div>


