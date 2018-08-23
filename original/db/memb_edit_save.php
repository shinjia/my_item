<?php
include '../common/config.php';
include '../common/utility.php';

// 接受外部表單傳入之變數
$uid = (isset($_POST['uid'])) ? $_POST['uid'] : 0;
$membcode = (isset($_POST['membcode']))  ? $_POST['membcode']  : '';
$password = (isset($_POST['password']))  ? $_POST['password']  : '';
$membname = (isset($_POST['membname']))  ? $_POST['membname']  : '';
$nickname = (isset($_POST['nickname']))  ? $_POST['nickname']  : '';
$email = (isset($_POST['email']))  ? $_POST['email']  : '';
$level = (isset($_POST['level']))  ? $_POST['level']  : '';
$remark = (isset($_POST['remark']))  ? $_POST['remark']  : '';


// 連接資料庫
$pdo = db_open(); 

// 寫出 SQL 語法
$sqlstr = "UPDATE memb SET membcode=:membcode, password=:password, membname=:membname, nickname=:nickname, email=:email, level=:level, remark=:remark WHERE uid=:uid " ;

$sth = $pdo->prepare($sqlstr);
$sth->bindParam(':membcode', $membcode, PDO::PARAM_STR);
$sth->bindParam(':password', $password, PDO::PARAM_STR);
$sth->bindParam(':membname', $membname, PDO::PARAM_STR);
$sth->bindParam(':nickname', $nickname, PDO::PARAM_STR);
$sth->bindParam(':email', $email, PDO::PARAM_STR);
$sth->bindParam(':level', $level, PDO::PARAM_STR);
$sth->bindParam(':remark', $remark, PDO::PARAM_STR);

$sth->bindParam(':uid', $uid, PDO::PARAM_INT);

// 執行SQL及處理結果
if($sth->execute())
{
   $url_display = 'memb_display.php?uid=' . $uid;
   header('Location: ' . $url_display);
}
else
{
   header('Location: error.php?type=edit_save');
   echo print_r($pdo->errorInfo()) . '<br />' . $sqlstr;  // 此列供開發時期偵錯用
}
?>