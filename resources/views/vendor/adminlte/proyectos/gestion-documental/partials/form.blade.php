{{csrf_field()}}
<input type="hidden" name="carpeta_id" value="{{ isset($carpeta->id) ? $carpeta->id : null}}">

<input type="hidden" name="proyecto_id" value="{{ $proyecto !== null ? $proyecto->ProyectoID : null }}">

<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-8">
  <label>*Nombre</label>
  <input type="text" name="nombre" class="form-control" required>
</div>

<div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }} col-md-4">
  <label>*Tipo</label>
  <select class="form-control" name="tipo" required>
    <option value="">Elija una opción</option>
    @foreach($tipos as $tipo)
    <option value="{{$tipo}}">{{$tipo}}</option>
    @endforeach
  </select>
</div>


