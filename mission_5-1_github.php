<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>mission_5-1</title>
  <style>
    body{
      margin:30px;
    }
  </style>
</head>
<body>
  <form method="POST" action="">
    <input type="text" name="name" placeholder="名前">
    <input type="text" name="comment" placeholder="コメント">
    <input type="text" name="pass" placeholder="パスワード">
    <input type="submit" name="send" value="送信"><br>
    <input type="number" name="e_num" placeholder="編集投稿番号">
    <input type="submit" name="edit" value="編集"><br>
    <input type="number" name="d_num" placeholder="削除投稿番号">
    <input type="submit" name="delete" value="削除">
  </form>

<?php
  //$filename="mission_5-1.txt";

  //sql-------------------------------------------------------------------------------------
  //database setting
  $dsn = 'mysql:dbname='detabase-name';host=localhost';//database name
  $user = 'user-name';//user name
  $password = 'password';//pass
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  //echo "set ok <br>";

  //create table
  $sql = "CREATE TABLE IF NOT EXISTS tbtest5_1"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"//auto numbering
  . "name char(32),"//name
  . "comment TEXT,"//comment
  . "ymdhis DATETIME,"//comment
  . "pass TEXT"//comment
  .");";
  $stmt = $pdo->query($sql);
  //echo "create table <br>";
  //sql-------------------------------------------------------------------------------------

  //$sql ='SHOW TABLES';
  //$result = $pdo -> query($sql);
  //foreach ($result as $row){
  //echo $row[0];
  //echo '<br>';
  //}
  //echo "<hr>";

  //$sql ='SHOW CREATE TABLE tbtest5_1';
	//$result = $pdo -> query($sql);
	//foreach ($result as $row){
	//	echo $row[1];
	//}
	//echo "<hr>";

  //push send-button
  if(isset($_POST["send"])||isset($_POST["edit"])){
    if($_POST["e_num"]==NULL){
      if($_POST["name"]!=NULL && $_POST["comment"]!=NULL){

        $ymdhis=date("Y/m/d H:i:s");
        //echo "send = ok <br>";

        //sql-------------------------------------------------------------------------------------
        //input contents
        $sql = $pdo -> prepare("INSERT INTO tbtest5_1 (name,comment,ymdhis,pass) VALUES (:name,:comment,:ymdhis,:pass)");//prepare name&comment
        $sql -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);//bind parameter -> $name to 'name'
        $sql -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);//bind parameter -> $comment to 'comment'
        $sql -> bindParam(':ymdhis', $ymdhis, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $_POST["pass"], PDO::PARAM_STR);

        $sql -> execute();//execution SQL
        //echo "send_input ok<br>";
        //sql-------------------------------------------------------------------------------------
      }
    }elseif($_POST["e_num"]!=NULL&&$_POST["pass"]!=NULL){

      $id=(int)$_POST["e_num"];
      //$id=12;
      $ymdhis=date("Y/m/d H:i:s");
      //echo gettype($ymdhis)." ymdhis type<br>";
      //echo "edit = ok <br>";
      //echo gettype($id)."<br>";
      //echo "e_num : $id<br>";

      //edit contents
      $sql = "UPDATE tbtest5_1 SET name=:name,comment=:comment,ymdhis=:ymdhis WHERE id=:id AND pass=:pass";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name',$_POST["name"], PDO::PARAM_STR);
      $stmt->bindParam(':comment',$_POST["comment"], PDO::PARAM_STR);
      $stmt->bindParam(':ymdhis',$ymdhis, PDO::PARAM_STR);
      $stmt->bindParam(':pass',$_POST["pass"] , PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      //echo "edit input ok <br>";

    }
  }elseif(isset($_POST["delete"])){
    if($_POST["d_num"]!=NULL&&$_POST["pass"]!=NULL){
    //echo "push delete";
      //$pass=$_POST["pass"];
      //$d_num=$_POST["d_num"];
      //echo "delete = ok <br>";

      //delite contents
        $id = (int)$_POST["d_num"];
        //echo gettype($id)."<br>";
        //echo "d_num : $id<br>";
        $sql = "DELETE FROM tbtest5_1 where id=:id AND pass=:pass";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $_POST["pass"], PDO::PARAM_STR);
        $stmt->execute();
        //echo "delete ok <br>";

        	//delite contents
    }
  }

  //output contents
  for($id=1;$id<100;$id++){
      $sql = 'SELECT * FROM tbtest5_1 WHERE id=:id';//:idを経由させる.直接$idを代入できるが危険.
      $stmt = $pdo->prepare($sql);// prepare SQL
      $stmt->bindParam(':id', $id, PDO::PARAM_INT); //bind parameter id
      $stmt->execute();// execution SQL
      $results = $stmt->fetchAll(); //return all lines
    foreach ($results as $row){
      //$rowの中にはテーブルのカラム名が入る
      echo $row['id'].', ';
      echo $row['name'].', ';
      echo $row['comment'].', ';
      echo $row['ymdhis'].'<br>';
      echo "<hr>";
    }

  }
  //echo "output ok <br>";

?>

</body>
</html>
