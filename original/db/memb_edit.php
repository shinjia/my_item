<?php
include '../common/config.php';
include '../common/utility.php';

$uid = $_GET["uid"];



// 連接資料庫
$pdo = db_open();

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM memb WHERE uid=:uid ";

$sth = $pdo->prepare($sqlstr);
$sth->bindParam(':uid', $uid, PDO::PARAM_INT);


// 執行SQL及處理結果
if($sth->execute())
{
   // 成功執行 query 指令
   if($row = $sth->fetch(PDO::FETCH_ASSOC))
   {
      $membcode = convert_to_html($row['membcode']);
      $password = convert_to_html($row['password']);
      $membname = convert_to_html($row['membname']);
      $nickname = convert_to_html($row['nickname']);
      $email = convert_to_html($row['email']);
      $level = convert_to_html($row['level']);
      $remark = convert_to_html($row['remark']);


      
      $data = <<< HEREDOC
<form action="memb_edit_save.php" method="post">
    <table>
        <tr><th>會員代碼</th><td><input type="text" name="membcode" value="{$membcode}" /></td></tr>
        <tr><th>密碼</th><td><input type="text" name="password" value="{$password}" /></td></tr>
        <tr><th>姓名</th><td><input type="text" name="membname" value="{$membname}" /></td></tr>
        <tr><th>暱稱</th><td><input type="text" name="nickname" value="{$nickname}" /></td></tr>
        <tr><th>電子郵件</th><td><input type="text" name="email" value="{$email}" /></td></tr>
        <tr><th>等級</th><td><input type="text" name="level" value="{$level}" /></td></tr>
        <tr><th>備註</th><td><input type="text" name="remark" value="{$remark}" /></td></tr>

    </table>
    <p>
    <input type="hidden" name="uid" value="{$uid}">
    <input type="submit" value="送出">
    </p>
</form>
HEREDOC;
   }
   else
   {
 	   $data = '查不到相關記錄！';
   }
}
else
{
   // 無法執行 query 指令時
   $data = error_message('edit');
}



$html = <<< HEREDOC
<h2>修改資料</h2>
<button onclick="history.back();">返回</button>
{$data}
HEREDOC;

include 'pagemake.php';
pagemake($html, '');
?>