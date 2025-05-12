<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Formato de Instalacion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/pdf.css">
    <style>
    @page { size: 21.59cm 36.56cm portrait; margin: 0.5cm;}
    
    @font-face {
      font-family: 'Calibri';
      src: url({{ storage_path('fonts\\calibri.ttf')}});      
    }

    body{
      font-family: 'Calibri';
      font-size: 12px;      
    }

    .page-break {
      page-break-after: always;
    }

    footer{
      margin: 0cm;
      height: 0cm;
      background-image: url('img/intro01.png');
    }

    .celda-pequena{
      margin: 0px;
      padding: 0px;
      border-top: solid;
    }

    table, tr, td{
      border: solid;
    }

    .sin-margen{
      margin: 0px;
      padding: 0px;      
    }

    .sin-margen > p{
      text-align: center !important;
    }

    .centrado{
      text-align: center;
    }

  </style>

  </head>
  <body>
    <main>
      <div class="container contenedor">
        <table class="table principal table-bordered">
          <tr>
            @if ($data['proyecto_id'] == 14)
              <td rowspan="2">
                <img src="img/grupo_energia_bogota.png" class="logo"> <br>
                <img src="img/logo_sisteco.png" class="logo">
              </td>
            @else
              <td rowspan="2" style="margin: 0px;">
                <img src="img/amigored.png" class="logo">
              </td>
            @endif

            <td>
              <h2 class="titulo">SISTEMAS Y TELECOMUNICACIONES DEL ORIENTE S.A.S</h2>
            </td>
            <td>
              <p>No. control documental FIC850-1</p>
            </td>           
          </tr>

          <tr>
            <td>
              <h2 class="titulo">FORMATO DE VISITA DE INSTALACION DE ACCESOS</h2>
            </td>
            <td class="sin-margen">
              <p>Versión 00</p>
              <p class="celda-pequena">Pagina 1 de n</p>
            </td>         
          </tr>
        </table>

        <table class="table principal table-bordered table-sm" style="border: 0px;">
          <tr style="border: 0px;">
            <td style="border: 0px;">
              <p style="text-align: right;">Codigo Dane munipio</p>
            </td>
            <td>{{$data['codigo_dane_municipio']}}</td>
            <td style="border: 0px;">
              <p style="text-align: right;">Numero Orden de trabajo</p>
            </td>
            <td>{{$data['orden_trabajo']}}</td>
          </tr>
          <tr style="border: 0px;">
            <td style="border: 0px;">
              <p style="text-align: right;">ID consecutivo acceso</p>
            </td>
            <td></td>            
          </tr>
        </table>


        <table class="table principal table-bordered table-sm">
          <tr>
            <td colspan="3" bgcolor="#305496">
              <p style="text-align: center; color: #ffffff;">INFORMACIÓN GENERAL</p>
            </td>
          </tr>
          <tr>
            <td>
              <p style="text-align: center;">Fecha de instalación (dd/mm/aaaa)</p>
            </td>
            <td>
              <p style="text-align: center;">DEPARTAMENTO</p>
            </td>
            <td>
              <p style="text-align: center;">MUNICIPIO</p>
            </td>
          </tr>
          <tr>
            <td>
              <p style="text-align: center;">{{$data['fecha_instalacion']}}</p>
            </td>
            <td>
              <p style="text-align: center;">{{$data['departamento']}}</p>
            </td>
            <td>
              <p style="text-align: center;">{{$data['municipio']}}</p>
            </td>
          </tr>
        </table>

        <table class="table principal table-bordered table-sm">
          <tr>
            <td colspan="4" bgcolor="#305496">
              <p style="text-align: center; color: #ffffff;">INFORMACIÓN DEL TECNICO QUE REALIZA LA INSTALACION</p>
            </td>
          </tr>
          <tr>
            <td>
              <p style="text-align: center;">NOMBRE </p>
            </td>
            <td>
              <p style="text-align: center;">CONTRATISTA</p>
            </td>
            <td>
              <p style="text-align: center;">CEDULA</p>
            </td>
            <td>
              <p style="text-align: center;">TELEFONO</p>
            </td>
          </tr>
          <tr>
            <td>
              <p style="text-align: center;">{{$data['nombre_tecnico']}}</p>
            </td>
            <td>
              <p style="text-align: center;">BITT S.A.S</p>
            </td>
            <td>
              <p style="text-align: center;">{{$data['cedula_tecnico']}}</p>
            </td>
            <td>
              <p style="text-align: center;">{{$data['celular_tecnico']}}</p>
            </td>
          </tr>
        </table>

        <table class="table principal table-bordered table-sm">
          <tr>
            <td colspan="4" bgcolor="#bf8f00">
              <p style="text-align: center; color: #ffffff;">INFORMACIÓN DEL CLIENTE</p>
            </td>
          </tr>
          <tr>
            <td>
              <p style="text-align: center;">NOMBRE </p>
            </td>
            <td>
              <p style="text-align: center;">CEDULA</p>
            </td>
            <td>
              <p style="text-align: center;">TELEFONO</p>
            </td>
            <td>
              <p style="text-align: center;">CORREO ELECTRONICO</p>
            </td>
          </tr>
          <tr>
            <td>
              <p style="text-align: center;">{{$data['nombre_cliente']}}</p>
            </td>
            <td>
              <p style="text-align: center;">{{$data['cedula_cliente']}}</p>
            </td>
            <td>
              <p style="text-align: center;">{{$data['celular_cliente']}}</p>
            </td>
            <td>
              <p style="text-align: center;">{{$data['correo']}}</p>
            </td>
          </tr>
          <tr>
            <td colspan="4" bgcolor="#bf8f00">
              <p style="text-align: center; color: #ffffff;">INFORMACIÓN DEL LUGAR DE INSTALACIÓN</p>
            </td>
          </tr>
          <tr>
            <td>
              <p style="text-align: center;">DIRECCIÓN RESIDENCIA: </p>
            </td>
            <td>
              {{$data['direccion']}}
            </td>
            @if ($data['proyecto_id'] == 14)
            <td>
              <p style="text-align: center;">COMUNIDAD</p>
            </td>
            <td>
              {{$data['comunidad_id']}}
            </td>
            @else
            <td>
              <p style="text-align: center;">ESTRATO:</p>
            </td>
            <td>
              {{$data['estrato']}}
            </td>
            @endif
          </tr>

          <tr>
            <td>
              <p>Cooordenadas GPS </p>
            </td>
            <td>
              {{$data['coordenadas']}}
            </td>

            @if ($data['proyecto_id'] == 14)
            <td>
              <p>BENEFICIARIO:</p>
            </td>
            <td>
              {{$data['tipo_comunidad']}}
            </td>
            @else
            <td>
              <p>TIPO DE BENEFICIARIO:</p>
            </td>
            <td>
              {{$data['tipo_beneficiario']}}
            </td>
            @endif
          </tr>

          <tr>
            <td colspan="2"><p>Tipo de Tecnologia implementada: (4G,4.5G, Wifi, HFC, xDSL, FTTH)</p></td>
            @if ($data['proyecto_id'] == 14)
            <td colspan="2">WIFI</td>
            @else
            <td colspan="2">FTTH</td>
            @endif
          </tr>
          <tr>
            <td><p>IDENTIFICACION DE LA RED:</p></td>
            @if ($data['proyecto_id'] == 14)
            <td colspan="3">ENLAZANET 2</td>
            @else
            <td colspan="3">AMIGO RED</td>
            @endif
          </tr>
        </table>

        <table class="table principal table-bordered table-sm">
          <tr bgcolor="#305496">
            <td>
              <p style="text-align: center;color: #ffffff;">EQUIPO</p>
            </td>
            <td>
              <p style="text-align: center;color: #ffffff;">MARCA-REFERENCIA</p>
            </td>
            <td>
              <p style="text-align: center;color: #ffffff;">NÚMERO SERIAL</p>
            </td>
            <td>
              <p style="text-align: center;color: #ffffff;">ESTADO</p>
            </td>
          </tr>
          @if ($data['proyecto_id'] == 14)
          {{-- ESTE ESPACIO SE DEJA PORQUE EN EL FORMULARIO DE LA GUAJIRA NO SON NECESARIOS LOS CAMPOS DEL ELSE --}}
          @else
          <tr>
            <td>
              <p>Tipo de terminal de usuario (UE,CPE,etc)</p>
            </td>
            <td>{{$data['marca_ont']}}</td>
            <td>{{$data['serial_ont']}}</td>
            <td>{{$data['estado_ont']}}</td>
          </tr>
          <tr>
            <td><p>Tipo de equipo utilizado por cliente para conexión</p></td>
            <td>{{$data['tipo_equipo_cliente_conexion']}} {{$data['marca_equipo']}}</td>
            <td>{{$data['serial_equipo']}}</td>
            <td>{{$data['estado_equipo']}}</td>
          </tr>
          @endif

          <tr>
            <td><p>Número de equipos conectados</p></td>
            <td colspan="3">{{$data['cantidad_equipos_conectados']}}</td>
            
          </tr>
          <tr>
            <td><p>Tipo de conexión eléctrica</p></td>
            <td colspan="3">{{$data['tipo_conexion_electrica']}}</td>            
          </tr>
          <tr>
            <td><p>Tipo de protección eléctrica (Estabilizador, UPS, protección de equipos)</p></td>
            <td>{{$data['tipo_proteccion_electrica']}}</td>
            <td>{{$data['serial_proteccion_electrica']}}</td>
            <td>{{$data['estado_conexion_electrica']}}</td>
          </tr>
        </table>

        <table class="table principal table-bordered table-sm">
          <tr>
            <td colspan="4" bgcolor="#305496">
              <p style="text-align: center; color: #ffffff;">PRUEBAS DEL SERVICIO</p>
            </td>
          </tr>
          {{-- CAMPO PARA AGREGAR LAS FOTOS DE LOS ELEMENTOS DEPENDIENDO DE QUE TIPO DE CONEXION SEA --}}
          <tr>
            <td>
              <p>Velocidad de bajada</p>
            </td>
            <td>{{$data['velocidad_bajada']}}</td>
            <td>
              <p>Velocidad de subida</p>
            </td>
            <td>{{$data['velocidad_subida']}}</td>
          </tr>
          <tr>
            <td>
              <p>Ping</p>
            </td>
            <td style="width: 210px; height: auto; text-align: center; overflow: hidden;">
              @php
                  $pingPath = public_path(Storage::url($data['ping']));
              @endphp
              @if(!empty($data['ping']) && file_exists($pingPath))
                  <img src="{{ $pingPath }}" width="210">
              @endif
            </td>
            <td>
              <p>Speedtest</p>
            </td>
            <td style="width: 210px; height: auto; text-align: center; overflow: hidden;">
              @php
                  $speedPath = public_path(Storage::url($data['speedtest']));
              @endphp
              @if(!empty($data['speedtest']) && file_exists($speedPath))
                  <img src="{{ $speedPath }}" width="210">
              @endif
            </td>
          </tr>
          <tr>
            <td>
              <p>Navegación web - Página de prueba Google</p>
            </td>
            <td style="width: 210px; height: auto; text-align: center; overflow: hidden;">
              @php
                  $navegacionPath = public_path(Storage::url($data['google']));
              @endphp
              @if(!empty($data['google']) && file_exists($navegacionPath))
                  <img src="{{ $navegacionPath }}" width="210">
              @endif
            </td>
            <td>
              <p>Video streaming - Youtube</p>
            </td>
            <td style="width: 210px; height: auto; text-align: center; overflow: hidden;">
              @php
                  $streamPath = public_path(Storage::url($data['youtube']));
              @endphp
              @if(!empty($data['youtube']) && file_exists($streamPath))
                  <img src="{{ $streamPath }}" width="210">
              @endif
            </td>
          </tr>
          <tr>
            <td>
              <p>Navegación web - Página de prueba MinTIC</p>
            </td>
            <td style="width: 210px; height: auto; text-align: center; overflow: hidden;">
              @php
                  $minticPath = public_path(Storage::url($data['mintic']));
              @endphp
              @if(!empty($data['mintic']) && file_exists($minticPath))
                  <img src="{{ $minticPath }}"width="210">
              @endif
            </td>
            <td>
              <p>Instalacion</p>
            </td>
            <td style="width: 210px; height: auto; text-align: center; overflow: hidden;">
              @php
                  $instalacionPath = public_path(Storage::url($data['instalacion']));
              @endphp
              @if(!empty($data['instalacion']) && file_exists($instalacionPath))
                  <img src="{{ $instalacionPath }}"  width="210">
              @endif
            </td>
          </tr>
          <tr>
            {{-- EVIDENCIAS DE LOS ELEMENTOS DE INSTALACIÓN EN DOS COLUMNAS --}}
            @if ($data['proyecto_id'] == 14 && isset($data['elementos_guajira']) && count($data['elementos_guajira']))
                <table class="table principal table-bordered table-sm">
                    <tr>
                        <td colspan="4" bgcolor="#305496">
                            <p style="text-align: center; color: #ffffff;">EVIDENCIAS DE LOS ELEMENTOS DE INSTALACIÓN</p>
                        </td>
                    </tr>
                    @foreach($data['elementos_guajira']->chunk(2) as $chunk)
                        <tr>
                            @foreach($chunk as $elemento)
                                <td>
                                    <p>{{ $elemento->element_name }}</p>
                                </td>
                                <td style="width: 210px; height: auto; text-align: center; overflow: hidden;">
                                    @php
                                        $fotoPath = public_path('storage/' . $elemento->fotografias);
                                    @endphp
                                    @if(!empty($elemento->fotografias) && file_exists($fotoPath))
                                        <img src="{{ $fotoPath }}" width="210" style="max-width: 210px; max-height: 250px; object-fit: contain;">
                                    @endif
                                </td>
                            @endforeach
                            {{-- Si hay un número impar de elementos, agrega celdas vacías --}}
                            @if($chunk->count() < 2)
                                <td></td><td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            @endif
          </tr>
        </table>

        <table class="table principal table-sm" style="border: 0px;">
          <tr style="border: 0px;">
            <td style="border: 0px;">
              <p>SERVICIO QUEDA ACTIVO</p>
              <div class="checkbox">
                <label>
                  <input type="checkbox" {{($data['servicio_activo'] == 'SI')? 'checked' : ''}}>
                  SI
                </label>
                <label>
                  <input type="checkbox" {{($data['servicio_activo'] == 'NO')? 'checked' : ''}}>
                  NO
                </label>
              </div> 
            </td>            
            <td style="border: 0px;">
              <p>CUMPLE CON LA VELOCIDAD CONTRATADA</p>
              <div class="checkbox">
                <label>
                  <input type="checkbox" {{($data['cumple_velocidad_contratada'] == 'SI')? 'checked' : ''}}>
                  SI
                </label>
                <label>
                  <input type="checkbox" {{($data['cumple_velocidad_contratada'] == 'NO')? 'checked' : ''}}>
                  NO
                </label>
              </div> 
            </td>
          </tr>
        </table>

        

        <table class="table principal table-bordered table-sm">
          <tr>
            <td bgcolor="#305496">
              <p style="text-align: center; color: #ffffff;">OBSERVACIONES GENERALES DE LA INSTALACION</p>
            </td>
          </tr>
          <tr>
            <td>
              <p>{{$data['observaciones']}}</p>
            </td>            
          </tr>          
        </table>
        <p>El cliente del servicio de internet, recibe a satisfaccion la conexión.</p>

        <table class="table principal table-bordered table-sm">
          <tr>
            <td>              
              <p style="text-align: center;">Firma Cliente</p>
            </td>
            <td>              
              <p style="text-align: center;">Firma contratista instalación</p>
            </td>
          </tr>
          <tr>
            <td>
              @php
                  $firmaPath = public_path(Storage::url($data['firma_cliente']));
              @endphp
              @if(!empty($data['firma_cliente']) && file_exists($firmaPath))
                  <img src="{{ $firmaPath }}"  width="210">
              @endif
              <p class="celda-pequena centrado">FIRMA</p>
              {{$data['nombre_cliente']}}
              <p class="celda-pequena centrado">NOMBRE CLIENTE</p>
              {{$data['cedula_cliente']}}
              <p class="celda-pequena centrado">CEDULA</p>
            </td>
            <td>
              @php
                  $firmaInstPath = public_path(Storage::url($data['firma_instalador']));
              @endphp
              @if(!empty($data['firma_instalador']) && file_exists($firmaInstPath))
                  <img src="{{ $firmaInstPath }}"  width="210">
              @endif
              <p class="celda-pequena centrado">FIRMA</p>
              {{$data['nombre_tecnico']}}
              <p class="celda-pequena centrado">NOMBRE DEL TECNICO INSTALACION</p>
              {{$data['cedula_tecnico']}}
              <p class="celda-pequena centrado">CEDULA</p>
            </td>
          </tr>
        </table>

      </div>
    </main>
  </body>
</html>