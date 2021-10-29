<?php

if ((function_exists('session_status')
    && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    session_start();
}
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





function getByid($id,$con){
	 $sql="SELECT * FROM books WHERE id ={$id}";
	return $con->query($sql)->fetch(PDO::FETCH_ASSOC);
}

function deleteByid($id,$con)
{
    $sql = "UPDATE books SET is_delete = true WHERE :id =id";
    $stmt = $con->prepare($sql);
	$stmt->execute([":id" => $id]);
}



if(isset($_POST["add"]) && $_POST["add"]=="ok"){
	$books =0;
	foreach($_POST["books"] as $book){
		$book_data = getByid($book,$pdo);
		deleteByid($book,$pdo);
		$books++;
    
	}
    $_SESSION["success"] ="削除が完了しました";
	//在庫一覧画面へ遷移する。
	header("location:zaiko_ichiran.php");
	exit;
}
if(empty($_POST["books"])){
	
	$_SESSION["success"] ="削除する商品が選択されていません";
	
	header("Location:zaiko_ichiran.php");
	exit;
}
   
	


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
    <title>商品削除</title>
</head>

<body>
    <div id="header">
        <h1>商品削除</h1>
    </div>

    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="delete_product.php" method="post">
        <div id="pagebody">

            <div id="error">
                <?php
                
                if(isset($_SESSION["error"])){
                    //⑭SESSIONの「error」の中身を表示する。
                    echo '<p>'.$_SESSION["error"].'</p>';
                    $_SESSION["error"]="";
                }
                ?>
            </div>
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
                        </tr>
                    </thead>
                    <?php

                    foreach ($_POST['books'] as $book) {
                        $getId_id = getByid($book, $pdo);
                        
                        
                    ?>
                        <input type="hidden" value="<?php echo $getId_id['id']; ?>" name="id[]">
                
                        <tr>
                            <td><?php echo $getId_id["id"]; ?></td>
                            <td><?php echo $getId_id["title"]; ?></td>
                            <td><?php echo $getId_id["author"]; ?></td>
                            <td><?php echo $getId_id["salesDate"]; ?></td>
                            <td><?php echo $getId_id["price"]; ?></td>
                            <td><?php echo $getId_id["stock"]; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <div id="kakunin">
                    <p>
                        上記の書籍を削除します。<br>
                        よろしいですか？
                    </p>
                    <button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
                    <button type="submit" id="message" formaction="zaiko_ichiran.php">いいえ</button>
                </div>
            </div>
        </div>
    </form>
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>