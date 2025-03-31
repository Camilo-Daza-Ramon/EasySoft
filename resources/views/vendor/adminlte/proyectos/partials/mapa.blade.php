<div class="modal fade" id="showMapa">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-map-o"></i> Mapa</h4>
        {{csrf_field()}}
      </div>
        <div class="modal-body no-padding">
          <div class="row">
            <div class="col">
              <div class="form-row rounded box-shadow">
                <div class="form-group col-md-12">
                    <div id="mapCanvas" style="width: 100%; height: 550px;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->