<?php
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	require_once ('../src/facebook.php');
	require_once ('config.php');
	


 $param['scope'] = 'user_likes, friends_likes';
 $param['redirect_uri']= 'http://apps.facebook.com/token_app/';
 

//if(!$signed_request->page->liked) {
//header("Location: like.php");
	//	}

    ?> 
     
<!doctype html> 
<html xmlns:fb="http://www.facebook.com/2008/fbml"> 
<link href="Css/style.css" rel="stylesheet" type="text/css">
<meta charset="UTF-8">

<body> 
<div class="wrapper">
<?php 
	//if($is_liked )
	//{ 
		$user = $facebook->getUser(); 
		
			if ($user) { 
				  try { 
						$me = $facebook->api('/me');
						$code = $_REQUEST["code"]; 
						if(empty($code)) { 
							$_SESSION['state'] = md5(uniqid(rand(), TRUE)); 
							$dialog_url = "http://facebook.com/dialog/oauth?client_id="  
							. $config["appId"] . "&redirect_uri=" .$param["redirect_uri"] . "&state=" 
							. $_SESSION['state']
							; 
					 
							exit("Loading ...<script>window.top.location.replace('$dialog_url');</script>");
						} 
						//get user access_token 
						$token_url = 'https://graph.facebook.com/oauth/access_token?client_id=' 
						. $config["appId"] . '&redirect_uri=' .$param["redirect_uri"]  
						. '&client_secret=' . $config["secret"]  
						. '&code=' . $code;	
						$access_token = file_get_contents($token_url); 
						
						?>
					
                        	<div class="headerWrapper">
                                <div class="headerMain">
                                    <img src="https://graph.facebook.com/<?php echo $user; ?>/picture?width=300&height=300"> 
                                    <p> Wellcome :  <a href='<?= $me['link']?>'><?php echo $me['name']?></a></p>
							<div class="headerSearch">
							<form action="checkform.php" method="post">
								<input name="ten" type="text" class="text" value="Tìm kiếm"/>
                                <input name="btn" type="submit" class="btn"/>
							</form>
                            </div>
							</div>
                          </div>
						
                       
                        <div class="middleWrapper">
                        <div class="middleLeft-Column">
                        	<div class="left-columnWidgetTitle">		
                            	<a>List Page</a>
                            </div>
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
											
											
										
											
											
											foreach ($result as $page_like) {
											?>
												<ul>
													
									<li><a href='load_check.php?page_id=<?php echo $page_like['page_id']?>'><?=$page_like['name']?></a></li>
												
												</ul>
											<?php
											}
											?>
								
                        </div>
						<div class="middleContent">			
						<div class="contentMain">
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
											
											
										
											
											
											foreach ($result as $page_like) {
											?>
												<td>
													<a href="load_check.php?page_id=<?php echo $page_like['page_id']?>">
														<img src="https://graph.facebook.com/<?php echo $page_like['page_id'] ?>/picture?width=120&height=120"><br>
														
															<p><?=$page_like['name']?></p>
															<br>
														
													</a>
													<?php
														$i++;
														if ($i % 4 == 0)
															echo '</tr>';
													?>

												</td>
											<?php
											}
											?>
								
								
							</tr>
						</table>
                        	</div>
                        </div>
					</div>		
					<?php
					}
					catch (FacebookApiException $e) { 
					error_log($e); 
					$user = null; 
					
				  } 
				}
				else{ 
					
					$loginUrl = $facebook->getLoginUrl($param);
					exit("Loading ...<script>window.top.location.replace('$loginUrl');</script>");
					
				} 
	//}			
	/*else
	{
		header("Location: like.php");
	} */
?>
</div>
</body> 
</html>