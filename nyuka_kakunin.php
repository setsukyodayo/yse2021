<?php
/* 
【機能】
入荷で入力された個数を表示する。入荷を実行した場合は対象の書籍の在庫数に入荷数を加
えた数でデータベースの書籍の在庫数を更新する。

【エラー一覧（エラー表示：発生条件）】
なし
*/

//①セッションを開始する
session_start();
function getByid($id,$con){
	 //②書籍を取得するSQLを作成する実行する。
	 //その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
	 //SQLの実行結果を変数に保存する。
	 //③実行した結果から1レコード取得し、returnで値を返す。
	 $sql="SELECT * FROM books WHERE id ={$id}";
	return $con->query($sql)->fetch(PDO::FETCH_ASSOC);
}

function updateByid($id,$con,$total){
	/*
	 * ④書籍情報の在庫数を更新するSQLを実行する。
	 * 引数で受け取った$totalの値で在庫数を上書く。
	 * その際にWHERE句でメソッドの引数に$idに一致する書籍のみ取得する。
	 */
	$sql = "UPDATE books SET stock=$total WHERE id=$id";
		return $result = $con->query($sql);
}

//⑤SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if (empty($_SESSION['login'])||$_SESSION['login']==false){
	//⑥SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2']="ログインしてください。";
	//⑦ログイン画面へ遷移する。
	header("Location:login.php");
	exit;
	
}else if($_POST["books"]==null){
	header("Location:zaiko_ichiran.php");
	exit;
	
}

//⑧データベースへ接続し、接続情報を変数に保存する
//⑨データベースで使用する文字コードを「UTF8」にする
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
//⑩書籍数をカウントするための変数を宣言し、値を0で初期化する
$count=0;
//⑪POSTの「books」から値を取得し、変数に設定する。
foreach($_POST['books']as $book){
	/*
	 * ⑫POSTの「stock」について⑩の変数の値を使用して値を取り出す。
	 * 半角数字以外の文字が設定されていないかを「is_numeric」関数を使用して確認する。
	 * 半角数字以外の文字が入っていた場合はif文の中に入る。
	*/
	$num = $_POST['stock'][$count];
	if (!is_numeric($num)) {
		//⑬SESSIONの「error」に「数値以外が入力されています」と設定する。
		$_SESSION['error']="数値以外が入力されています";
		//⑭「include」を使用して「nyuka.php」を呼び出す。
		include "nyuka.php";
		//⑮「exit」関数で処理を終了する。
		exit();
	}
}

	//⑯「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に⑪の処理で取得した値と⑧のDBの接続情報を渡す。
	$book_data = getByid($book,$pdo);
	//⑰ ⑯で取得した書籍の情報の「stock」と、⑩の変数を元にPOSTの「stock」から値を取り出し、足した値を変数に保存する。
	$num_stook = $book_data['stock'];
	$total_count=$num_stook+$num;
	//⑱ ⑰の値が100を超えているか判定する。超えていた場合はif文の中に入る。
	if($total_count>100){
		//⑲SESSIONの「error」に「最大在庫数を超える数は入力できません」と設定する。
		$_SESSION['error']="最大在庫数を超える数は入力できません";
		//⑳「include」を使用して「nyuka.php」を呼び出す。
		include "nyuka.php";
		//㉑「exit」関数で処理を終了する。
		exit();
	}
	
	//㉒ ⑩で宣言した変数をインクリメントで値を1増やす。
	$count++;


/*
 * ㉓POSTでこの画面のボタンの「add」に値が入ってるか確認する。
 * 値が入っている場合は中身に「ok」が設定されていることを確認する。
 */
if(isset($_POST['add'])){
	//㉔書籍数をカウントするための変数を宣言し、値を0で初期化する。
	$count = 0;
	$result;
	//㉕POSTの「books」から値を取得し、変数に設定する。
	foreach($_POST["books"] as $book_id){
		//㉖「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉕の処理で取得した値と⑧のDBの接続情報を渡す。
		$book_data = getByid($book_id,$pdo);
		//㉗ ㉖で取得した書籍の情報の「stock」と、㉔の変数を元にPOSTの「stock」から値を取り出し、足した値を変数に保存する。
		$total_count = $book_data["stock"] + $_POST["stock"][$count];
		//㉘「updateByid」関数を呼び出す。その際に引数に㉕の処理で取得した値と⑧のDBの接続情報と㉗で計算した値を渡す。
		// updateByid($book_id,$pdo,$total);
		$result=updateByid($book_id,$pdo,$total_count);
		//㉙ ㉔で宣言した変数をインクリメントで値を1増やす。
		$count++;
	}


	//㉚SESSIONの「success」に「入荷が完了しました」と設定する。
	$_SESSION['success']="入荷が完了しました";
	unset($_SESSION['error']);
	//㉛「header」関数を使用して在庫一覧画面へ遷移する。
	header('Location:zaiko_ichiran.php');
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
						foreach($_POST["books"] as $book){
						//㉞「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉜の処理で取得した値と⑧のDBの接続情報を渡す。
						$book_data = getByid($book,$pdo);
						?>
						<tr>
							<td><?php echo	$book_data["title"];?></td>
							<td><?php echo	$book_data["stock"];?></td>
							<td><?php echo	$_POST["stock"][$count];?></td>
						</tr>
						<input type="hidden" name="books[]" value="<?php echo $book  /* ㊲ ㉝で取得した値を設定する */; ?>">
						<input type="hidden" name="stock[]" value='<?php echo $_POST['stock'][$count] /* ㊳POSTの「stock」に設定されている値を㉜の変数を使用して設定する。 */;?>'>
						<?php
							//㊴ ㉜で宣言した変数をインクリメントで値を1増やす。
							$count++;
						}
						?>
					</tbody>
				</table>
				<div id="kakunin">
					<p>
						上記の書籍を入荷します。<br>
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
