
<div class="row  bg-blue">
    <div class="col-md-12 text-center">
        <h5>EVIDENCIAS</h5>
    </div>    
</div>

<div class="row">
    <br>
    <div class="form-group{{ $errors->has('speedtest') ? ' has-error' : '' }} col-md-6">
        <label>SpeedTest</label>
        <input type="file" class="form-control" name="speedtest" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('ping') ? ' has-error' : '' }} col-md-6">
        <label>PING</label>
        <input type="file" class="form-control" name="ping" value="" accept="image/png, image/gif, image/jpeg,  image/jpg" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('navegacion') ? ' has-error' : '' }} col-md-6">
        <label>Navegacion web (Google)</label>
        <input type="file" class="form-control" name="navegacion" value="" accept="image/png, image/gif, image/jpeg,  image/jpg" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('youtube') ? ' has-error' : '' }} col-md-6">
        <label>Video Streaming (Youtube)</label>
        <input type="file" class="form-control" name="youtube" value="" accept="image/png, image/gif, image/jpeg,  image/jpg" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('mintic') ? ' has-error' : '' }} col-md-6">
        <label>Pagina MINTIC</label>
        <input type="file" class="form-control" name="mintic" value="" accept="image/png, image/gif, image/jpeg,  image/jpg" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('instalacion') ? ' has-error' : '' }} col-md-6">
        <label>Foto de los equipos instalados</label>
        <input type="file" class="form-control" name="instalacion" value="" accept="image/png, image/gif, image/jpeg,  image/jpg" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('pregunta_firma') ? ' has-error' : '' }} col-md-6">
        <label>*Firma</label>
        <select name="pregunta_firma" class="form-control" required>
            <option value="">Elija una opción</option>
            <option>FIRMAR</option>
            <option>SUBIR FIRMA</option>
        </select>        
        <span class="help-block"></span>
    </div>

    <div id="firmaSubir" class="form-group{{ $errors->has('firma') ? ' has-error' : '' }} col-md-6" style="display:none;">
        <label>*Firma</label>
        <input type="file" class="form-control" name="firma" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
        <span class="help-block"></span>
    </div>
</div>