<?php
include '../common/config.php';
include '../common/utility.php';



// 連接資料庫
$pdo = db_open();

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM item ";

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
      $itemcode = convert_to_html($row['itemcode']);
      $membcode = convert_to_html($row['membcode']);
      $title = convert_to_html($row['title']);
      $category = convert_to_html($row['category']);
      $descr = convert_to_html($row['descr']);
      $maintext = convert_to_html($row['maintext']);
      $author = convert_to_html($row['author']);
      $picture = convert_to_html($row['picture']);
      $itemdate = convert_to_html($row['itemdate']);
      $status = convert_to_html($row['status']);
      $remark = convert_to_html($row['remark']);

    
      $data .= <<< HEREDOC
<tr>
   <td>{$uid}</td>
   <td>{$itemcode}</td>
   <td>{$membcode}</td>
   <td>{$title}</td>
   <td>{$category}</td>
   <td>{$descr}</td>
   <td>{$str_maintext}</td>
   <td>{$author}</td>
   <td>{$picture}</td>
   <td>{$itemdate}</td>
   <td>{$status}</td>
   <td>{$remark}</td>

   <td><a href="item_display.php?uid={$uid}">詳細</a></td>
   <td><a href="item_edit.php?uid={$uid}">修改</a></td>
   <td><a href="item_delete.php?uid={$uid}" onClick="return confirm('確定要刪除嗎？');">刪除</a></td>
</tr>
HEREDOC;
   }
   
   $html = <<< HEREDOC
<h2 align="center">共有 {$total_rec} 筆記錄</h2>
<table border="1" align="center">
   <tr>
      <th>序號</th>
      <th>作品代碼</th>
      <th>會員代碼</th>
      <th>作品名稱</th>
      <th>種類代號</th>
      <th>作品描述</th>
      <th>詳細說明</th>
      <th>作者</th>
      <th>作品圖片</th>
      <th>發表日期</th>
      <th>狀態</th>
      <th>備註</th>

      <th colspan="3" align="center"><a href="item_add.php">新增記錄</a></th>
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