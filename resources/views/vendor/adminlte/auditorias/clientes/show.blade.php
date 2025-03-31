@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1> <i class="fa fa-user-secret"> </i> Auditar Cliente -
  {{mb_convert_case($cliente->NombreBeneficiario . ' ' . $cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}
</h1>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">

  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header bg-blue">
          <h3 class="box-title">Datos de la Venta</h3>
        </div>

        <div class="box-body">
          <form action="{{route('auditorias.clientes.store')}}" id="form-auditoria" method="post">
            <input type="hidden" name="tipo" value="cliente">
            <input type="hidden" name="cliente" value="{{$cliente->ClienteId}}">            
            {{ csrf_field() }}
            
            <div class="box-group" id="accordion">
              <div class="panel box box-orange">
                <div class="box-header bg-orange">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#panel-datos-personales">
                      <i class="fa fa fa-user"></i> Cedula y Datos Personales
                    </a>
                  </h4>
                </div>
                <div id="panel-datos-personales" class="panel-collapse collapse in">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label>*Tipo de documento: </label>
                            <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                              <option value="">Elija una opcion</option>
                              @foreach($tipos_documentos as $key => $value)
                              <option value="{{$key}}" {{($cliente->TipoDeDocumento == $key)? 'selected' : ''}}>{{$value}}</option>
                              @endforeach
                            </select>
                          </div>
                          <div id="form-group-documento" class="form-group{{ $errors->has('documento') ? ' has-error' : '' }} col-md-6">
                            <label>Documento:</label>
                            <input type="number" class="form-control" name="documento" placeholder="Documento" value="{{$cliente->Identificacion}}">
                          </div>

                          <div class="form-group col-md-6">
                            <label>Apellidos:</label>
                            <input type="text" name="apellidos" class="form-control" placeholder="Apellidos" value="{{$cliente->Apellidos}}">
                          </div>

                          <div class="form-group col-md-6">
                            <label>Nombres:</label>
                            <input type="text" name="nombres" class="form-control" placeholder="Nombres" value="{{$cliente->NombreBeneficiario}}">
                          </div>

                          <div class="form-group{{ $errors->has('fecha_nacimiento') ? ' has-error' : '' }} col-md-6">
                            <label>*Fecha de Nacimiento: </label>
                            <input type="date" class="form-control" name="fecha_nacimiento" placeholder="Fecha de nacimiento" value="{{$cliente->fecha_nacimiento}}" required>
                          </div>
                          <div class="form-group{{ $errors->has('lugar_nacimiento') ? ' has-error' : '' }} col-md-6">
                            <label>*Lugar de Nacimiento: </label>
                            <input type="text" class="form-control" name="lugar_nacimiento" placeholder="Lugar de nacimiento" value="{{$cliente->lugar_nacimiento}}" autocomplete="off" required>
                          </div>

                          <div class="form-group{{ $errors->has('genero') ? ' has-error' : '' }} col-md-6">
                            <label>*Género: </label>
                            <select name="genero" id="genero" class="form-control" required>
                              <option value="">Elija una Opcion</option>
                              @foreach($genero as $dato)
                              @if($cliente->genero == $dato['sigla'])
                              <option value="{{$dato['sigla']}}" selected>{{$dato['valor']}}</option>
                              @else
                              <option value="{{$dato['sigla']}}">{{$dato['valor']}}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>

                          <div class="form-group{{ $errors->has('lugar_expedicion') ? ' has-error' : '' }} col-md-6">
                            <label>*Ciudad de Expedición:</label>
                            <input type="text" name="lugar_expedicion" class="form-control" placeholder="Ciudad de Espedición" value="{{$cliente->ExpedidaEn}}" autocomplete="off" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6" style="max-height: 350px; overflow-y: auto;">
                        @if(isset($evidencias['datos_personales']))
                          {!! $evidencias['datos_personales'] !!}
                        @endif
                      </div>

                      <div class="col-md-12">
                        <button type="button" class="btn btn-block btn-success" onclick="siguiente('panel-datos-personales', 'panel-direccion');">Siguiente</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="panel box box-orange" style="display:none;">
                <div class="box-header bg-orange">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#panel-direccion" class="collapsed" aria-expanded="false">
                      <i class="fa fa-home"></i> Dirección, Recibo y Foto Vivienda
                    </a>
                  </h4>
                </div>
                <div id="panel-direccion" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="row">

                          <div class="form-group col-md-12 col-sm-6">
                            <label>*Direccion Recibo:</label>
                            <input type="text" class="form-control" name="direccion_recibo" placeholder="Direccion Recibo" value="{{$cliente->direccion_recibo}}" autocomplete="off">
                          </div>

                          <div class="form-group col-md-8 col-sm-6">
                            <label>Barrio:</label>
                            <input type="barrio" class="form-control" name="barrio" placeholder="Barrio" value="{{$cliente->Barrio}}" autocomplete="off">
                          </div>

                          <div class="form-group{{ $errors->has('estrato') ? ' has-error' : '' }} col-md-4 col-sm-6">
                            <label>*Estrato:</label>
                            <select name="estrato" class="form-control" id="estrato" required>
                              <option value="">Elija una opción</option>
                              @foreach($estratos as $estrato)
                              <option value="{{$estrato}}" {{($cliente->Estrato == $estrato)? 'selected' : ''}}>{{$estrato}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6" style="max-height: 350px; overflow-y: auto;">
                        @if(isset($evidencias['lugar_residencia']))
                          {!! $evidencias['lugar_residencia'] !!}
                        @endif
                      </div>

                      <div class="col-md-12">
                        <button type="button" class="btn btn-block btn-success" onclick="siguiente('panel-direccion', 'panel-firma');">Siguiente</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="panel box box-orange" style="display:none;">
                <div class="box-header bg-orange">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#panel-firma" class="collapsed" aria-expanded="false">
                      <i class="fa fa-edit"></i> Firma
                    </a>
                  </h4>
                </div>
                <div id="panel-firma" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                  <div class="box-body">
                    <div class="row">

                      <div class="row col-md-12" style="padding:15px;">
                        @if ($cliente->SabeFirmar == 0 && count($contratos) > 0)
                          @include('adminlte::auditorias.clientes.partials.acta-no-firma')
                        @endif

                      </div>

                      @if(isset($evidencias['firma']))
                        {!! $evidencias['firma'] !!}
                      @endif

                      <div class="col-md-12">
                        <button type="button" class="btn btn-block btn-success" onclick="siguiente('panel-firma', 'panel-contrato');">Siguiente</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="panel box box-orange" style="display:none;">
                <div class="box-header bg-orange">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#panel-contrato" class="collapsed" aria-expanded="false">
                      <i class="fa fa-file"></i> Contrato y Condiciones
                    </a>
                  </h4>
                </div>
                <div id="panel-contrato" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                  <div class="box-body">
                    @foreach($contratos as $contrato)
                      <table class="table">
                        <tbody>
                          <tr>
                            <th class="bg-gray">Tipo de Facturación</th>
                            <td>{{$contrato->tipo_cobro}}</td>

                            <th class="bg-gray">Vigencia</th>
                            <td>{{$contrato->vigencia_meses}} MESES</td>

                            <th class="bg-gray">Vendedor</th>
                            <td>{{$contrato->vendedor->name}}</td>
                          </tr>

                          @foreach($contrato->servicio as $servicio)
                            <tr>
                              <th class="bg-gray">Servicio</th>
                              <td>
                                {{$servicio->nombre}} <br>
                                <small>{{$servicio->descripcion}}</small>

                              </td>

                              <th class="bg-gray">Valor</th>
                              <td>${{$servicio->valor}}</td>

                              <th class="bg-gray">Megas Contratadas</th>
                              <td>{{$servicio->cantidad}} Mb</td>
                            </tr>                            
                          @endforeach
                        </tbody>
                      </table>

                      <hr style="width: 90%;">
                    @endforeach

                    <div class="row">
                      <div class="form-group col-md-4">
                        <label for="">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                          <option value="">Estado</option>
                          <option value="APROBADO">APROBADO</option>
                          <option value="RECHAZADO">RECHAZADO</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="">Vendedor</label>
                        <select class="form-control" name="vendedor" id="vendedor" required>
                          <option value="">Elija un Vendedor</option>
                          <?php $add = 0; ?>
                          @foreach($vendedores as $vendedor)
                            @if($cliente->user_id == $vendedor->id)
                              <?php $add = 1; ?>
                              <option value="{{$vendedor->id}}" selected>{{$vendedor->name}}</option>
                            @else
                              <option value="{{$vendedor->id}}">{{$vendedor->name}}</option>
                            @endif
                          @endforeach

                          @if($add == 0 && !empty($cliente->user_id))
                          <option value="{{$cliente->user_id}}" selected>{{$cliente->vendedor->name}}</option>
                          @endif
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="">Motivo de Rechazo</label>
                        <select class="form-control" id="motivo_rechazo" name="motivo_rechazo" id="motivo_rechazo">
                          <option value="">Motivo Rechazo</option>
                          @foreach($motivos_rechazo as $valor)
                            @if($valor == $cliente->MotivoDeRechazo)
                              <option value="{{$valor}}" selected>{{$valor}}</option>
                            @else
                              <option value="{{$valor}}">{{$valor}}</option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group col-md-12">
                        <label for="">Observaciones</label>
                        <textarea placeholder="Observaciones" class="form-control" rows="5" id="observaciones" name="observaciones">
                          {{$cliente->ComentarioRechazo}}
                        </textarea>
                      </div>

                      <div class="form-group col-md-12">
                        <button type="submit" id="auditar" class="btn btn-block btn-primary btn-flat">Auditar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  @section('mis_scripts')
    <script src="https://unpkg.com/@panzoom/panzoom@4.5.1/dist/panzoom.min.js"></script>
    <script>
        // Habilita el zoom con el scroll del mouse
        //element.parentElement.addEventListener('wheel', panzoom.zoomWithWheel);

        $('select[name="estado"]').on('change', function(){
          if($(this).val().length > 0){
            if($(this).val() == "RECHAZADO"){
              $('#motivo_rechazo').parent().show();
              $('#motivo_rechazo').attr('required', true);

            }else{
              $('#motivo_rechazo').parent().hide(); 
              $('#motivo_rechazo').attr('required', false);
            }
          }
        });

        $("input[type=radio]").on("change", function(){

          //$(this).is(':checked')

          if($(this).val() == "RECHAZADO"){
            $('#motivo_rechazo').parent().show();
            $('#motivo_rechazo').attr('required', true);
            $('select[name="estado"] option[value="RECHAZADO"]').prop("selected", true);
          }          
            
        });

        toastr.options.positionClass = 'toast-bottom-right';	  		

        const siguiente = (anterior, siguiente) => {

          if (anterior == 'panel-firma' && siguiente == 'panel-contrato' && !tieneActaNoFirma && !sabeFirmar) {
            toastr.warning("Debes subir el acta no firma");
            return;
          }

          const acordeon = document.getElementById(anterior);
          const radios = acordeon.querySelectorAll('.formulario-imagen .radio');

          let total_radios = radios.length;
          let faltan = false;

          radios.forEach(container => {
            const radio = container.querySelector('input');
            
            if(!radio.checked){
              //radio.focus();
              total_radios -= 1;
            }

          });

          if(total_radios < (radios.length / 2)){
            faltan = true;
            toastr.warning("Debe auditar todas las evidencias");
          }else{
            let panel = $("#"+anterior);
            let panel2 = $("#"+siguiente);

            let titulo = panel.parent().find('a');
            let titulo2 = panel2.parent().find('a');

            //ANTERIOR
            panel.parent().find('.box-header').removeClass('bg-orange').addClass('bg-green');
            titulo.attr('arial-expanded', false);
            titulo.addClass('collapsed');
            panel.attr('arial-expanded', false);
            panel.removeClass('in');

            //SIGUIENTE
            panel2.parent().show();
            titulo2.attr('arial-expanded', true);
            titulo2.removeClass('collapsed');
            panel2.attr('arial-expanded', true);
            panel2.addClass('in');

          }

          return faltan;
        }

        $('#form-auditoria').on('submit', function(e) {
          e.eventPreventDefault();
          alert('hola');
        });

        var contenedor = document.querySelectorAll('.zoom');        

        contenedor.forEach(container => {
          
          const img = container.querySelector('img');
          const btnGirar = container.querySelector('button');

          let currentRotation = 0;
          let isZoomedIn = 2;
          
          const panzoom = Panzoom(img, {
            maxScale: 5,   // Escala máxima de zoom
            minScale: 0,   // Escala mínima de zoom
            contain: 'invert', // Limita el zoom a los bordes de la imagen
            cursor: 'zoom-in',
            //canvas: true,
            setTransform: (_, { scale, x, y}) => {
              panzoom.setStyle('transform', `rotate(${currentRotation}deg) scale(${scale}) translate(${x}px, ${y}px)`)
            }
          });
          
          img.addEventListener('click', (e) => {

            if (parseInt(panzoom.getScale()) >= 5) {
              isZoomedIn = 2;
              panzoom.reset();  // Reinicia el zoom si ya está aumentado              
            }else{              
              const rect = img.getBoundingClientRect();
              panzoom.zoomToPoint(isZoomedIn, e);
              isZoomedIn += 1;
            }
          });
          

          btnGirar.addEventListener('click', () => {
            currentRotation += 90;
            //img.style.transform = `rotate(${currentRotation}deg) scale(${panzoom.getScale()})`;
            panzoom.setStyle('transform', `rotate(${currentRotation}deg) scale(${panzoom.getScale()})`)
          });
        });

        let tieneActaNoFirma = null;
        let sabeFirmar = null;

        $('#btn-subir-acta-no-firma').on('click', function (e) {
          e.preventDefault();
          const formData = new FormData(); 
          formData.append("nombre", "constancia_no_firma");
          formData.append("contrato_id", $('input[name="contrato_id"]').val());
          formData.append("accion", "subir");
          formData.append("archivo", $('input[name="archivo"]')[0].files[0]);

          $.ajax({
              url: "{{ route('contratos-archivos.store') }}", 
              type: 'post',
              data: formData,
              processData: false,
              contentType: false, 
              headers: {
                  'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
              },
              success: function (response) {
                  if (response.includes("El archivo ya existe.")) {
                    toastr.warning('El archivo ya existe.');
                  } else {
                    toastr.success('Acta subida correctamente');
                  }
                  $('#loadActaNoFirma').hide();
                  $('body').removeClass('modal-open'); 
                  $('.modal-backdrop').remove();

                  btnActaNoFirma(); 
                  tieneActaNoFirma = true;           
              },
              error: function (xhr, status, error) {
                toastr.error('Error al subir el acta');
                console.log(error);
              }
          });
          
        })

        const init = () => {
          const tiene_acta_no_firma = "{!! json_encode($tiene_acta_no_firma) !!}";
          tieneActaNoFirma = JSON.parse(tiene_acta_no_firma);

          const sabeFirmarJson = "{!! $cliente->SabeFirmar !!}";
          sabeFirmar = JSON.parse(sabeFirmarJson) === 1;
          
          if (tieneActaNoFirma) {
            btnActaNoFirma();            
          }
        }

        const btnActaNoFirma = () => {
          const btnCargarActa = $('#btn-cargar-acta');
          btnCargarActa.removeAttr('data-toggle');
          btnCargarActa.removeAttr('data-target');
          btnCargarActa.removeClass('btn-warning');
          btnCargarActa.addClass('btn-light');
          btnCargarActa.attr('disabled', 'true');
          btnCargarActa.html('<i class="fa fa-check" aria-hidden="true"></i> Acta ya cargada');
        }

        init();
    </script>
  @endsection
@endsection