<?php
    require 'connect_db.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="STYLESHEET" TITLE="default" TYPE="text/css" href="./admin.css">
        <title>掲示板一覧画面</title>
    </head>
    <body>
    <?php
        $sql_str = sprintf("select * from board");
        $result = mysql_query($sql_str) or die('SQLエラー'.$sql_str);
        while($row = mysql_fetch_assoc($result)){
            $board_id=$row["boardid"];
            echo '<a href="./list.php?boardid=' .$board_id. '">'.$row["name"].'</a>'.nl2br("\n");
        }
    ?>
    </body>
</html>