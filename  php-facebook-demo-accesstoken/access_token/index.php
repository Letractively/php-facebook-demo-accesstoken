<?php
	require_once ('../src/facebook.php');
	require_once ('config.php');
	
	$facebook = new Facebook($config); 

$signed_request = $facebook->getSignedRequest(); 
/*if($signed_request["page"]["liked"]!=1){ 
 $is_liked = false;  
}else{ 
 $is_liked = true; 
} */
    ?> 
     
<!doctype html> 
<html xmlns:fb="http://www.facebook.com/2008/fbml"> 
<meta charset="UTF-8">
<head> 
<title>Tutorial FaceBook App</title> 

</head> 
<body> 
<?php 

    $user = $facebook->getUser(); 
    if ($user) { 
      try { 
        // Proceed knowing you have a logged in user who's authenticated. 
        $user_profile = $facebook->api('/me'); 
		?>
		<img src="https://graph.facebook.com/<?php echo $user; ?>/picture"> 
        <p> Wellcome : <?echo $user_profile['name']?></p> 
		<?php
      } catch (FacebookApiException $e) { 
        error_log($e); 
        $user = null; 
      } 
        $code = $_REQUEST["code"]; 
        if(empty($code)) { 
            $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection 
            $dialog_url = "http://facebook.com/dialog/oauth?client_id="  
            . $config["appId"] . "&redirect_uri=" . urlencode("http://apps.facebook.com/token_app") . "&state=" 
            . $_SESSION['state']; 
     
            echo("<script> top.location.href='" . $dialog_url . "'</script>"); 
        } 
        //get user access_token 
        $token_url = 'https://graph.facebook.com/oauth/access_token?client_id=' 
        . $config["appId"] . '&redirect_uri=' . urlencode('http://apps.facebook.com/token_app')  
        . '&client_secret=' . $config["secret"]  
        . '&code=' . $code; 
        $access_token = file_get_contents($token_url); 
         
        // Run fql query 
        $fql_query_url = 'https://graph.facebook.com' 
        . '/fql?q=SELECT+page_id+FROM+page_fan+WHERE+uid=me()' 
        . '&' . $access_token; 
        $fql_query_result = file_get_contents($fql_query_url); 
        $fql_query_obj = json_decode($fql_query_result, true); 
		//echo count($fql_query_obj);
		//exit;
		/*for(int i = 0; i<$fql_query_object.lenght;i++)
		{
			$birthday = $fql_query_obj["data"][i]["page_id"]; 
		}
		*/
		 $sql = "SELECT page_id,name,type,page_url
              FROM page 
              WHERE page_id 
              IN (
                    SELECT page_id 
                    FROM page_fan
                    WHERE uid = me())";
            $result = $facebook->api(array(
                'method' => 'fql.query',
                'query' => $sql,
                    ));
			//print_r($result);
			//exit;
            foreach ($result as $page_like) {
                ?>
        
        <div> 
			<img src="https://graph.facebook.com/<?php echo $page_like['page_id'] ?>/picture?type=square">
              <p><?php echo $page_like['name']; ?></p> 
             
        </div> 
        <?php 
		}
    }else{ 
        echo("<script> top.location.href='" . $facebook->getLoginUrl() . "&scope=user_likes,friends_likes'</script>"); 
    } 
?>
</body> 
</html>