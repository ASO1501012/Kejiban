<?php
	if(isset($_POST['userid']) && ($_POST['username']) && ($_POST['pass']) && ($_POST['mail']) && ($_POST['freetext'])){
		require_once 'connect_db.php';
        require_once 'util.php';
		registUser($_POST['userid'],$_POST['username'],$_POST['pass'],$_POST['mail'],$_POST['freetext']);
	}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>ユーザー登録画面</title>
</head>
<body>
	<form action="UserRegist.php" method="post">
	<div style="width:280px;margin-right:auto;margin-left:auto;padding:30px;border:solid 1px #000000;">
	<table>
		<tr><td style="width:80px;"><div style="text-align:right">ユーザID:</td><td><input type="text" name="userid" size="20"></td></tr>
        <tr><td style="width:80px;"><div style="text-align:right">ユーザーネーム:</td><td><input type="text" name="username" size="20"></td></tr>
		<tr><td style="width:80px;"><div style="text-align:right">パスワード:</td><td><input type="pass" name="pass" size="20"></td></tr>
		<tr><td style="width:80px;"><div style="text-align:right">メールアドレス:</td><td><input type="text" name="mail" size="20"></td></tr>
        <tr><td style="width:80px;"><div style="text-align:right">ご自由にお使いください。:</td><td><input type="text" name="freetext" size="20"></td></tr>
		<tr><td colspan=2><input type="submit" value="登録" style="background-color:#8181E4;color:#FFFFFF;width:260px;margin-top:50px;margin-left:auto;margin-right:auto;border:0;"></td></tr>
	</table>
	</div>
	</form>

</body>
</html>