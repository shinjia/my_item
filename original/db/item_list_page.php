<?php
include '../common/config.php';
include '../common/utility.php';

$page = isset($_GET['page']) ? $_GET['page'] : 1;   // 目前的頁碼
$numpp = 15;  // 每頁的筆數




// 連接資料庫
$pdo = db_open();

// 取得分頁所需之資訊 (總筆數、總頁數、擷取記錄之起始位置)
$sqlstr = "SELECT count(*) as total_rec FROM item ";
$sth = $pdo->query($sqlstr);
if($row = $sth->fetch(PDO::FETCH_ASSOC))
{
   $total_rec = $row["total_rec"];
}
$total_page = ceil($total_rec / $numpp);  // 計算總頁數
$tmp_start = ($page-1) * $numpp;  // 從第幾筆記錄開始抓取資料

// 寫出 SQL 語法
$sqlstr = "SELECT * FROM item ";
$sqlstr .= " LIMIT " . $tmp_start . "," . $numpp;

$sth = $pdo->prepare($sqlstr);

if($sth->execute())
{
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

    
        // 顯示『maintext』欄位的文字區域文字
        $str_maintext = nl2br($maintext);

   
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

    <td><a href="item_display.php?uid=$uid">詳細</a></td>
    <td><a href="item_edit.php?uid=$uid">修改</a></td>
    <td><a href="item_delete.php?uid=$uid" onClick="return confirm('確定要刪除嗎？');">刪除</a></td>
</tr>
HEREDOC;
    }


// ------ 分頁處理開始 -------------------------------------
// 處理分頁之超連結：上一頁、下一頁、第一首、最後頁
$n_prev = (($page==1)?(1):($page-1));
$n_next = (($page==$total_page)?($total_page):($page+1));
$n_head = 1;
$n_last = $total_page;

$lnk_pagebutton  = <<< HEREDOC
<form method="GET" action="" style="margin:0; display: inline-block; float: left;">
<button name="page" value="{$n_head}" onClick="submit();">第一頁</button>
<button name="page" value="{$n_prev}" onClick="submit();">上一頁</button>
<button name="page" value="{$n_next}" onClick="submit();">下一頁</button>
<button name="page" value="{$n_last}" onClick="submit();">最後頁</button>
</form>
HEREDOC;

// 處理各頁之超連結：列出所有頁數 (暫未用到，保留供參考)
$lnk_pagelist = "";
for($i=1; $i<=$page-1; $i++)
{ $lnk_pagelist .= '<a href="?page='.$i.'">'.$i.'</a> '; }
$lnk_pagelist .= '[' . $i . '] ';
for($i=$page+1; $i<=$total_page; $i++)
{ $lnk_pagelist .= '<a href="?page='.$i.'">'.$i.'</a> '; }

// 處理各頁之超連結：下拉式跳頁選單
$lnk_pagegoto  = '<form method="GET" action="" style="margin:0;">';
$lnk_pagegoto .= '<select name="page" onChange="submit();">';
for($i=1; $i<=$total_page; $i++)
{
   $is_current = (($i-$page)==0) ? ' selected' : '';
   $lnk_pagegoto .= '<option' . $is_current . '>' . $i . '</option>';
}
$lnk_pagegoto .= '</select>';
$lnk_pagegoto .= '</form>';

// 將各種超連結組合成HTML顯示畫面
$ihc_navigator  = <<< HEREDOC
<table border="0" align="center">
    <tr>
        <td>頁數：{$page} / {$total_page} &nbsp;&nbsp;&nbsp;</td>
        <td>{$lnk_pagebutton}</td>
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
<p></p>
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

include 'pagemake.php';
pagemake($html, '');
?>