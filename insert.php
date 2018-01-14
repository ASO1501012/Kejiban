<?php
 require 'connect_db.php';
 require 'util.php';
 ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html lang="ja-JP">

    <head>
        <link rel="STYLESHEET" TITLE="default" TYPE="text/css" href="./bbs.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php
     if (!isset($_POST["boardid"])) {
           die('掲示板のIDが変です。');
         }
     $board_id=$_POST["boardid"];
     $board_name = get_board_name($board_id);
     ?>
            <TITLE>
                <?=$board_name?> 投稿</TITLE>
    </head>

    <body>
        <?php
     if ($_POST["name"]=="") {
           die("名前の入力は必須です。");
         }
     if ($_POST["message"]=="") {
           die("内容がないよう");
         }
     $name=$_POST["name"];
     if ($_POST["subject"]=="") {
           $subject = "無題";
         } else {
           $subject = $_POST["subject"];
         }
     $url=$_POST["url"];
     $message=$_POST["message"];
     $password=$_POST["password"];
     if (isset($_POST["parent"])) {
           $parent = $_POST["parent"];
           if (!ctype_digit($parent)) {
                 die("誰にレスするつもりなんだか");
               }
          $sql_str = sprintf("select * from message where boardid='%s' and serialid=%d",
                                                       $board_id, $parent);
           $result = mysql_query_or_die($sql_str);
           if (mysql_num_rows($result) != 1) {
                 die("誰にレスしてるつもりなんだろう…");
               }
           $row = mysql_fetch_assoc($result);
           $top = $row["top"];
         } else {
           $parent = 'null';
         }
     
     mysql_query_or_die("lock tables message write");
     $sql_str = sprintf("select count(*)"
                                               . " from message where boardid='%s'",
                                               $board_id);
     $result = mysql_query_or_die($sql_str);
     $row = mysql_fetch_row($result);
     if ($row[0] == 0) {
           $serial_id = 0;
         } else {
           $serial_id = $row[0];
         }
     if (!isset($_POST["parent"])) {
           $top = $serial_id;
         }
     $remote_host = getenv("REMOTE_HOST");
     $ip_address = getenv("REMOTE_ADDR");
     if($remote_host == "" || $remote_host == $ip_address){
           $remote_host = gethostbyaddr($ip_address);
         }
     $user_agent = getenv("HTTP_USER_AGENT");
     
     $sql_str = "insert into message (";
     $sql_str .= "boardid, serialid, name, subject, url, message, password, "
                     . "parent, top, ipaddress, remotehost, useragent"
                         . ") values (";
     $sql_str .= sprintf("'%s', %d, '%s', '%s', '%s', "
                                                 . "'%s', '%s', %s, %s, "
                                                 . "'%s', '%s', '%s')",
                                                 $board_id, $serial_id, $name, $subject, $url,
                                                 $message, $password, $parent, $top,
                                                 $ip_address, $remote_host, $user_agent);
     mysql_query_or_die($sql_str);
     mysql_query_or_die("unlock tables");
     ?>
            <div align="center">
                <p>
                    投稿が成功しました。
                </p>
                <p>
                    <a href="./list.php?boardid=<?=$board_id?>">
     一覧表示に戻る</a>
            </div>
    </body>