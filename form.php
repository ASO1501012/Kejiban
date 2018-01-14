<?php
 require 'connect_db.php';
 require 'util.php';
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
     $board_name = get_board_name($board_id);
     
     if (isset($_GET["parent"])) {
           $parent = $_GET["parent"];
           if (!ctype_digit($parent)) {
                 die("誰にレスするつもりなんだか");
               }
           $sql_str = sprintf("select * from message where boardid='%s' and serialid=%d",$board_id, $parent);
           $result = mysql_query_or_die($sql_str);
           if (mysql_num_rows($result) != 1) {
                 die("誰にレスしてるつもりなんだろう…");
               }
         $row = mysql_fetch_assoc($result);
         $parent_subject=$row["subject"];
         $parent_message=$row["message"];
         }
     ?>
            <TITLE>
                <?=$board_name?>　投稿フォーム</TITLE>
            <script language="JavaScript">
                <!--
                function get_cookie(key) {
                    var i, index, splitted;
                    var sstr = key + "=";
                    var sstrlen = sstr.length;
                    splitted = document.cookie.split("; ");

                    for (i = 0; i < splitted.length; i++) {
                        if (splitted[i].substring(0, sstrlen) == sstr) {
                            return unescape(splitted[i].substring(sstrlen));
                        }
                    }
                    return "";
                }

                function set_cookie(key, val) {
                    document.cookie =
                        key + "=" + escape(val) + "; expires=Wed, 01-Jan-2031 00:00:00 GMT;";
                }

                function set_cookies() {
                    set_cookie("name", document.mainForm.name.value);
                    set_cookie("url", document.mainForm.url.value);
                    set_cookie("password", document.mainForm.password.value);
                }
                //-->
            </script>
    </head>

    <body>
        <table>
            <tr>
                <td align="center">
                    <font size="6" color="#0000ff"><?=$board_name?>　投稿フォーム</font>
                    <br>
                    <hr>
                    <CENTER>
                        <FONT color="red" size="4">注意!!</FONT>
                        <BR> この掲示板では、手で改行を入れない限り改行されません。
                        <BR>
                        <SMALL>(ソースを貼るときのためにPREで囲むため)</SMALL>
                        <BR> 適当な場所で改行を入れてください。
                        <BR>
                        <a href="http://kmaebashi.com/bbshelp.html">ヘルプ</a>
                    </CENTER>
                    <form name="mainForm" action="preview.php" method="post">
                        <input type="hidden" name="boardid" value="<?=htmlspecialchars($board_id)?>">
                        <?php
       if (isset($parent)) {
         ?>
                            <input type="hidden" name="parent" value="<?=$parent?>">
                            <?php
               }
     ?>
                                <table border="1">
                                    <tr>
                                        <td>ハンドル名</td>
                                        <td>
                                            <input type="text" name="name" size="40">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>件名</td>
                                        <td>
                                            <input type="text" name="subject" 
                                            <?php 
                                                   if (isset($parent)) {
                                                       if (ereg( '^Re:.*$', $parent_subject)) {
                                                           $new_subject=$parent_subject;
                                                       } else { 
                                                           $new_subject="Re:" . $parent_subject;
                                                       } 
                                                   ?> 
                                                   value="<?=$new_subject?>"
                                            <?php
                                                   }
                                            ?>
                                                    size="40">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Link</td>
                                        <td>
                                            <input type="text" name="url" size="40">
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td>
                                            <textarea name="message" cols="80" rows="20">
                                                <?php
       if (isset($parent)) {
         ?>
                                                    <?=get_parent_message($parent_message)?>
                                                        <?php
               }
     ?>
                                            </textarea>
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td>削除パスワード :
                                            <input type="text" name="password" size="12">
                                        </td>
                                        <td width="30"></td>
                                        <td>
                                            <input type="submit" value="送信" onClick="set_cookies();"> クリック！
                                        </td>
                                    </tr>
                                </table>
                    </form>
                </td>
            </tr>
        </table>
        </div>
        <script language="JavaScript">
            <!--
            document.mainForm.name.value = get_cookie("name");
            document.mainForm.url.value = get_cookie("url");
            document.mainForm.password.value = get_cookie("password");
            //-->
        </script>

    </body>

    </html>