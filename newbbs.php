<?php
require 'connect_db.php';
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="STYLESHEET" TITLE="default" TYPE="text/css" href="./admin.css">
        <title>新しい掲示板の作成</title>
    </head>

    <body>
        <?php
if ($_POST["boardname"] == ""
               || $_POST["boardid"] == ""
              || $_POST["defaultrange"] == ""
              || $_POST["defaultrangeindex"] == ""
            || $_POST["defaultrangethread"] == ""
              || $_POST["homepage"] == ""
             || $_POST["css"] == ""
            || $_POST["showmessagephp"] == "" ) {
        die("全ての項目を入力してください");
      }
  $sql_str = "insert into board (name, boardid, defaultrange, defaultrangeindex, "
                   . "defaultrangethread, "
                   . "homepage, css, showmessagephp) ";
        //sprintfは引数の変換。%sがストリング型、%dがint型
   $sql_str .= sprintf("values ('%s', '%s', %d, %d, %d, '%s', '%s', '%s')",
                                         $_POST["boardname"],
                                             $_POST["boardid"],
                                            $_POST["defaultrange"],
                                             $_POST["defaultrangeindex"],
                                            $_POST["defaultrangethread"],
                                              $_POST["homepage"],
                                             $_POST["css"],
                                             $_POST["showmessagephp"]);
    mysql_query($sql_str,$dbh)
        or die('SQLエラー..'.mysql_error());
   ?>
            <p>
                掲示板「
                <?= htmlspecialchars(stripslashes($_POST["boardname"]))?>
                    」の作成が終了しました。
            </p>
    </body>

    </html>