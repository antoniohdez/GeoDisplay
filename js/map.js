var map;
var marker;
var infowindow = null;

function openInfoWindow() {
  var markerLatLng = marker.getPosition();
	document.getElementById('latitude').value = markerLatLng.lat();
	document.getElementById('longitude').value = markerLatLng.lng();
    infowindow.setContent([
      'The current position is: ',
      markerLatLng.lat(),
      ', ',
      markerLatLng.lng()
    ].join(''));
    infowindow.open(map, marker);
}

function initialize() {
  var mapOptions = {
    zoom: 6,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map'),
    mapOptions);

  // Try HTML5 geolocation
  var pos;
  if(document.getElementById('latitude').value == ""){
	  if(navigator.geolocation) {
		  navigator.geolocation.getCurrentPosition(function(position) {
		  pos = new google.maps.LatLng(position.coords.latitude,
										   position.coords.longitude);
		  marker = new google.maps.Marker({
			position: pos,
			map: map,
			draggable: true,
			title:"Your marker"});

		  map.setCenter(pos);
		},function() {
		    handleNoGeolocation()
		    alert('Error: The Geolocation service failed.');;
		  });
	  } 
	  else {
		  // Browser doesn't support Geolocation
		  handleNoGeolocation()
		  alert('Error: Your browser doesn\'t support geolocation.');
	  }
  } 
  else {
	  pos = new google.maps.LatLng(document.getElementById('latitude').value, document.getElementById('longitude').value);
	  marker = new google.maps.Marker({
			position: pos,
			map: map,
			draggable: true,
			title:"Your marker"});

	  map.setCenter(pos);
  }
  setTimeout(function(){
		var markerLatLng = window.marker.getPosition();
		document.getElementById('latitude').value = markerLatLng.lat();
		document.getElementById('longitude').value = markerLatLng.lng();
		infowindow = new google.maps.InfoWindow({
		  maxWidth: 200
		});
		google.maps.event.addListener(marker, 'mouseup', function() {
			openInfoWindow();
		});
  }, 1000);
}


function handleNoGeolocation() {
  var options = {
    map: map,
    position: new google.maps.LatLng(37, -122),
    content: content
  };
  map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);