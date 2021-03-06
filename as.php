
   
<?php
/* 
【機能】
出荷で入力された個数を表示する。出荷を実行した場合は対象の書籍の在庫数から出荷数を
引いた数でデータベースの書籍の在庫数を更新する。
【エラー一覧（エラー表示：発生条件）】
なし
*/

//①セッションを開始する
session_start();

function getByid($id,$con){
	/* 
	 * ②書籍を取得するSQLを作成する実行する。
	 * その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
	 * SQLの実行結果を変数に保存する。
	 */
$sql = "select * from books where books.id=$id ";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				return $row;
			}	
		}
	//③実行した結果から1レコード取得し、returnで値を返す。
}

function updateByid($id,$con,$total){
	/*
	 * ④書籍情報の在庫数を更新するSQLを実行する。
	 * 引数で受け取った$totalの値で在庫数を上書く。
	 * その際にWHERE句でメソッドの引数に$idに一致する書籍のみ取得する。
	 */
	$sql = "UPDATE books SET stock=$total WHERE id=$id";
		return $result = $con->query($sql);

		// if ($result->num_rows > 0) {
		// 	while($row = $result->fetch_assoc()) {
		// 		return $row;
		// 	}	
		// }
}

//⑤SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。

if ($_SESSION["login"] ==False){
	//④SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2'] ="ログインしてください";
	header("Location: login.php");//④ログイン画面へ遷移する。
	//⑤ログイン画面へ遷移する。
}

//⑧データベースへ接続し、接続情報を変数に保存する

//⑨データベースで使用する文字コードを「UTF8」にする
$con = mysqli_connect("localhost" , "zaiko2019" , "2019zaiko" , "zaiko2019_yse");
	mysqli_set_charset($con,"UTF8");
//⑩書籍数をカウントするための変数を宣言し、値を0で初期化する
//⑪POSTの「books」から値を取得し、変数に設定する。
	$count=0;

foreach($_POST['books'] as $books ){
	/*
	 * ⑫POSTの「stock」について⑩の変数の値を使用して値を取り出す。
	 * 半角数字以外の文字が設定されていないかを「is_numeric」関数を使用して確認する。
	 * 半角数字以外の文字が入っていた場合はif文の中に入る。
	 */
	// foreach ($tests as $element) { -----doan nay tham khao---
 //    if (is_numeric($element)) {
 //        echo var_export($element, true) . " is numeric", PHP_EOL;
 //    } else {
 //        echo var_export($element, true) . " is NOT numeric", PHP_EOL;
 //    } -----tham khao den day= yeu cau k bo comment------
	if (!is_numeric($_POST['stock'][$count])) {
		//⑬SESSIONの「error」に「数値以外が入力されています」と設定する。
		//.⑭「include」を使用して「syukkaphp」を呼び出す。
		//⑮「exit」関数で処理を終了する。
		$_SESSION['error']="数値以外が入力されています";
		include 'nyuka.php';
		exit();
}

	//⑯「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に⑪の処理で取得した値と⑧のDBの接続情報を渡す。
	$dtb=getByid($books,$con);
$total=$dtb['stock']+$_POST['stock'][$count];
	//⑰ ⑯で取得した書籍の情報の「stock」と、⑩の変数を元にPOSTの「stock」から値を取り出して書籍情報の「stock」から値を引いた値を変数に保存する。

	//⑱ ⑰の値が0未満か判定する。0未満の場合はif文の中に入る。
	if($total>100){
		//⑲SESSIONの「error」に「出荷する個数が在庫数を超えています」と設定する。
		//⑳「include」を使用して「syukka.php」を呼び出す。
		//㉑「exit」関数で処理を終了する。
		$_SESSION['error']="最大在庫数を超える数は入力できません";
		include 'nyuka.php';
		exit();
	}
	
	//㉒ ⑩で宣言した変数をインクリメントで値を1増やす。
	$count++;
}

/*
 * ㉓POSTでこの画面のボタンの「add」に値が入ってるか確認する。
 * 値が入っている場合は中身に「ok」が設定されていることを確認する。
 */
if(@$_POST['add']=="ok"/* ㉓の処理を書く */){
	//㉔書籍数をカウントするための変数を宣言し、値を0で初期化する。
$count=0;
$result;
	//㉕POSTの「books」から値を取得し、変数に設定する。

	foreach($_POST['books']as $books/* ㉕の処理を書く */){
		//㉖「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉕の処理で取得した値と⑧のDBの接続情報を渡す。
		//㉗ ㉖で取得した書籍の情報の「stock」と、㉔の変数を元にPOSTの「stock」から値を取り出して書籍情報の「stock」から値を引いた値を変数に保存する。
		//㉘「updateByid」関数を呼び出す。その際に引数に㉕の処理で取得した値と⑧のDBの接続情報と㉗で計算した値を渡す。
		//㉙ ㉔で宣言した変数をインクリメントで値を1増やす。
		$dtb=getByid($books,$con);
$total=$dtb['stock']+$_POST['stock'][$count];
$result=updateByid($books,$con,$total);
$count++;
	}

	//㉚SESSIONの「success」に「入荷が完了しました」と設定する。
	//㉛「header」関数を使用して在庫一覧画面へ遷移する。
	echo $result;

if($result){

	$_SESSION['success'] ="入荷が完了しました";
	header("Location: zaiko_ichiran.php");//④ログイン画面へ遷移する。
}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>入荷確認</title>
<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
<div id="header">
	<h1>入荷確認</h1>
</div>
<form action="nyuka_kakunin.php" method="post" id="test">
	<div id="pagebody">
		<div id="center">
			<table>
				<thead>
					<tr>
						<th id="book_name">書籍名</th>
						<th id="stock">在庫数</th>
						<th id="stock">入荷数</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					//㉜書籍数をカウントするための変数を宣言し、値を0で初期化する。
			$count=0;
					//㉝POSTの「books」から値を取得し、変数に設定する。
					// foreach(/* ㉝の処理を書く */){
						//㉞「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉜の処理で取得した値と⑧のDBの接続情報を渡す。
		foreach ($_POST['books'] as $books){
					$a =getbyId($books,$con);

					?>
					<tr>
						<td><?php echo	$a['title']/* ㉟ ㉞で取得した書籍情報からtitleを表示する。 */;?></td>
						<td><?php echo	$a['stock']/* ㊱ ㉞で取得した書籍情報からstockを表示する。 */;?></td>
						<td><?php echo	$_POST['stock'][$count]/* ㊲ POSTの「stock」に設定されている値を㉜の変数を使用して呼び出す。 */;?></td>
					</tr>
					<input type="hidden" name="books[]" value="<?php echo $books/* ㊳ ㉝で取得した値を設定する */;?>">
					<input type="hidden" name="stock[]" value='<?php echo $_POST['stock'][$count]/* ㊴「POSTの「stock」に設定されている値を㉜の変数を使用して設定する。 */;?>'>
					<?php
						//㊵ ㉜で宣言した変数をインクリメントで値を1増やす。
					$count++;
					}
					?>
				</tbody>
			</table>
			<div id="kakunin">
				<p>
					上記の書籍を出荷します。<br>
					よろしいですか？
				</p>
				<button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
				<button type="submit" id="message" formaction="nyuka.php">いいえ</button>
			</div>
		</div>
	</div>
</form>
<div id="footer">
	<footer>株式会社アクロイト</footer>
</div>
</body>
</html>
© 2021 GitHub, Inc.
Terms
Privacy
Security
Status
Docs
Contact GitHub
Pricing
API
Training
Blog
About
Loading complete