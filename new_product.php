<?php
//セッションを開始する
session_start();
//SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if (empty($_SESSION['login'])){
//SESSIONの「error2」に「ログインしてください」と設定する。
$_SESSION['error2']=='ログインして下さい。';
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
    // $id2 = $pdo->lastInsertId('id');
}catch (PDOException $e){
	echo "接続失敗:". $e->getMessage();
	exit;
}
//書籍テーブルから書籍情報を取得するSQLを実行する。また実行結果を変数に保存する
// $sql="SELECT max(id) FROM books";
$sql="SELECT MAX(book_id)  FROM books";
$books = $pdo->prepare('SELECT * FROM books');
// $books2 = $pdo->prepare('SELECT  MAX(id) books');
$books->execute();
// SELECT  MAX(列名)  FROM  表名;

?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>新商品追加機能</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>新商品追加機能</h1>
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
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
							<th id="in">入荷数</th>
						</tr>
					</thead>
					<?php 
						// foreach($_POST['books'] as $book_id)	// {	
							// $book=getId($book_id,$pdo);
						//⑰ ⑯の戻り値からidを取り出し、設定する・//
    					
					?>
					
					<input type="hidden" value="<?php echo $book['id'];?>" name="books[]">
					<tr>
						<td><?php echo	$book["id"];?></td>
						<!-- <td><?php echo	$book["title"];?></td>
						<td><?php echo	$book["author"];?></td>
						<td><?php echo	$book["salesDate"];?></td>
						<td><?php echo	$book["price"];?></td>
						<td><?php echo	$book["stock"];?></td>  -->
                        <td><input type='text' name='title[]' size='1' maxlength='11' required></td>
                        <td><input type='text' name='author[]' size='2' maxlength='11' required></td>
                        <td><input type='text' name='salesDate[]' size='3' maxlength='11' required></td>
                        <td><input type='text' name='price[]' size='4' maxlength='11' required></td>
                        <td><input type='text' name='price[]' size='5' maxlength='11' required></td>
						<td><input type='text' name='stock[]' size='6' maxlength='11' required></td>
					</tr>

					<?php
					//  }
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