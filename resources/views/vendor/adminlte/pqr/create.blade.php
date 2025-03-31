@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-comments-o"></i> pqr - Crear</h1>
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border bg-blue">
                <h3 class="box-title">Detalles</h3>
            </div>
            <!-- /.box-header -->
            <form action="{{route('pqr.store')}}" method="post">
                <div class="box-body">
                    @include('adminlte::pqr.partials.form')
                </div>

                <div class="box-footer">
                    <button type="submit" class="pull-right btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('mis_scripts')
    <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios3.js')}}"></script>
    <script type="text/javascript">

        $('select[name="tipo_solicitud"]').on("change", function(){
            if($(this).val().length > 0){
                listar_clasificaciones($(this).val());
            }
        });        

        const listar_clasificaciones = (tipo_pqr, clasificacion = null) => {

            if(tipo_pqr.length > 0){

                var parameters = {
                    tipo_pqr : tipo_pqr,                
                    '_token' : $('input:hidden[name=_token]').val()
                };

                $.post('/pqrs/clasificaciones/ajax', parameters).done(function(data){

                    $('select[name="clasificacion"]').empty();

                    $('select[name="clasificacion"]').append('<option value="">Elija una opcion</option>');

                    if(data.clasificaciones.length > 0){

                        $.each(data.clasificaciones, function(index, clasificacionObj){
                        
                            if (clasificacion != null) {
                                $('select[name="clasificacion"]').append(`<option value="${clasificacionObj.id}" ${(clasificacionObj.id == clasificacion)?'selected' : ''}>${clasificacionObj.descripcion}</option>`);
                            }else{
                                $('select[name="clasificacion"]').append(`<option value="${clasificacionObj.id}" >${clasificacionObj.descripcion}</option>`);
                            }                                     
                        });

                    }

                    

                }).fail(function(e){
                    alert('error');
                });

            }           

        }

	</script>

@endsection
@endsection