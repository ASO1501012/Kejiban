<?php
 require 'connect_db.php';
 require 'util.php';
 
 function show_child($board_id, $parent) {
       $sql_str = sprintf("select * from message where boardid='%s'"
                                                  . "and parent = %d",
                                                  $board_id, $parent);
       $result = mysql_query($sql_str) or die('SQLエラー'.$sql_str);
       echo '<ul>';
       while ($row = mysql_fetch_assoc($result)) {
             echo '<li>';
             include 'threaditem.php';
             show_child($board_id, $row["serialid"]);
           }
       echo '</ul>';
     }
 
 
 ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html lang="ja-JP">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="STYLESHEET" TITLE="default" TYPE="text/css" href="./bbs.css">
        <?php
     if (!isset($_GET["boardid"])) {
           die('URLが変です。');
         }
     $board_id=$_GET["boardid"];
     $sql_str = sprintf("select * from board where boardid='%s'",
                                               $board_id);
    $result = mysql_query($sql_str) or die('SQLエラー'.$sql_str);
     if (mysql_num_rows($result) != 1) {
           die("掲示板のIDが変です。");
         }
     $row = mysql_fetch_assoc($result);
     $board_name=$row["name"];
     $homepage=$row["homepage"];
     $defaultrangethread=$row["defaultrangethread"];
     if (!isset($_GET["from"])) {
           $fromfirst=true;
         } else {
           $from = $_GET["from"];
           if (!ctype_digit($from)) {
                 die("fromが変です。");
               }
           $fromfirst=false;
         }
     if (!isset($_GET["range"])) {
           $range = $defaultrangethread;
         } else {
           $range = $_GET["range"];
           if (!is_positive_number($range)) {
                 die("rangeが変です。");
               }
         }
     $sql_str = sprintf("select * from message where boardid='%s' and parent is null ",
                                               $board_id);
     if (!$fromfirst) {
           $sql_str .= sprintf("and serialid <= %d ", $from);
         }
     $sql_str .= sprintf("order by serialid desc limit 0, %d",
                                                 $range);
     $result = mysql_query($sql_str) or die('SQLエラー'.$sql_str);
     ?>
            <TITLE>
                <?=$board_name?>　スレッド表示</TITLE>
    </head>

    <body>
        <div style="
            text-align:center;
            color: white;
            font-size: large;
            background: #0000ff;
            padding-top: 2px;
            border-top: #ccccff 2px solid;
            border-bottom: #000099 2px solid;
            padding-bottom: 2px;
            padding-left: 1em;
            border-left: #ccccff 2px solid;
            border-right: #000099 2px solid;
            padding-right: 2px;">
            <?=$board_name?>
        </div>
        <BR>
        <BR> [
        <a href="./list.php?boardid=<?=$board_id?>">日付順表示</a>] [
        <a href="./list.php?boardid=<?=$board_id?>&mode=index">日付順インデックス</a>] [
        <a href="./thread.php?boardid=<?=$board_id?>">スレッド順インデックス</a>]
        <br>
        <br>
        <hr>
        <center>
            <a href="./form.php?boardid=<?=$board_id?>">新規投稿</a> |
            <a href="<?=$homepage?>">開設者ホームページへ戻る</a> |
            <a href="http://kmaebashi.com/bbshelp.html">ヘルプ</a>
        </center>
        <hr>
        <ul>
            <?php
     while ($row = mysql_fetch_assoc($result)) {
           if (!isset($firstid)) {
                 $firstid = $row["serialid"];
               }
           $lastid = $row["serialid"];
         ?>
                <li>
                    <a href="./list.php?boardid=<?=$board_id?>&thread=<?=$row[" serialid "]?>">
     ▼</a>
                    <?php
               include 'threaditem.php';
           show_child($board_id, $row["serialid"]);
         ?>
                </li>
                <?php
         }
     ?>
        </ul>
        <hr>
        <div align="center">
            <?php
     $sql_str = sprintf("select max(serialid) from message where boardid='%s'"
                                                . "and parent is null",
                                                $board_id);
     $result = mysql_query($sql_str) or die('SQLエラー'.$sql_str);
     $row = mysql_fetch_row($result);
     $max = $row[0];
     if ($max > $firstid) {
           $prevlink = sprintf("./thread.php?boardid=%s&from=%d&range=%d",
                                                          $board_id, $from + $range, $range);
         ?>
                [<a href="<?=$prevlink?>">
     より新しい投稿</a>]
                <?php
         }
     if ($lastid > 0) {
           $nextlink = sprintf("./thread.php?boardid=%s&from=%d&range=%d",
                               $board_id, $lastid-1, $range);
        ?>
                    [<a href="<?=$nextlink?>">
     より古い投稿</a>]
                    <?php
             }
     ?>
    </body>