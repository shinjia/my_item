<?php



$html = <<< HEREDOC
<button onclick="history.back();">返回</button>
<h2>新增資料</h2>
<form action="item_add_save.php" method="post">
<table>
    <tr><th>作品代碼</th><td><input type="text" name="itemcode" value="" /></td></tr>
    <tr><th>會員代碼</th><td><input type="text" name="membcode" value="" /></td></tr>
    <tr><th>作品名稱</th><td><input type="text" name="title" value="" /></td></tr>
    <tr><th>種類代號</th><td><input type="text" name="category" value="" /></td></tr>
    <tr><th>作品描述</th><td><input type="text" name="descr" value="" /></td></tr>
    <tr><th>詳細說明</th><td><textarea name="maintext"></textarea></td></tr>
    <tr><th>作者</th><td><input type="text" name="author" value="" /></td></tr>
    <tr><th>作品圖片</th><td><input type="text" name="picture" value="" /></td></tr>
    <tr><th>發表日期</th><td><input type="text" name="itemdate" value="" /></td></tr>
    <tr><th>狀態</th><td><input type="text" name="status" value="" /></td></tr>
    <tr><th>備註</th><td><input type="text" name="remark" value="" /></td></tr>

</table>
<p><input type="submit" value="新增"></p>
</form>
HEREDOC;

include 'pagemake.php';
pagemake($html, '');
?>