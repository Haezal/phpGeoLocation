<?php  
function get_local_ipv4() {
  $localIP = $_SERVER['SERVER_ADDR'];
  return $localIP;
}

$addrs = get_local_ipv4();
// var_export($addrs);

$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
echo $ip;
?>