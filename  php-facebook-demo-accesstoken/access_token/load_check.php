<html>

    <meta charset="utf-8">
    <link href="Css/style.css" rel="stylesheet" type="text/css">
    

    <?php
    require_once 'config.php';
    $me = $facebook->api('/me');
    ?>


    <?php
    $temp = $_GET['page_id'];
    $fql = "SELECT name,page_url,fan_count,about FROM page WHERE page_id=" . $temp;
    $name = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
            ));
  //  print_r($name);
   // exit();
    ?>	
<body>
 
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=539935456045933";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> 
    <div class="header-Wrapper">
        <div class="header-imageLeft">
            <img src="https://graph.facebook.com/<?php echo $temp ?>/picture?width=200&height=200">
        </div>
        <div class="headerLoadContent">
            <a href="index.php">
                <p>
                    <b>
                        <i>
                            <font size="25" face="Times New Roman" color="#FF0000"> Trang chủ
                            </font>
                        </i>
                    </b></p>
            </a>
            <ul>

                <li>
                    <p>Name : <?= $name[0]["name"] ?></p>

                </li>
                <li>
                    <p>Liked : <?= $name[0]["fan_count"] ?> người thích trang này</p>

                </li>
                <li>
                    <p>About : <?= $name[0]["about"] ?></p>

                </li>
                                 
                    <div class="fb-follow" data-href="<?= $name[0]['page_url']?>"  data-show-faces="true" data-width="450"></div>
                 

            </ul>


        </div>	

    </div>
    <div class="middleWrapper">
        <div class="loadContent">
            <div class="contentMain">
                <div class="right-columnWidgetTitle">
                    <a>List friend joined : <?= $name[0]['name'] ?> page </a>
                </div>
                <table>

                    <tr> 
<?php
$i = 0;

$tete = "SELECT uid,name,username FROM user WHERE  uid IN
					(SELECT uid FROM page_fan WHERE page_id =$temp  AND uid IN (SELECT uid1 FROM friend WHERE  uid2=me()))";
$result = $facebook->api(array(
    'method' => 'fql.query',
    'query' => $tete,
        ));

foreach ($result as $page_like) {
    ?>


                            <td>

                                <a href="http://www.facebook.com/<?php echo $page_like['username'] ?>">
                                    <img src="https://graph.facebook.com/<?php echo $page_like['uid'] ?>/picture?width=120&height=120">
                                    <p><?= $page_like['name'] ?></p>
                                </a>
                            <?php
                            $i++;
                            if ($i % 6 == 0)
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
 </body>
</html>
