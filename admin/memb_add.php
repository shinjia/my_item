<?php



$html = <<< HEREDOC
<button onclick="history.back();">返回</button>
<h2>新增資料</h2>
<form action="memb_add_save.php" method="post">
<table>
    <tr><th>會員代碼</th><td><input type="text" name="membcode" value="" /></td></tr>
    <tr><th>密碼</th><td><input type="text" name="password" value="" /></td></tr>
    <tr><th>姓名</th><td><input type="text" name="membname" value="" /></td></tr>
    <tr><th>暱稱</th><td><input type="text" name="nickname" value="" /></td></tr>
    <tr><th>電子郵件</th><td><input type="text" name="email" value="" /></td></tr>
    <tr><th>等級</th><td><input type="text" name="level" value="" /></td></tr>
    <tr><th>備註</th><td><input type="text" name="remark" value="" /></td></tr>

</table>
<p><input type="submit" value="新增"></p>
</form>
HEREDOC;

include 'pagemake.php';
pagemake($html, '');
?>