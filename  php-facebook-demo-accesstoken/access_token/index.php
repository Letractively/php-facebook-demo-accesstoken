<?php
	require_once ('config.php');
	
	$user_id = $facebook->getUser();
	$me = $facebook->api('/me');
	
?>