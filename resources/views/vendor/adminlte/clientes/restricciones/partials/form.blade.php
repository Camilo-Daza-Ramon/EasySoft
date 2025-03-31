<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row"> 
    <div class="form-grup col-md-12 {{ $errors->has('observaciones') ? 'has-error' : ''}}">
        <label>Observaciòn</label>
        <textarea class="form-control" name="observaciones" placeholder="Observacion" id="" cols="30" rows="10"></textarea>
        {!! $errors->first('observaciones', '<p class="help-block">:message</p>') !!}
    </div>
</div>
