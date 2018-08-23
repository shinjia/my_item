<?php
include '../common/config.php';
include '../common/utility.php';

$cond = isset($_GET['cond']) ? $_GET['cond'] : '';  // 條件

$page = isset($_GET['page']) ? $_GET['page'] : 1;   // 目前的頁碼
$numpp = 15;  // 每頁的筆數




// 隨應用的條件而有不同，需改寫
$a_key = explode('|', $cond);
$sql_where = "TRUE ";
if(isset($a_key[0])) $sql_where .= "AND username LIKE '%" .  $a_key[0] . "%' ";  
if(isset($a_key[1])) $sql_where .= "AND address = '" .  $a_key[1] . "' ";

echo $sql_where;

// 連接資料庫
$pdo = db_open();

// 取得分頁所需之資訊 (總筆數、總頁數、擷取記錄之起始位置)
$sqlstr = "SELECT count(*) as total_rec FROM memb WHERE " . $sql_where;
$sth = $pdo->query($sqlstr);
if($row = $sth->fetch(PDO::FETCH_ASSOC))
{
   $total_rec = $row["total_rec"];
}
$total_page = ceil($total_rec / $numpp);  // 計算總頁數
$tmp_start = ($page-1) * $numpp;  // 從第幾筆記錄開始抓取資料

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM memb ";
$sqlstr .= "WHERE " . $sql_where;
$sqlstr .= " LIMIT " . $tmp_start . "," . $numpp;

$sth = $pdo->prepare($sqlstr);

if($sth->execute())
{
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

    <td><a href="memb_display.php?uid=$uid">詳細</a></td>
    <td><a href="memb_edit.php?uid=$uid">修改</a></td>
    <td><a href="memb_delete.php?uid=$uid" onClick="return confirm('確定要刪除嗎？');">刪除</a></td>
</tr>
HEREDOC;
    }


// ------ 分頁處理開始 -------------------------------------
// 處理分頁之超連結：上一頁、下一頁、第一首、最後頁
$lnk_pageprev = '?cond=' . $cond . '&page=' . (($page==1)?(1):($page-1));
$lnk_pagenext = '?cond=' . $cond . '&page=' . (($page==$total_page)?($total_page):($page+1));
$lnk_pagehead = '?cond=' . $cond . '&page=1';
$lnk_pagelast = '?cond=' . $cond . '&page=' . $total_page;

// 處理各頁之超連結：列出所有頁數 (暫未用到，保留供參考)
$lnk_pagelist = "";
for($i=1; $i<=$page-1; $i++)
{ $lnk_pagelist .= '<a href="?cond=' . $cond . '&page='.$i.'">'.$i.'</a> '; }
$lnk_pagelist .= '[' . $i . '] ';
for($i=$page+1; $i<=$total_page; $i++)
{ $lnk_pagelist .= '<a href="?cond=' . $cond . '&page='.$i.'">'.$i.'</a> '; }

// 處理各頁之超連結：下拉式跳頁選單
$lnk_pagegoto  = '<form method="GET" action="" style="margin:0;">';
$lnk_pagegoto .= '<select name="page" onChange="submit();">';
for($i=1; $i<=$total_page; $i++)
{
   $is_current = (($i-$page)==0) ? ' selected' : '';
   $lnk_pagegoto .= '<option' . $is_current . '>' . $i . '</option>';
}
$lnk_pagegoto .= '</select>';
$lnk_pagegoto .= '<input type="hidden" name="cond" value="' . $cond . '">';
$lnk_pagegoto .= '</form>';

// 將各種超連結組合成HTML顯示畫面
$ihc_navigator  = <<< HEREDOC
<table border="0" align="center">
    <tr>
        <td>頁數：{$page} / {$total_page} &nbsp;&nbsp;&nbsp;</td>
        <td>
        <a href="{$lnk_pagehead}">第一頁</a> 
        <a href="{$lnk_pageprev}">上一頁</a> 
        <a href="{$lnk_pagenext}">下一頁</a> 
        <a href="{$lnk_pagelast}">最末頁</a> &nbsp;&nbsp;
        </td>
        <td>移至頁數：</td>
        <td>{$lnk_pagegoto}</td>
    </tr>
</table>
HEREDOC;
// ------ 分頁處理結束 -------------------------------------
}
else
{
   // 無法執行 query 指令時
   $html = error_message('list_page');
}


$html = <<< HEREDOC
<h2 align="center">共有 $total_rec 筆記錄</h2>
{$ihc_navigator}
<p>cond: {$cond}</p>
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

include 'pagemake.php';
pagemake($html, '');
?>