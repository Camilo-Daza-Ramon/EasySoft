<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar ONT</h4>
      </div>      
      <div class="modal-body">
        
          <div class="form-group" id="form-buscar-ont">
            <label>*ONT</label>
            <div class="input-group input-group hidden-xs" style="width: 60%;">
              <input type="text" id="serial" name="table_search" class="form-control pull-right" autocomplete="off" placeholder="SN">

              <div class="input-group-btn">
                <button type="button" id="ont-buscar" class="btn btn-primary"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
          
            <table id="table-ont" class="table table-hover" style="display: none;">
              <thead>
                <th>#</th>
                <th>Nombre</th>
                <th>Modelo</th>
                <th>Serial</th>              
                <th>Estado</th>
              </thead>
              <tbody id="ont-resultado">
                
              </tbody>
            </table>
        <form id="form-ont" action="{{route('clientes.aprovisionar.store')}}" method="POST" style="display: none;">
          {{csrf_field()}}          
          <input type="hidden" name="cliente_id" value="{{$contrato->ClienteId}}">
          <input type="hidden" id="ont_id" name="ont_id" value="">
          <input type="hidden" name="servicio_id" value="{{$contrato->servicio[0]->id}}">
          <div class="row">
            <div class="form-group{{ $errors->has('olt_id') ? ' has-error' : '' }} col-md-5">
              <label>*OLT: </label>
              <select class="form-control" name="olt_id" required>
                <option value="">Elija una opción</option>
                @foreach($olts as $dato)
                  <option value="{{$dato->id}}">{{$dato->nombre}} ({{$dato->ip}})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-3">
              <label>*Estado del servicio</label>
              <select class="form-control" name="estado" required>
                <option value="">Elija un Estado</option>
                <option value="Sin acciones">Sin acciones</option>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
                <option value="Retiro">Retiro</option>
                <option value="Suspendido">Suspendido</option>
              </select>
            </div>
            <div class="form-group{{ $errors->has('fecha_instalacion') ? ' has-error' : '' }} col-md-4 col-xs-12 col-sm-12">
              <label>*Fecha Instalación</label>
              <input type="date" name="fecha_instalacion" id="fecha_instalacion" class="form-control" value="{{$contrato->fecha_instalacion}}" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary" onclick="return validar();">Agregar</button>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->