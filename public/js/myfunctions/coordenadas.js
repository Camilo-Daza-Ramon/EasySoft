function getUserPosition() {
	if (navigator.geolocation) {
      	navigator.geolocation.getCurrentPosition(onSuccessGeolocating, onErrorGeolocating,{
      		enableHighAccuracy: true,
      		maximumAge:         5000,
      		timeout:            10000
      	});
     }else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function onSuccessGeolocating(position){
    latitud = position.coords.latitude;
    longitud = position.coords.longitude;
    $('#coordenadas').val(latitud + ','+ longitud);
}

function onErrorGeolocating(error){
  switch(error.code)
  {
    case error.PERMISSION_DENIED:
      alert('ERROR: No se permitió o no se tienen suficientes privilegios para acceder al servicio de geolocalización.');
          $('#permisos').modal('show');
    break;

    case error.POSITION_UNAVAILABLE:
      alert("ERROR: El dispositivo no pudo determinar correctamente su ubicación.");
    break;

    case error.TIMEOUT:
      alert("ERROR: El intento de geolocalización tomó mas tiempo del permitido.");
    break;

    default:
      alert("ERROR: Problema desconocido.");
    break;
  }
}