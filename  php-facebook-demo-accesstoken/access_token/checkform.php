<html>
    <meta charset="utf-8">
    <link href="Css/style.css" rel="stylesheet" type="text/css">
	
    <?php
    require_once 'config.php';
	$me = $facebook->api('/me');
	$user = $facebook->getUser(); 
	$textform_check = $_POST['ten'];
    
    ?>  
     <a href="index.php">
                    <p>
                        <b>
                            <i>
                                <font size="25" face="Times New Roman" color="#FF0000"> Trang chủ
                                </font>
                            </i>
               	</b></p>
                </a>
    <div class="headerWrapper">
       	  <div class="headerMain">
            		<img src="https://graph.facebook.com/<?php echo $user ?>/picture?width=300&height=300"> 
                    <p> Wellcome :  <a href='<?= $me['link']?>'><?php echo $me['name']?></a></p>
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
                        <div class="right-columnWidgetTitle">
            	<a>List page was found by input : <?= $textform_check?> character </a>
           				 </div>
						<table>
							<tr>
								<?php
									
			
    $sql = "SELECT page_id,name,type
              FROM page 
              WHERE page_id 
              IN (
                    SELECT page_id 
                    FROM page_fan
                    WHERE uid = me()
                  ) AND strpos(lower(name),'$textform_check') >=0";

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
    
</html>
