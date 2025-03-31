@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-home"></i>  Facturación</h1>
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
            	<div class="box box-info">
					<div class="box-header bg-blue">
						<h2 class="box-title"> Facturas por Periodo</h2>
						
                        <div class="box-tools pull-right">

                            @permission('facturacion-crear')
                            <a class="btn btn-default float-bottom btn-sm" href="{{route('facturacion.create')}}">
                                <i class="fa fa-plus"></i>  Agregar          
                            </a>
                            @endpermission

                            @permission('facturacion-efecty')
                            <button id="btn-efecty" type="button" class="btn btn-warning btn-sm" title="Efecty">  <i id="icon-efecty" class="fa fa-dollar" style="padding: 2px; color: #000;"></i> Efecty</button>
                            @endpermission
                           
                        </div>

			            
					</div>
					
					<div class="box-body table-responsive">
						<table  id="areas" class="table table-bordered table-striped dataTable">
							<tbody>
								<tr>
									<th>#</th>
									<th>Periodo</th>
									<th>Cantidad</th>
									<th>Valor Total</th>								
								</tr>
								<?php $contar = 0; ?>
								@foreach($facturacion as $dato)
									<tr>
										<td>{{$contar+=1}}</td>
										<td><a href="{{route('facturacion.view', $dato->periodo)}}">{{$dato->periodo}}</a></td>
										<td>{{$dato->cantidad}}</td>
										<td>${{number_format($dato->valor, 0, ',', '.')}}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					<div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$facturacion->currentPage()}} de {{$facturacion->lastPage()}}. Total registros {{$facturacion->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $facturacion->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div> 
    @section('mis_scripts')
        @permission('facturacion-efecty')
        <script type="text/javascript">
            $('#btn-efecty').on('click', function(){
                var parametros = {                           
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $(this).attr('disabled',true);
                $('#icon-efecty').removeClass('fa-dollar');
                $('#icon-efecty').addClass('fa-refresh fa-spin');

                $.ajax({
                    type: "POST",
                    url: '/exportar/efecty',
                    data: parametros,
                    xhrFields: {
                        responseType: 'blob' // to avoid binary data being mangled on charset conversion
                    },
                    success: function(blob, status, xhr) {
                        // check for a filename
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }

                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);

                            if (filename) {
                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");
                                // safari doesn't support this yet
                                if (typeof a.download === 'undefined') {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }

                            setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                        }

                        $('#btn-efecty').attr('disabled',false);
                        $('#icon-efecty').removeClass('fa-refresh fa-spin');
                        $('#icon-efecty').addClass('fa-dollar');
                    },
                    error: function(blob, status, xhr){
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.error(xhr);

                        $('#btn-efecty').attr('disabled',false);
                        $('#icon-efecty').removeClass('fa-refresh fa-spin');
                        $('#icon-efecty').addClass('fa-dollar');
                    }

                });
                
            });
    	</script>
        @endpermission
    @endsection   
@endsection