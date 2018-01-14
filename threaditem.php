<?php
$serialid=$row["serialid"];
 $subject = htmlspecialchars($row["subject"]);
 $name = htmlspecialchars($row["name"]);
 $date = format_date($row["posteddate"]);
 ?>
    [
    <?=$serialid?>]
        <a href="./list.php?boardid=<?=$board_id?>&from=<?=$serialid?>&range=1">
            <?=$subject?>
        </a>
        ｡｡
        <?=$name?>
            ｡｡
            <?=$date?>