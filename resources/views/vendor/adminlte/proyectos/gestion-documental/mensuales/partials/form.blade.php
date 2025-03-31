{{csrf_field()}}

<div class="form-group{{ $errors->has('titulo') ? ' has-error' : '' }} col-md-9">
  <label>*Periodo</label>
  <input type="month" name="periodo" class="form-control" required>
</div>