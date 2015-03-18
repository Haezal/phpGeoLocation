<!DOCTYPE html>
<html>
<head>
<style>
  #map-canvas {
    width: 500px;
    height: 400px;
  }
</style>

</head>
<body>

<?php  
/*
"ip": "8.8.8.8",
"hostname": "google-public-dns-a.google.com",
"loc": "37.385999999999996,-122.0838",
"org": "AS15169 Google Inc.",
"city": "Mountain View",
"region": "California",
"country": "US",
"phone": 650
*/
// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
if($_SERVER["HTTP_HOST"]=="localhost"){
	//$ip="202.185.112.50";
	$ip = get_client_ip();
}
else{
	$ip = get_client_ip();
}

$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
echo "<h5>IP : ".$ip."</h5>";
echo "Your Current Location Is : <b>".$details->city."</b>"; // -> "Mountain View"
?>
<hr>

<h4>Your coordinates</h4>

<p id="demo"></p>

<div id="map-canvas"></div>

<script>
var x = document.getElementById("demo");

getLocation(); // call direct function

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
	var latitude=position.coords.latitude; 
	var longitude=position.coords.longitude;
    x.innerHTML = "Latitude: " + latitude + 
    "<br>Longitude: " + longitude;	
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}
</script>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
  function initialize() {
    var mapCanvas = document.getElementById('map-canvas');

    var myLatlng = new google.maps.LatLng(3.1569485999999998, 101.71230299999999);
    var mapOptions = {
      center: myLatlng,
      zoom: 15,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(mapCanvas, mapOptions);

    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Anda Disini'
  	});
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
<hr>
</body>
</html>