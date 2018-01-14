<?php
 function format_date($src) {
       $ret = substr($src, 0, 4) . "/" . substr($src, 4, 2) . "/" . substr($src, 6, 2)
           . " " . substr($src, 8, 2) . ":" . substr($src, 10, 2) . ":"
           . substr($src, 12, 2);
       return $ret;
     }
 
 function is_positive_number($str) {
       if (!ctype_digit($str)) {
             return false;
           }
       if ($str <= 0) {
             return false;
           }
       return true;
     }
 
 function get_parent_message($src) {
       $dest=htmlspecialchars($src);
       $dest=ereg_replace('^', '&gt;' ,$dest);
       $dest=ereg_replace("\n", "\n>" ,$dest);
       return $dest;
     }

 function convert_message($src) {
       $dest = ereg_replace("http://[^<>[:space:]]+[[:alnum:]/]",
                            "<a href=\"\\0\">\\0</a>", $src);
     return $dest;
     }
 
function mysql_query_or_die($sql_str) {
       $result = mysql_query($sql_str) or die('SQLエラー'.$sql_str);
       return $result;
     }
 
 function get_board_name($board_id) {
       $sql_str = sprintf("select name from board where boardid='%s'",
                          $board_id);
       $result = mysql_query_or_die($sql_str);
       if (mysql_num_rows($result) != 1) {
             die("掲示板のIDが変です。");
           }
       $row = mysql_fetch_assoc($result);
       return $row["name"];
     }

    function registUser($userid,$pass,$username){
        $dbm = new DBManager();
        $listcnt = $dbm->getUserInfoTblByUserId($userid);
        $listlength = count($listcnt);
        if($listlength == 0){
            $hash = $this->passwordHash($pass);
            $dbm->insertuserInfo($userid,$hash,$username);
            header('Location: RegistComp.php');
        }else{
            echo "登録できませんでした。";
            header('Location: RegistMiss.php');
        }
    }

    function logincheck($userid,$pass){
        $dbm = new DBManager();
        $um = new UserManager();
        $listcnt = $dbm->getUserInfoTblByUserId($userid);
        $listlength = count($listcnt);
        foreach($listcnt as $list){
            $username = $list->username;
        }

        if($listlength >= 1){
            $passcheck = $this->passwordCheck($pass);
            if($passcheck == true){
                $_SESSION['userid'] = $userid;
                $_SESSION['username'] = $username;

                session_regenerate_id();
                header('Location:ToDoList.php');
            }else{
                //パスワードが不一致
                header('Location: Login.php');
            }
        }else{
            //ユーザーIDが不一致
            header('Location: Login.php');
        }
    }

 function passwordCheck($pass){
        $userhash = $this->passwordHash($pass);
        $flag = password_verify($pass,$userhash);
        return $flag;
    }

 function passwordHash($pass){
        $hash = password_hash($pass,PASSWORD_DEFAULT);
        return $hash;
    }
 ?>