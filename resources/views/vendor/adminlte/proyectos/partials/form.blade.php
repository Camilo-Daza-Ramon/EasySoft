{{csrf_field()}}
<div class="row">
  <div class="form-group col-md-6">
    <label>*Nombre corto del Proyecto</label>
    <input type="text" class="form-control" name="proyecto" value="{{ (Session::has('errors')) ? old('proyecto', '') : $proyecto->NumeroDeProyecto}}" required>
  </div>
  <div class="form-group col-md-3">
    <label>Numero Contrato</label>
    <input type="text" class="form-control" name="contrato" value="{{ (Session::has('errors')) ? old('contrato', '') : $proyecto->NumeroDeContrato}}">
  </div>
  <div class="form-group col-md-12">
    <label>*Descripcion</label>
    <textarea class="form-control" name="descripcion" required>{{ (Session::has('errors')) ? old('descripcion', '') : $proyecto->DescripcionProyecto}}</textarea>
  </div>
             
  <div class="form-group col-md-3">
    <label>*Vigencia en Meses</label>
    <input type="number" class="form-control" name="vigencia" value="{{ (Session::has('errors')) ? old('vigencia', '') : $proyecto->vigencia}}" required>
  </div>
  

  <div class="form-group col-md-3">
    <label>Fecha de Finalización del Proyecto</label>
    <input type="date" class="form-control" name="fecha_fin_proyecto" value="{{ (Session::has('errors')) ? old('fecha_fin_proyecto', '') : $proyecto->fecha_fin_proyecto}}">
  </div>


  <div class="form-group col-md-3">
    <label>*Tipo Facturación</label>
    <select class="form-control" name="tipo_facturacion" required>
      <option value="">Elija un tipo</option>
      @foreach($tipos_facturacion as $tipo)
        @if($tipo == $proyecto->tipo_facturacion)
          <option value="{{$tipo}}" selected>{{$tipo}}</option>
        @else
          <option value="{{$tipo}}">{{$tipo}}</option>
        @endif                  
      @endforeach
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>*Dia Corte Facturacion</label>
    <input type="number" class="form-control" name="dia_corte_facturacion" value="{{ (Session::has('errors')) ? old('dia_corte_facturacion', '') : $proyecto->dia_corte_facturacion}}" max="30" min="1" required>
  </div>
  <div class="form-group col-md-3">
    <label>*Limite Meses en Mora</label>
    <input type="number" class="form-control" name="limite_meses_mora" min="0" max="12"  value="{{ (Session::has('errors')) ? old('limite_meses_mora', '') : $proyecto->limite_meses_mora}}" required>
  </div>
  <div class="form-group col-md-3">
    <label>*% Interes Mora</label>
    <input type="number" class="form-control" name="porcentaje_interes_mora" min="0" max="99" value="{{ (Session::has('errors')) ? old('porcentaje_interes_mora', '') : $proyecto->porcentaje_interes_mora}}" required>
  </div>
  <div class="form-group col-md-3">
    <label>*Estado</label>
    <select class="form-control" name="estado" required>
      <option value="">Elija un estado</option>
      @foreach($estados as $estado)
        <option value="{{$estado['sigla']}}" {!!($estado['sigla'] == $proyecto->Status)? 'selected' : '' !!}>{{$estado['descripcion']}}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group{{ $errors->has('clausula_permanencia') ? ' has-error' : '' }} col-sm-6">              
    <div class="checkbox">
      <label>
        <input type="checkbox" name="clausula_permanencia" {!!(old('clausula_permanencia') || ($proyecto->clausula_permanencia))? 'checked': ''!!}>
        Marque si el contrato tendrá clausula de permanencia.
      </label>
    </div>
  </div>

  <div class="form-group{{ $errors->has('acta_juramentada') ? ' has-error' : '' }} col-sm-6">              
    <div class="checkbox">
      <label>
        <input type="checkbox" name="acta_juramentada" {!!(old('acta_juramentada') || ($proyecto->acta_juramentada))? 'checked': ''!!}>
        El proyecto exige declaración juramentada de nuevo usuario?
      </label>
    </div>
  </div>

  <div class="form-group{{ $errors->has('condiciones_plan') ? ' has-error' : '' }} col-md-6">              
    <label>Condiciones del Plan</label>
    <textarea class="form-control text-justify" name="condiciones_plan" rows="10">{{ (Session::has('errors')) ? old('condiciones_plan', '') : $proyecto->condiciones_plan}}</textarea>
  </div>

  <div class="form-group{{ $errors->has('condiciones_servicio') ? ' has-error' : '' }} col-md-6">              
    <label>Condiciones del Servicio</label>
    <textarea class="form-control text-justify" name="condiciones_servicio" rows="10">{{ (Session::has('errors')) ? old('condiciones_servicio', '') : $proyecto->condiciones_servicio}}</textarea>
  </div>
</div>