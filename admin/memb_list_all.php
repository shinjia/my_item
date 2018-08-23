<?php
include '../common/config.php';
include '../common/utility.php';



// 連接資料庫
$pdo = db_open();

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM memb ";

$sth = $pdo->prepare($sqlstr);

// 執行SQL及處理結果
if($sth->execute())
{
   // 成功執行 query 指令
   $total_rec = $sth->rowCount();
   $data = '';
   while($row = $sth->fetch(PDO::FETCH_ASSOC))
   {
      $uid = $row['uid'];
      $membcode = convert_to_html($row['membcode']);
      $password = convert_to_html($row['password']);
      $membname = convert_to_html($row['membname']);
      $nickname = convert_to_html($row['nickname']);
      $email = convert_to_html($row['email']);
      $level = convert_to_html($row['level']);
      $remark = convert_to_html($row['remark']);

    
      $data .= <<< HEREDOC
<tr>
   <td>{$uid}</td>
   <td>{$membcode}</td>
   <td>{$password}</td>
   <td>{$membname}</td>
   <td>{$nickname}</td>
   <td>{$email}</td>
   <td>{$level}</td>
   <td>{$remark}</td>

   <td><a href="memb_display.php?uid={$uid}">詳細</a></td>
   <td><a href="memb_edit.php?uid={$uid}">修改</a></td>
   <td><a href="memb_delete.php?uid={$uid}" onClick="return confirm('確定要刪除嗎？');">刪除</a></td>
</tr>
HEREDOC;
   }
   
   $html = <<< HEREDOC
<h2 align="center">共有 {$total_rec} 筆記錄</h2>
<table border="1" align="center">
   <tr>
      <th>序號</th>
      <th>會員代碼</th>
      <th>密碼</th>
      <th>姓名</th>
      <th>暱稱</th>
      <th>電子郵件</th>
      <th>等級</th>
      <th>備註</th>

      <th colspan="3" align="center"><a href="memb_add.php">新增記錄</a></th>
   </tr>
   {$data}
</table>
HEREDOC;
}
else
{
   // 無法執行 query 指令時
   $html = error_message('list_all');
}


include 'pagemake.php';
pagemake($html, '');
?>