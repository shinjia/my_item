<?php
include '../common/config.php';
include '../common/utility.php';

$uid = $_GET["uid"] ;  // 強制轉成數值



// 連接資料庫
$pdo = db_open();

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM memb WHERE uid=:uid ";

$sth = $pdo->prepare($sqlstr);
$sth->bindParam(':uid', $uid, PDO::PARAM_INT);

// 執行 SQL
if($sth->execute())
{
   // 成功執行 query 指令
   if($row = $sth->fetch(PDO::FETCH_ASSOC))
   {
      $uid = $row['uid'];
      $membcode = convert_to_html($row['membcode']);
      $password = convert_to_html($row['password']);
      $membname = convert_to_html($row['membname']);
      $nickname = convert_to_html($row['nickname']);
      $email = convert_to_html($row['email']);
      $level = convert_to_html($row['level']);
      $remark = convert_to_html($row['remark']);


      $data = <<< HEREDOC
<table>
   <tr><th>會員代碼</th><td>{$membcode}</td></tr>
   <tr><th>密碼</th><td>{$password}</td></tr>
   <tr><th>姓名</th><td>{$membname}</td></tr>
   <tr><th>暱稱</th><td>{$nickname}</td></tr>
   <tr><th>電子郵件</th><td>{$email}</td></tr>
   <tr><th>等級</th><td>{$level}</td></tr>
   <tr><th>備註</th><td>{$remark}</td></tr>

</table>
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
   $data = error_message('display');
}


$html = <<< HEREDOC
<button onclick="location.href='memb_list_page.php';">返回列表</button>
<h2>顯示資料</h2>
{$data}
HEREDOC;
 
 
include 'pagemake.php';
pagemake($html, '');
?>