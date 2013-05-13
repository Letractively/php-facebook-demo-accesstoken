<?php
require_once '../src/facebook.php';


$config = array(); 
$config["appId"] = '539935456045933'; 
$config["secret"] = 'b8e72a6e4deba0ad85dd803074b47d27'; 

$facebook = new Facebook($config);
 
 $me = $facebook->api('/me');
?>