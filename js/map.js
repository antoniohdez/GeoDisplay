var map;
var marker;
var markers = [];
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
			title:"Your marker"
		  });

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
var input = /** @type {HTMLInputElement} */(
      document.getElementById('pac-input'));
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));

  // Listen for the event fired when the user selects an item from the
  // pick list. Retrieve the matching places for that item.
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      
      marker = new google.maps.Marker({
        map: map,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });

      markers.push(marker);

      bounds.extend(place.geometry.location);
    }

    map.fitBounds(bounds);
  });

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
  });
  
  setPositionForm();

}

function setPositionForm(){
    if ( marker != null ){
        var markerLatLng = marker.getPosition();
        document.getElementById('latitude').value = markerLatLng.lat();
        document.getElementById('longitude').value = markerLatLng.lng();
        infowindow = new google.maps.InfoWindow({
          maxWidth: 200
        });
        google.maps.event.addListener(marker, 'mouseup', function() {
          openInfoWindow();
        });
    }
    else
    {
        window.setTimeout("setPositionForm();",100);
    }
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