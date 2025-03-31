<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group col-md-4">
    <label for="name">Usuario</label>
    <input type="name" class="form-control" id="name" placeholder="Name" name="name" value="{{ (Session::has('errors')) ? old('name', '') : $user->name }}">
</div>
<div class="form-group col-md-4">
    <label for="cedula">Cedula</label>
    <input type="cedula" class="form-control" id="cedula" placeholder="Cedula" name="cedula" value="{{ (Session::has('errors')) ? old('cedula', '') : $user->cedula }}">
</div>
<div class="form-group col-md-4">
    <label for="celular">Celular</label>
    <input type="celular" class="form-control" id="celular" placeholder="Celular" name="celular" value="{{ (Session::has('errors')) ? old('celular', '') : $user->celular}}">
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}} col-md-4">
    <label for="email">Correo</label>
    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ (Session::has('errors')) ? old('email', '') : $user->email }}">
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group col-md-4">
    <label for="password">Contraseña</label>
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
</div>
@if(Config::get('entrust-gui.confirmable') === true)
<div class="form-group col-md-4">
    <label for="password">Confirmar Contraseña</label>
    <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation">
</div>
@endif
<div class="form-group col-md-4">
    <label for="roles">Roles</label>
    <select name="roles[]" id="roles" multiple class="form-control">
        @foreach($roles as $index => $role)
            <option value="{{ $index }}" {{ ((in_array($index, old('roles', []))) || ( ! Session::has('errors') && $user->roles->contains('id', $index))) ? 'selected' : '' }}>{{ $role }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-4">
    <label for="firma" class="control-label">Firma</label>
    <input id="firma" type="file" class="form-control" name="firma" value="{{ old('firma') }}">

    @if ($errors->has('firma'))
        <span class="help-block">
            <strong>{{ $errors->first('firma') }}</strong>
        </span>
    @endif
</div>

<div class="form-group col-md-4">
    <label for="name">Estado</label>
    <select class="form-control" id="estado" name="estado" required="">
        @if($user->estado == 'ACTIVO')
            <option value="ACTIVO" selected>ACTIVO</option>
            <option value="INACTIVO">INACTIVO</option>
        @else
            <option value="ACTIVO">ACTIVO</option>
            <option value="INACTIVO" selected>INACTIVO</option>
        @endif
    </select>
</div>
