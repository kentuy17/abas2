<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>Reverse Geocoding</title>
		<style>
			html, body {
				height: 100%;
				margin: 0;
				padding: 0;
			}
			#map {
				height: 100%;
			}
			#floating-panel {
				position: absolute;
				top: 10px;
				left: 25%;
				z-index: 5;
				background-color: #fff;
				padding: 5px;
				border: 1px solid #999;
				text-align: center;
				font-family: 'Roboto','sans-serif';
				line-height: 30px;
				padding-left: 10px;
			}
		</style>
		<style>
			#floating-panel {
				position: absolute;
				top: 5px;
				left: 50%;
				margin-left: -180px;
				width: 350px;
				z-index: 5;
				background-color: #fff;
				padding: 5px;
				border: 1px solid #999;
			}
			#latlng {
				width: 225px;
			}
		</style>
  </head>
  <body>
    <div id="floating-panel">
      <input id="latlng" type="text" value="40.714224,-73.961452">
      <input id="submit" type="button" value="Reverse Geocode">
    </div>
    <div id="map"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAPSKEY; ?>&signed_in=true&callback=initMap" async defer></script>
    <script>
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 8,
    center: {lat: 40.731, lng: -73.997}
  });
  var geocoder = new google.maps.Geocoder;
  var infowindow = new google.maps.InfoWindow;

  document.getElementById('submit').addEventListener('click', function() {
    geocodeLatLng(geocoder, map, infowindow);
  });
}

function geocodeLatLng(geocoder, map, infowindow) {
  var input = document.getElementById('latlng').value;
  var latlngStr = input.split(',', 2);
  var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[1]) {
		alert(results[1].formatted_address);
        // map.setZoom(11);
        // var marker = new google.maps.Marker({
          // position: latlng,
          // map: map
        // });
        // infowindow.setContent(results[1].formatted_address);
        // infowindow.open(map, marker);
      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}

    </script>
  </body>
</html>