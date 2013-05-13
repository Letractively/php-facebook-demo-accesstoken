<html>
    <meta charset="utf-8">

	<a href="index.php">
        <p>
            <b>
                <i>
                    <font size="5" face="Times New Roman" color="#FF0000">
                    Trang chủ
                    </font>
                </i>
            </b>
        </p>
    </a>
    <?php
		require_once 'config.php';
		
    ?>

    
    <table>
        
        <tr>
            <?php
			
			$temp = $_GET['page_id'];
			$fql = "SELECT name FROM page WHERE page_id=".$temp;
			$name = $facebook->api(array(
                'method' => 'fql.query',
                'query' => $fql,
                    ));
			?>		
			<h1><p> List Friends likes this page : <?= $name[0]["name"]?> </p></h1>		
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
				
                    <a href="http://www.facebook.com/<?php echo $page_like['username']?>">
                        <img src="https://graph.facebook.com/<?php echo $page_like['uid'] ?>/picture?type=square">
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
    </table>
</html>
