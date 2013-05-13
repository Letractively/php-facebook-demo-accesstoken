<html>
    <meta charset="utf-8">

    <?php
    require_once 'config.php';
	$me = $facebook->api('/me');

    $textform_check = $_POST['ten'];
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
    ?>  
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
    <br>
    <h1>Kết quả tìm kiếm string : <?php echo $textform_check ?>  </h1

    <table>
        <tr>
            <?php
            $i = 0;
            foreach ($result as $page_like) {
                ?>
                <td>
                    <a href="load_check.php?page_id=<?php echo $page_like['page_id']?>">
                        <img src="https://graph.facebook.com/<?php echo $page_like['page_id'] ?>/picture?type=square">
                        <?php
                        echo $page_like['name'];
                        echo"<br>";
                        ?>
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
</html>
