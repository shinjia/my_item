<?php
include '../common/config.php';
include '../common/utility.php';

// 接受外部表單傳入之變數
$uid = (isset($_POST['uid'])) ? $_POST['uid'] : 0;
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
$sqlstr = "UPDATE item SET itemcode=:itemcode, membcode=:membcode, title=:title, category=:category, descr=:descr, maintext=:maintext, author=:author, picture=:picture, itemdate=:itemdate, status=:status, remark=:remark WHERE uid=:uid " ;

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

$sth->bindParam(':uid', $uid, PDO::PARAM_INT);

// 執行SQL及處理結果
if($sth->execute())
{
   $url_display = 'item_display.php?uid=' . $uid;
   header('Location: ' . $url_display);
}
else
{
   header('Location: item_error.php?type=edit_save');
   echo print_r($pdo->errorInfo()) . '<br />' . $sqlstr;  // 此列供開發時期偵錯用
}
?>