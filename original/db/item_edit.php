<?php
include '../common/config.php';
include '../common/utility.php';
$uid = $_GET["uid"];



// 連接資料庫
$pdo = db_open();

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM item WHERE uid=:uid ";

$sth = $pdo->prepare($sqlstr);
$sth->bindParam(':uid', $uid, PDO::PARAM_INT);


// 執行SQL及處理結果
if($sth->execute())
{
   // 成功執行 query 指令
   if($row = $sth->fetch(PDO::FETCH_ASSOC))
   {
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


      
      $data = <<< HEREDOC
<form action="item_edit_save.php" method="post">
    <table>
        <tr><th>作品代碼</th><td><input type="text" name="itemcode" value="{$itemcode}" /></td></tr>
        <tr><th>會員代碼</th><td><input type="text" name="membcode" value="{$membcode}" /></td></tr>
        <tr><th>作品名稱</th><td><input type="text" name="title" value="{$title}" /></td></tr>
        <tr><th>種類代號</th><td><input type="text" name="category" value="{$category}" /></td></tr>
        <tr><th>作品描述</th><td><input type="text" name="descr" value="{$descr}" /></td></tr>
        <tr><th>詳細說明</th><td><textarea name="maintext">{$maintext}</textarea></td></tr>
        <tr><th>作者</th><td><input type="text" name="author" value="{$author}" /></td></tr>
        <tr><th>作品圖片</th><td><input type="text" name="picture" value="{$picture}" /></td></tr>
        <tr><th>發表日期</th><td><input type="text" name="itemdate" value="{$itemdate}" /></td></tr>
        <tr><th>狀態</th><td><input type="text" name="status" value="{$status}" /></td></tr>
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