<?php
 require 'connect_db.php';
 require 'util.php';
var_dump($_POST);
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
     if (!isset($_GET["mode"])) {
           $mode = 'plain';
         } else if ($_GET["mode"] == 'index') {
           $mode = 'index';
         } else if ($_GET["mode"] == 'plain') {
           $mode = 'plain';
         } else {
           die("modeが変です。");
         }
     if (isset($_GET["thread"])) {
           $thread = $_GET["thread"];
           if (!ctype_digit($thread)) {
                 die("threadが変です。");
               }
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
     $defaultrange=$row["defaultrange"];
     $defaultrangeindex=$row["defaultrangeindex"];
     
     if (!isset($thread)) {
        # 通常表示ではfromを使う
           if (isset($_GET["from"])) {
                 $from = $_GET["from"];
                 if (!ctype_digit($from)) {
                       die("fromが変です。");
                     }
               }
         } else {
           # スレッド一覧表示ではoffsetを使う
           if (isset($_GET["offset"])) {
                 $offset = $_GET["offset"];
                 if (!ctype_digit($offset)) {
                       die("offsetが変です。");
                     }
               }
     }
     if (!isset($_GET["range"])) {
           if ($mode == 'plain') {
                 $range = $defaultrange;
               } else if ($mode == 'index') {
                 $range = $defaultrangeindex;
               }
         } else {
           $range = $_GET["range"];
           if (!is_positive_number($range)) {
                 die("rangeが変です。");
               }
         }
     $sql_str = sprintf("select * from message where boardid='%s' ",
                                               $board_id);
     if (isset($from)) {
           $sql_str .= sprintf("and serialid <= %d ", $from);
         }
     if (!isset($thread)) {
           $sql_str .= sprintf("order by serialid desc limit %d", $range);
         } else {
           $sql_str .= sprintf(" and top = %d order by serialid ", $thread);
           if (isset($offset)) {
                 $sql_str .= sprintf("limit %d, %d", $offset, $range);
               } else {
                 $sql_str .= sprintf("limit 0, %d", $range);
               }
     }
     $result = mysql_query($sql_str) or die('SQLエラー'.$sql_str);
     ?>
            <TITLE>
                <?=$board_name?>　一覧表示</TITLE>
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
        <a href="./main.php">メインページへ</a>] [
        <a href="./bordlist.php">掲示板一覧画面へ</a>] [
        <a href="./list.php?boardid=<?=$board_id?>">日付順表示</a>] [
        <a href="./list.php?boardid=<?=$board_id?>&amp;mode=index">日付順インデックス</a>] [
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

        <?php
     if ($mode == 'index') {
           echo "<table>";
         }
     ?>
            <?php
     while ($row = mysql_fetch_assoc($result)) {
           $serialid=$row["serialid"];
           $subject = htmlspecialchars($row["subject"]);
           $name = htmlspecialchars($row["name"]);
           $url = htmlspecialchars($row["url"]);
           $message = htmlspecialchars($row["message"]);
           $top = $row["top"];
           $date = format_date($row["posteddate"]);
         
           if (!isset($firstid)) {
             $firstid = $serialid;
           }
           $lastid = $serialid;
         
           if ($mode == 'plain') {
             include 'plain.php';
           } else if ($mode == 'index') {
             include 'index.php';
           }
         }
     if ($mode == 'index') {
           echo "</table>";
         }
     if (!isset($firstid)) {
           die("該当するレスなし。");
         }
     ?>
                <hr>
                <div align="center">
                    <?php
     $sql_str = sprintf("select min(serialid), max(serialid) ". "from message where boardid='%s' ",$board_id);
                    
     if (isset($thread)) {
           $sql_str .= sprintf("and top = %d", $thread);
         }
     $result = mysql_query_or_die($sql_str);
     $row = mysql_fetch_row($result);
     $db_min = $row[0];
     $db_max = $row[1];
     
     if (!isset($thread)) {
           # 通常表示
           if ($db_max > $firstid) {
                 $prevlink = sprintf("./list.php?boardid=%s&amp;from=%d&amp;range=%d&amp;mode=%s",
                                                                  $board_id, $firstid + $range, $range, $mode);
                 $prevmessage = "より新しい投稿";
               }
         } else {
           # スレッド一覧
           if (isset($offset) && $offset > 0) {
                 $prevlink = sprintf("./list.php?boardid=%s&amp;range=%d&amp;mode=%s"
                                                                 . "&amp;thread=%d",
                                                                  $board_id, $range, $mode, $thread);
                 $new_offset = $offset - $range;
                 if ($new_offset > 0) {
                       $prevlink .= sprintf("&amp;offset=%d", $new_offset);
                     }
                 $prevmessage = "より古い投稿";
               }
         }
     if (isset($prevlink)) {
         ?>
                        [
                        <a href="<?=$prevlink?>">
                            <?=$prevmessage?>
                        </a>]
                        <?php
             }
     if (!isset($thread)) {
           # 通常表示
           if ($db_min < $lastid) {
                 $nextlink = sprintf("./list.php?boardid=%s&amp;from=%d&amp;range=%d&amp;mode=%s",
                                     $board_id, $lastid - 1, $range, $mode);
                 $nextmessage = "より古い投稿";
               }
         } else {
           # スレッド一覧
           if ($db_max > $lastid) {
                 if (isset($offset)) {
                       $new_offset = $offset + $range;
                     } else {
                       $new_offset = $range;
                     }
                 $nextlink = sprintf("./list.php?boardid=%s&amp;range=%d&amp;mode=%s"
                                                                  . "&amp;thread=%d&amp;offset=%d",
                                                                  $board_id, $range, $mode, $thread, $new_offset);
                 $nextmessage = "より新しい投稿";
              }
         }
     if (isset($nextlink)) {
         ?>
                            [
                            <a href="<?=$nextlink?>">
                                <?=$nextmessage?>
                            </a>]
                            <?php
             }
     ?>
                </div>
    </body>

    </html>