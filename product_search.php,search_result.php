<?php
//セッションを開始する
session_start();
//SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if (empty($_SESSION['login'])){
//SESSIONの「error2」に「ログインしてください」と設定する。
$_SESSION['error2']='ログインして下さい。';
//ログイン画面へ遷移する。
header('Location: login.php');
}

//⑤データベースへ接続し、接続情報を変数に保存する
//⑥データベースで使用する文字コードを「UTF8」にする
$db_name='zaiko2021_yse';
$db_host='localhost';
$db_port='3306';
$db_password='2021zaiko';
$db_user='zaiko2021_yse';
$dsn="mysql:dbname={$db_name};host={$db_host};charset=utf8;port{$db_port}";
try{
	$pdo=new PDO($dsn,$db_user,$db_password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
}catch (PDOException $e){
	echo "接続失敗:". $e->getMessage();
	exit;
}
function maxId($pdo){
$sql="SELECT MAX(id) AS id FROM books";
$row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
return $row['id'];
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>商品検索</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>商品検索</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
			</ul>
		</nav>
	</div>

	<form action="nyuka_kakunin.php" method="post">
		<div id="pagebody">
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="keyword">キーワード</th>
							<th id="salesDate">発売年代</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
						</tr>
					</thead>
				
					
					<tr>
                        <td><input type='text' name='keyword[]' size='1' maxlength='11' required></td>
                        <td>     <select id="age" name="age">
                            <?php $my_array = array("","1970年代", "1980年代", "1990年代", "2000年代", "2010年代", "2020年代");?>
                            <?php foreach($my_array as $index => $value){?>
                                    <option value="1"><?php echo $value;?></option>
                                    <?php }?>
                        </select></td>
                        <td>
                        <select id="age" name="age">
                            <?php $my_array = array("","400円代", "500円代", "600円代", "700円代", "800円代", "900円代", "1000円代", "2000円代");?>
                            <?php foreach($my_array as $index => $value){?>
                                    <option value="1"><?php echo $value;?></option>
                                    <?php }?>
                        </select>
                        </td>
                        <td> <select id="age" name="age">
                            <?php $my_array = array("","10冊未満", "20冊未満", "30冊未満", "40冊未満", "50冊未満", "50冊以上");?>
                            <?php foreach($my_array as $index => $value){?>
                                    <option value="1"><?php echo $value;?></option>
                                    <?php }?>
                        </select></td>
					</tr>

					<?php
					?>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>