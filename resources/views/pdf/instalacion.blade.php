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
            <td rowspan="2" style="margin: 0px;">
              <img src="img/amigored.png" class="logo">
            </td>

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
            <td>
              <p style="text-align: center;">ESTRATO:</p>
            </td>
            <td>
              {{$data['estrato']}}
            </td>
          </tr>

          <tr>
            <td>
              <p>Cooordenadas GPS </p>
            </td>
            <td>
              {{$data['coordenadas']}}
            </td>
            <td>
              <p>TIPO DE BENEFICIARIO:</p>
            </td>
            <td>
              {{$data['tipo_beneficiario']}}
            </td>
          </tr>

          <tr>
            <td colspan="2"><p>Tipo de Tecnologia implementada: (4G,4.5G, Wifi, HFC, xDSL, FTTH)</p></td>
            <td colspan="2">FTTH</td>
          </tr>
          <tr>
            <td><p>IDENTIFICACION DE LA RED:</p></td>
            <td colspan="3">AMIGO RED</td>
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
            <td>
              <img src=".{{Storage::url($data['ping'])}}" width="210">
            </td>
            <td>
              <p>Speedtest</p>
            </td>
            <td>
              <img src=".{{Storage::url($data['speedtest'])}}" width="210">
            </td>
          </tr>
          <tr>
            <td>
              <p>Navegación web - Página de prueba Google</p>
            </td>
            <td>
              <img src=".{{Storage::url($data['google'])}}" width="210">
            </td>
            <td>
              <p>Video streaming - Youtube</p>
            </td>
            <td>
              <img src=".{{Storage::url($data['youtube'])}}" width="210">
            </td>
          </tr>
          <tr>
            <td>
              <p>Navegación web - Página de prueba MinTIC</p>
            </td>
            <td>
              <img src=".{{Storage::url($data['mintic'])}}" width="210">
            </td>
            <td>
              <p>Instalacion</p>
            </td>
            <td>
              <img src=".{{Storage::url($data['instalacion'])}}" width="210">
            </td>
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
              <img src=".{{Storage::url($data['firma_cliente'])}}" width="210">
              <p class="celda-pequena centrado">FIRMA</p>
              {{$data['nombre_cliente']}}
              <p class="celda-pequena centrado">NOMBRE CLIENTE</p>
              {{$data['cedula_cliente']}}
              <p class="celda-pequena centrado">CEDULA</p>
            </td>
            <td>
              <img src=".{{Storage::url($data['firma_instalador'])}}" width="210">
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