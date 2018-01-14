<DIV class="res" style="background-color:white;">
    <DIV style="margin: 10px 10px 10px 10px;">
        <BR>
        <DIV style="line-height:0%;">
            [
            <?=$serialid?>]
                <STRONG><font size="4">
         <?= $subject ?>
       </font></STRONG>
                <DIV align="right">
                    <a href="./form.php?boardid=<?=$board_id?>&amp;parent=<?=$serialid?>">返信</a></DIV>
        </DIV>
        <BR>
        <BR>
        <DIV style="line-height:0%;">
            投稿者：
            <?= $name ?>
                <DIV align="right">
                    <?=$date?>
                </DIV>
        </DIV>
        <BR> Link:
        <a href="<?=$url?>">
            <?=$url?>
        </a>
        <HR>
        <PRE>
     <?php
     $message2 = convert_message($message);
     ?>
     <?=$message2?>  
     </PRE>
    </DIV>
</DIV>
<div align="center">
    [<a href="./thread.php?boardid=<?=$board_id?>&amp;from=<?=$top?>&amp;range=1">
     このレスを含むスレッドを表示</a>]
</div>
<br>
<br>