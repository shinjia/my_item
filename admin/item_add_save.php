<?php
include '../common/config.php';
include '../common/utility.php';


// 接受外部表單傳入之變數
$itemcode = (isset($_POST['itemcode']))  ? $_POST['itemcode']  : '';
$membcode = (isset($_POST['membcode']))  ? $_POST['membcode']  : '';
$title = (isset($_POST['title']))  ? $_POST['title']  : '';
$category = (isset($_POST['category']))  ? $_POST['category']  : '';
$descr = (isset($_POST['descr']))  ? $_POST['descr']  : '';
$maintext = (isset($_POST['maintext']))  ? $_POST['maintext']  : '';
$author = (isset($_POST['author']))  ? $_POST['author']  : '';
$picture = (isset($_POST['picture']))  ? $_POST['picture']  : '';
$itemdate = (isset($_POST['itemdate']))  ? $_POST['itemdate']  : '';
$status = (isset($_POST['status']))  ? $_POST['status']  : '';
$remark = (isset($_POST['remark']))  ? $_POST['remark']  : '';


// 連接資料庫
$pdo = db_open();

// 寫出 SQL 語法
$sqlstr = "INSERT INTO item(itemcode, membcode, title, category, descr, maintext, author, picture, itemdate, status, remark) VALUES (:itemcode, :membcode, :title, :category, :descr, :maintext, :author, :picture, :itemdate, :status, :remark)";

$sth = $pdo->prepare($sqlstr);
$sth->bindParam(':itemcode', $itemcode, PDO::PARAM_STR);
$sth->bindParam(':membcode', $membcode, PDO::PARAM_STR);
$sth->bindParam(':title', $title, PDO::PARAM_STR);
$sth->bindParam(':category', $category, PDO::PARAM_STR);
$sth->bindParam(':descr', $descr, PDO::PARAM_STR);
$sth->bindParam(':maintext', $maintext, PDO::PARAM_STR);
$sth->bindParam(':author', $author, PDO::PARAM_STR);
$sth->bindParam(':picture', $picture, PDO::PARAM_STR);
$sth->bindParam(':itemdate', $itemdate, PDO::PARAM_STR);
$sth->bindParam(':status', $status, PDO::PARAM_STR);
$sth->bindParam(':remark', $remark, PDO::PARAM_STR);


// 執行SQL及處理結果
if($sth->execute())
{
   $new_uid = $pdo->lastInsertId();    // 傳回剛才新增記錄的 auto_increment 的欄位值
   $url_display = 'item_display.php?uid=' . $new_uid;
   header('Location: ' . $url_display);
}
else
{
   header('Location: error.php?type=add_save');
   echo print_r($pdo->errorInfo()) . '<br />' . $sqlstr; exit;  // 此列供開發時期偵錯用
}
?>