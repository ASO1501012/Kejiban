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
     $boardid=stripslashes($_POST["boardid"]);
     $board_name = get_board_name($boardid);
     ?>
            <TITLE>
                <?=$board_name?>　投稿</TITLE>
    </head>

    <body>
        <?php
     if ($_POST["name"]=="") {
           die("名前の入力は必須です。");
         }
     if ($_POST["message"]=="") {
           die("内容がないよう");
         }
     $name=stripslashes($_POST["name"]);
     if ($_POST["subject"]=="") {
           $subject = "無題";
         } else {
           $subject = stripslashes($_POST["subject"]);
     }
     $url=stripslashes($_POST["url"]);
     $message=stripslashes($_POST["message"]);
     $password=stripslashes($_POST["password"]);
     ?>
            <h1>投稿前のプレビュー</h1>
            <p>
                こんな感じで投稿されます。ちゃんと改行を入れたか等チェックしてください。
            </p>
            <p>
                「投稿」ボタンをクリックすると、投稿されます。
            </p>
            <DIV class="res" style="background-color:white;">
                <DIV style="margin: 10px 10px 10px 10px;">
                    <BR>
                    <DIV style="line-height:0%;">
                        [発言番号]
                        <STRONG><font size="4">
           <?= htmlspecialchars($subject) ?>
         </font></STRONG>
                        <DIV align="right"><font color="red"><u>返信</u></font></DIV>
                    </DIV>
                    <BR>
                    <BR>
                    <DIV style="line-height:0%;">
                        投稿者：
                        <?= htmlspecialchars($name) ?>
                            <DIV align="right">YYYY/MM/DD hh:mm:ss</DIV>
                    </DIV>
                    <BR> Link:
                    <a href="<?=htmlspecialchars($url)?>">
                        <?=htmlspecialchars($url)?>
                    </a>
                    <HR>
                    <PRE>
     <?php
     $message2 = htmlspecialchars($message);
     $message2 = convert_message($message2);
     ?>
     <?=$message2?>  
     </PRE>
                </DIV>
            </DIV>
            <form action="insert.php" method="post">
                <input type="hidden" name="name" value="<?=htmlspecialchars($name)?>">
                <input type="hidden" name="subject" value="<?=htmlspecialchars($subject)?>">
                <input type="hidden" name="url" value="<?=htmlspecialchars($url)?>">
                <input type="hidden" name="message" value="<?=htmlspecialchars($message)?>">
                <input type="hidden" name="password" value="<?=htmlspecialchars($password)?>">
                <input type="hidden" name="boardid" value="<?=htmlspecialchars($boardid)?>">
                <?php
    $parent = htmlspecialchars($_POST["parent"]);
       if (isset($_POST["parent"])) {
             if (!ctype_digit($_POST["parent"])) {
                   die("誰にレスするつもりなんだか");
                 }
         ?>
                    <input type="hidden" name="parent" value="<?=htmlspecialchars($parent)?>">
                    <?php
               }
     ?>
                        <div align="center">
                            <input type="submit" value="送信">
                        </div>
            </form>
    </body>