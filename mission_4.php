<!
>
<?php
//データベースへの接続

$dsn='mysql:dbname=tt_756_99sv_coco_com;host=localhost';
$user='tt-756.99sv-coco';
$password='B6cCgswZ';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_WARNING));


//テーブル作成

$sql = "CREATE TABLE IF NOT EXISTS mission_4"
." ("
."number INT,"
."date TEXT,"
."name char(32),"
."comment TEXT,"
."password TEXT"
.");";
$stmt = $pdo->query($sql);


//投稿作業

//insertを行なってデータ入力
if($_POST["namae"] != NULL && $_POST["comment"] != NULL 
        && $_POST["hidden"] == NULL && $_POST["firstpass"] != NULL)
{
 $num = 1;
 $date = date("Y m d H:i", time());
 $namae= $_POST["namae"];
 $name = $_POST["comment"];
 $pas = $_POST["firstpass"];
 
 $sql = 'SELECT * FROM mission_4';
 $stmt = $pdo->query($sql);
 $result = $stmt->fetchAll();
 foreach($result as $row)
 {
  ++$num;
 }
 
 $sql = $pdo->prepare("INSERT INTO mission_4 (number, date, name, comment, password) VALUES (:number, :date, :name, :comment,:password)");
 $sql->bindParam(':number', $num, PDO::PARAM_INT);
 $sql->bindParam(':date', $date, PDO::PARAM_STR);
 $sql->bindParam(':name', $namae, PDO::PARAM_STR);
 $sql->bindParam(':comment', $name, PDO::PARAM_STR);
 $sql->bindParam(':password', $pas, PDO::PARAM_STR);
 $sql->execute();
}

//編集選択

if ( $_POST["recomment_number"] != NULL && $_POST["secondpass"] != NULL)
{
 $num = $_POST["recomment_number"];
 $pas = $_POST["secondpass"];
 $comme = false;
 
 $sql = 'SELECT * FROM mission_4';
 $stmt = $pdo->query($sql);
 $result = $stmt->fetchAll();

 foreach($result as $row)
 {
     if ($row['number'] == $num && $row['password'] == $pas)
     {
       $recomment_number = $row['number'];
       $recomment_comment = $row['comment'];
       $recomment_name = $row['name'];
       $recomment_pas = $row['password'];
       $comme = true;
       
       break;
     }
 }
 
 if ($comme == false)
 {
  echo "番号が違うか、パスワードが違います。<br>";
 }
 
}

// 編集機能

if($_POST["hidden"] != NULL &&  $_POST["namae"] != NULL
         && $_POST["comment"] != NULL && $_POST["firstpass"] != NULL)
{
 $num = $_POST["hidden"];
 $namae = $_POST["namae"];
 $name = $_POST["comment"];
 $pas = $_POST["firstpass"];
 $date = date("Y m d H:i", time())." に編集されました。";
 
 $sql = 'update mission_4 set date=:date, name=:name, comment=:comment, password=:password where number=:number';
 $stmt = $pdo->prepare($sql);
 $stmt->bindParam(':date', $date, PDO::PARAM_INT);
 $stmt->bindParam(':name', $namae, PDO::PARAM_STR);
 $stmt->bindParam(':comment', $name, PDO::PARAM_STR);
 $stmt->bindParam(':password', $pas, PDO::PARAM_STR);
 $stmt->bindParam(':number', $num, PDO::PARAM_INT);
 $stmt->execute();
}

//deleteによる削除機能

if($_POST["saku"] != NULL && $_POST["thirdpass"] != NULL)
{
 $num = $_POST["saku"];
 $pas = $_POST["thirdpass"];
 $delee = false;
 
 $sql = 'SELECT * FROM mission_4';
 $stmt = $pdo->query($sql);
 $result = $stmt->fetchAll();

 foreach($result as $row)
 {
     if ($row['number'] == $num && $row['password'] == $pas)
     {
   $sakuzyo = "削除済みコメント";
   $sp = " ";
   $date = date("Y m d H:i", time())." に";
   
   $sql = 'update mission_4 set date = :date, name=:name, comment=:comment, password=:password where number=:number';
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':date', $date, PDO::PARAM_STR);
   $stmt->bindParam(':name', $sakuzyo, PDO::PARAM_STR);
   $stmt->bindParam(':comment', $sp, PDO::PARAM_STR);
   $stmt->bindParam(':password', $sp, PDO::PARAM_STR);
   $stmt->bindParam(':number', $row['number'], PDO::PARAM_INT);
   $stmt->execute();
   
   $delee = true;
   
       break;
     }
 }
 if ($delee == false)
 {
  echo "番号が違うか、パスワードが違います。<br>";  
 }

}
?>



<html>
   <head>
   <title> mission_4</title>
   <meta charset = "utf-8">
   </head>
   <body>
   <pre>
   <form action = "mission_4.php" method = "post">
   <input type = "hidden" name = "hidden" value = "<?php echo $recomment_number; ?>" >
      氏名：  <input type = "text" name = "namae" value = "<?php if($recomment_name != NULL) {echo $recomment_name;}?>" 
                                                                                                                                                        placeholder = "氏名">
  コメント：  <input type = "text" name = "comment" value = "<?php if($recomment_comment != NULL) {echo $recomment_comment;} ?>" 
                                                                                                                                                        placeholder = "コメント">
パスワード：  <input type = "password" name = "firstpass" value = "<?php if($recomment_pas != NULL){echo $recomment_pas;} ?>" 
                                                                                                                                                        placeholder = "パスワード" >
                            <input type = "submit" name = "bbbtn" value = "送信">
 <form action = "mission_4.php" method = "post">
編集対象番号：<input type = "text" name = "recomment_number" placeholder = "編集対象番号">
  パスワード：<input type = "password" name = "secondpass" placeholder = "パスワード" >
                        <input type = "submit" name = "bbtn" value = "編集">
 <form action = "mission_4.php" method = "post">
削除対象番号：<input type = "text" name = "saku" placeholder = "削除対象番号" >
  パスワード：<input type = "password" name = "thirdpass" placeholder = "パスワード" >
                            <input type = "submit" name = "btn" value = "削除" >
    </pre>
    </form>
<?php

$sql = 'SELECT * FROM mission_4';
$stmt = $pdo->query($sql);
$result = $stmt->fetchAll();
foreach($result as $row)
{
    echo $row['number']."<br>";
    echo $row['date']."<br>";
    echo $row['name']."<br>";
    echo $row['comment']."<br>";
    echo "<br>";
}


?>
   
   
   
   
   </body>
</html>