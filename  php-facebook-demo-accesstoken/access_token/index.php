<?php
	require_once ('../src/facebook.php');
	require_once ('config.php');
	
//	$facebook = new Facebook($config); 

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

<body> 
<?php 

    $user = $facebook->getUser(); 
	$me = $facebook->api('/me'); 
	?>
		<div>
		<img src="https://graph.facebook.com/<?php echo $user; ?>/picture?type=large"> 
        <p> Wellcome :  <a href='<?= $me['link']?>'>Phạm Hoàng Nhật Thanh
		</a></p>
		<form action="checkform.php" method="post">
        <input type="text" id="ten_text" name="ten" width="500 px" height="50 px">
        <input type="submit" value="submit"><br>
		</div>
      
	<?php
    if ($user) { 
      try { 
        
        $user_profile = $me; 
		
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
		//$url_with_token = $token_url."";
        $access_token = file_get_contents($token_url); 
         
        // Run fql query 
        $fql_query_url = 'https://graph.facebook.com' 
        . '/fql?q=SELECT+page_id+FROM+page_fan+WHERE+uid=me()' 
        . '&' . $access_token; 
		 $fql_query_result = file_get_contents($fql_query_url);
		$fql_query_obj = json_decode($fql_query_result, true); 
       
        
		
		?>
		<table>
			<tr>
		<?php
			
			$i=0;
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
                <td>
                    <a href="load_check.php?page_id=<?php echo $page_like['page_id']?>">
                        <img src="https://graph.facebook.com/<?php echo $page_like['page_id'] ?>/picture?type=square"><br>
                        <?php
                        echo $page_like['name'];
                        echo"<br>";
                        ?>
                    </a>
                    <?php
                    $i++;
                    if ($i % 5 == 0)
                        echo '</tr>';
                    ?>

                </td>
                <?php
            }
            ?>
        </tr>
	<?php	
    }else{ 
        echo("<script> top.location.href='" . $facebook->getLoginUrl() . "&scope=user_likes,friends_likes'</script>"); 
    } 
?>
</body> 
</html>