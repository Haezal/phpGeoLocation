<!DOCTYPE html>
<html>
<head>
<style>
  #map-canvas {
    width: 500px;
    height: 250px;
  }
</style>

</head>
<body>

<?php  
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
	$ip="202.185.112.50";
	// $ip = get_client_ip();
}
else{
	$ip = get_client_ip();
}

$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
echo "<h2>Using : http://ipinfo.io</h2>";
echo "<h5>IP : ".$ip."</h5>";
echo "Your Current Location Is : <b>".$details->city."</b>"; // -> "Mountain View"

$loc = $details->loc;
$loc = split(",", $loc);

echo "<pre>";print_r($details);echo "</pre>";
?>

<input type="hidden" id="latitude" value="<?php echo $loc[0] ?>">
<input type="hidden" id="longitude" value="<?php echo $loc[1] ?>">
<div id="map-canvas"></div>
<hr>


<h4>Using HTML5 Geolocation</h4>

<p id="demo"></p>
<div id="mapholder"></div>

<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
var x = document.getElementById("demo");
// var latitude;
// var longitude;
getLocation(); // call direct function

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    lat = position.coords.latitude;
    lon = position.coords.longitude;
    latlon = new google.maps.LatLng(lat, lon)
    mapholder = document.getElementById('mapholder')
    mapholder.style.height = '250px';
    mapholder.style.width = '500px';

    var myOptions = {
    center:latlon,zoom:14,
    mapTypeId:google.maps.MapTypeId.ROADMAP,
    mapTypeControl:false,
    navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
    }
    
    var map = new google.maps.Map(document.getElementById("mapholder"), myOptions);
    var marker = new google.maps.Marker({position:latlon,map:map,title:"You are here!"});

    x.innerHTML = "Latitude: " + lat + 
    "<br>Longitude: " + lon;	
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

function initialize() {
	var mapCanvas = document.getElementById('map-canvas');

	// get value from text box
	var latitude = document.getElementById('latitude').value;
	var longitude = document.getElementById('longitude').value;

	var myLatlng = new google.maps.LatLng(latitude, longitude);
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
// show maps bagi yang dapat value dari http://ipinfo.io
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<hr>
</body>
</html>