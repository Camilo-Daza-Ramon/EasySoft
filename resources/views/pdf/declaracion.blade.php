<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Declaración Juramentada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/pdf.css">
    <style>
    @page { size: 21.59cm 27.94cm portrait;}
    
    @font-face {
      font-family: 'Calibri';
      src: url({{ storage_path('fonts\\calibri.ttf')}});      
    }

    body{
      margin-top: 50px;
      font-family: 'Calibri';
      font-size: 12px;      
    }

    .page-break {
      page-break-after: always;
    }

    footer{
      margin: 0cm;
      background-image: url('img/intro01.png');
    }
  </style>

  </head>
  <body>
    <header>
      <img src="img/amigored.png" class="logo"><img src="img/logo_mintic.png" class="logo3"><img src="img/logo-presidencia.png" class="logo3">
    </header>
    <main>
      <div class="container contenedor">
        <h2 style="text-align: center; font-size: 20px; font-weight: 400;">DECLARACIÓN JURAMENTADA</h2>
        <br>
        <br>
        <br>
        <p>Yo <b>{{$data['nombre_suscriptor']}}</b>, que resido en la dirección señalada en este formato, mayor de edad e identifcado como aparece al pie de mi firma, por medio del presente escrito, Declaro bajo la gravedad de Juramento, que soy un nuevo usuario, es decir que ni yo, ni los miembros de mi núcleo familiar que residen en el mismo predio para el que se requiere la conexión, hemos contado con la prestación del servicio de Internet Fijo, al menos durante los seis meses anteriores a la presente suscripción.</p>
        <p>Así mismo, para ser benefciario del Proyecto Incentivos a la Demanda de Internet fjo II del Fondo Único de Tecnologías de la Información y las Comunicaciones, entrego la siguiente documentación: <b>I)</b> Copia de mi cédula de ciudadanía <b>II)</b> Recibo de servicio público (agua o energía) o constancia de la autoridad territorial que corresponde al predio donde se instalará el servicio de internet, el cual es de estrato 1 o 2. <b>III)</b> Foto de la fachada del predio en la que resido y en donde se instalará el servicio de internet, en la que aparece legible la nomenclatura de la dirección.</p>
        <p>Declaro igualmente que la documentación que adjunto como requisito para ser beneficiario del proyecto, es verdadera.</p>
        <p>Se firma en constancia a los <b>{{date("d", strtotime( $data['fecha_contrato']))}}</b> días del mes de <b>{{date("M", strtotime( $data['fecha_contrato']))}}</b> del año <b>{{date("Y", strtotime( $data['fecha_contrato']))}}</b></p>
        <br>
        <br>

        <p><b>Firma:</b> <img src=".{{Storage::url($data['firma'])}}" width="220" style="margin: 5px;"> <br>
          <b>Nombres:</b> {{$data['nombre']}} <b>Apellidos:</b> {{ $data['apellido']}} <br>
          <b>Cédula No:</b> {{$data['identificacion']}} <b>Teléfono No:</b> {{$data['telefono']}} <br>
          <b>Dirección:</b> {{$data['direccion']}} <b>Municipio:</b> {{$data['municipio']}} ({{$data['departamento']}}) <br>
          <b>Correo Electrónico:</b> {{$data['correo']}} <br>
        </p>

        <footer>
          <table width="100%">
            <tr>
              <td><img src="img/logo_mastic.png" class="logo"></td>
              <td>
                <center><span>Calle 35 #17-77 Centro - Edificio Bancoquia Ofic. 301 – 302 PBX: +57 (7) 6334050 <br>Línea Gratuita: 01 8000 945 080 E-mail servicioalcliente@amigored.com.co. <br>GC F 004   Versión 04   11 Feb 2020</span></center>
              </td>
            </tr>
          </table>         
        </footer>
      </div>
    </main>
  </body>
</html>