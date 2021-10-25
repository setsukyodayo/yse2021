









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
			<!-- エラーメッセージ -->
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
						// foreach($_POST['books'] as $book_id)//⑮POSTの「books」から一つずつ値を取り出し、変数に保存する。
						// {	
						// 	$book=getId($book_id,$pdo);// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。
						// //⑰ ⑯の戻り値からidを取り出し、設定する・//
    					
					?>
					
					<input type="hidden" value="<?php echo $book['id'];?>" name="books[]">
					<tr>
						<!-- <td><?php echo	$book["id"];?></td> -->
						<!-- <td><?php echo	$book["title"];?></td>
						<td><?php echo	$book["author"];?></td>
						<td><?php echo	$book["salesDate"];?></td>
						<td><?php echo	$book["price"];?></td>
						<td><?php echo	$book["stock"];?></td>  -->
                        <td><input type='text' name='title[]' size='1' maxlength='11' required></td>
                        <td><input type='text' name='author[]' size='2' maxlength='11' required></td>
                        <td><input type='text' name='salesDate[]' size='3' maxlength='11' required></td>
                        <td><input type='text' name='price[]' size='4' maxlength='11' required></td>
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