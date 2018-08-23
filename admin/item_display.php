<?php
include '../common/config.php';
include '../common/utility.php';

$uid = $_GET["uid"];  // 強制轉成數值



// 連接資料庫
$pdo = db_open();

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM item WHERE uid=:uid ";

$sth = $pdo->prepare($sqlstr);
$sth->bindParam(':uid', $uid, PDO::PARAM_INT);

// 執行 SQL
if($sth->execute())
{
   // 成功執行 query 指令
   if($row = $sth->fetch(PDO::FETCH_ASSOC))
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

        // 顯示『maintext』欄位的文字區域文字
        $str_maintext = nl2br($maintext);

      $data = <<< HEREDOC
<table>
   <tr><th>作品代碼</th><td>{$itemcode}</td></tr>
   <tr><th>會員代碼</th><td>{$membcode}</td></tr>
   <tr><th>作品名稱</th><td>{$title}</td></tr>
   <tr><th>種類代號</th><td>{$category}</td></tr>
   <tr><th>作品描述</th><td>{$descr}</td></tr>
   <tr><th>詳細說明</th><td>{$str_maintext}</td></tr>
   <tr><th>作者</th><td>{$author}</td></tr>
   <tr><th>作品圖片</th><td>{$picture}</td></tr>
   <tr><th>發表日期</th><td>{$itemdate}</td></tr>
   <tr><th>狀態</th><td>{$status}</td></tr>
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
<button onclick="location.href='item_list_page.php';">返回列表</button>
<h2>顯示資料</h2>
{$data}
HEREDOC;
 
 
include 'pagemake.php';
pagemake($html, '');
?>