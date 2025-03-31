<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-thumb-tack"></i> Información Meta</h2> 
    </div>
    <div class="panel-body table-responsive">
      <div class="row">
        <div class="col-md-4">
          <label>Meta Asignado:</label>
          <p>{{$cliente->meta_cliente->meta->nombre}}</p>
        </div>
        <div class="col-md-4">
          <label>ID-PUNTO:</label>
          <p>{{$cliente->meta_cliente->idpunto}}</p>
        </div>
      </div>
    </div>
  </div>
</div>