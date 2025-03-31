var map;
var marcadores = [];
var localidades = [];
var imagen = "";
var categorias_checked;
var valores = [];

function initAutocomplete() {

  var mapa = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 3.417226, lng: -76.5407485},
    zoom: 12,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var limites = new google.maps.LatLngBounds();

  var infowindow = new google.maps.InfoWindow();

  var marcador, i;

  graficar();
}


function graficar(){

    localidades = [];
    var data = {{$graficar}};

    $.each(data, function(index, ticketObj){
      var url;
      url = "#";
      imagen = "img/video.png";
      
      localidades.push([ticketObj.latitud, ticketObj.longitud, "", ticketObj.titulo, imagen , ticketObj.fecha, ticketObj.direccion, url]);                              
    });


  if (localidades.length > 0) {

    for (i = 0; i < localidades.length; i++) {

      marcador = new google.maps.Marker({

        position: new google.maps.LatLng(localidades[i][0], localidades[i][1]),

        map: mapa,

        animation: google.maps.Animation.DROP,

        icon: localidades[i][2]

      });

      marcadores.push(marcador);

      limites.extend(marcador.position);

      google.maps.event.addListener(marcador, 'click', (function(marcador, i) {

        return function() {

          //infowindow.setContent(localidades[i][3]);
          infowindow.setContent('<h4>' + localidades[i][3] +' </h4> <img class="pull-left" src="'+ localidades[i][4] +'" width="90px" height="80px" alt="..." style="margin-right: 5px;"><label>Fecha Reporte:</label> '+ localidades[i][5] +' <br> <label>Direccion:</label> '+ localidades[i][6] +'<br> <a href="'+ localidades[i][7] +'"> Más Información</a>');

          infowindow.open(mapa, marcador);

        }

      })(marcador, i));

    }

    mapa.fitBounds(limites);

  }else{
     alert("No hay datos");
  }

}

//google.maps.event.addDomListener(window, 'load', initAutocomplete);

