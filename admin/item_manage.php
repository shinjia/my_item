<?php
include '../common/config.php';
include '../common/utility.php';

$op  = isset($_GET['op']) ? $_GET['op'] : 'HOME'; 

$uid = (isset($_POST['uid'])) ? $_POST['uid'] : (isset($_GET['uid'])?$_GET['uid']:'');

$code = isset($_GET['code']) ? $_GET['code'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;   // 目前的頁碼

$numpp = 15;

$itemcode = (isset($_POST['itemcode']))  ? $_POST['itemcode']  : '';
$membcode = (isset($_POST['membcode']))  ? $_POST['membcode']  : '';
$title = (isset($_POST['title']))  ? $_POST['title']  : '';
$category = (isset($_POST['category']))  ? $_POST['category']  : '';
$descr = (isset($_POST['descr']))  ? $_POST['descr']  : '';
$maintext = (isset($_POST['maintext']))  ? $_POST['maintext']  : '';
$author = (isset($_POST['author']))  ? $_POST['author']  : '';
$picture = (isset($_POST['picture']))  ? $_POST['picture']  : '';
$itemdate = (isset($_POST['itemdate']))  ? $_POST['itemdate']  : '';
$status = (isset($_POST['status']))  ? $_POST['status']  : '';
$remark = (isset($_POST['remark']))  ? $_POST['remark']  : '';



// 連接資料庫
$pdo = db_open();

switch($op)
{
   case 'LIST_PAGE' :
        $url_page = '?op=LIST_PAGE';

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

    <td><a href="?op=DISPLAY&uid=$uid">詳細</a></td>
    <td><a href="?op=EDIT&uid=$uid">修改</a></td>
    <td><a href="?op=DELETE&uid=$uid" onClick="return confirm('確定要刪除嗎？');">刪除</a></td>
</tr>
HEREDOC;
            }
        
        // ------ 分頁處理開始 -------------------------------------
        // 處理分頁之超連結：上一頁、下一頁、第一首、最後頁
        $lnk_pageprev = '?op=LIST_PAGE&page=' . (($page==1)?(1):($page-1));
        $lnk_pagenext = '?op=LIST_PAGE&page=' . (($page==$total_page)?($total_page):($page+1));
        $lnk_pagehead = '?op=LIST_PAGE&page=1';
        $lnk_pagelast = '?op=LIST_PAGE&page=' . $total_page;
        
        // 處理各頁之超連結：列出所有頁數 (暫未用到，保留供參考)
        $lnk_pagelist = "";
        for($i=1; $i<=$page-1; $i++)
        { $lnk_pagelist .= '<a href="?op=LIST_PAGE&page='.$i.'">'.$i.'</a> '; }
        $lnk_pagelist .= '[' . $i . ']';
        for($i=$page+1; $i<=$total_page; $i++)
        { $lnk_pagelist .= '<a href="?op=LIST_PAGE&page='.$i.'">'.$i.'</a> '; }
        
        // 處理各頁之超連結：下拉式跳頁選單
        $lnk_pagegoto  = '<form method="GET" action="" style="margin:0;">';
        $lnk_pagegoto .= '<input type="hidden" name="op" value="LIST_PAGE">';
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
        
        // 網頁輸出        
        $html = <<< HEREDOC
<h2 align="center">資料列表，共有 {$total_rec} 筆記錄</h2>
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

        <th colspan="3" align="center">[<a href="?op=ADD">新增記錄</a>]</th>
    </tr>
{$data}
</table>
HEREDOC;
        }
        else
        {
           // 無法執行 query 指令時
           $html = error_message('list_page');
        }
        
        break;
        
        
        
   case 'ADD' :



        $html = <<< HEREDOC
<button onclick="history.back();">返回</button>
<h2>新增資料</h2>
<form action="?op=ADD_SAVE" method="post">
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
        break;
        
       
        
   case 'ADD_SAVE' :
        // 寫出 SQL 語法
       $sqlstr = "INSERT INTO item(itemcode, membcode, title, category, descr, maintext, author, picture, itemdate, status, remark) VALUES (:itemcode, :membcode, :title, :category, :descr, :maintext, :author, :picture, :itemdate, :status, :remark)";
       
        $sth = $pdo->prepare($sqlstr);
$sth->bindParam(':itemcode', $itemcode, PDO::PARAM_STR);
$sth->bindParam(':membcode', $membcode, PDO::PARAM_STR);
$sth->bindParam(':title', $title, PDO::PARAM_STR);
$sth->bindParam(':category', $category, PDO::PARAM_STR);
$sth->bindParam(':descr', $descr, PDO::PARAM_STR);
$sth->bindParam(':maintext', $maintext, PDO::PARAM_STR);
$sth->bindParam(':author', $author, PDO::PARAM_STR);
$sth->bindParam(':picture', $picture, PDO::PARAM_STR);
$sth->bindParam(':itemdate', $itemdate, PDO::PARAM_STR);
$sth->bindParam(':status', $status, PDO::PARAM_STR);
$sth->bindParam(':remark', $remark, PDO::PARAM_STR);

        
        // 執行SQL及處理結果
        if($sth->execute())
        {
           $new_uid = $pdo->lastInsertId();    // 傳回剛才新增記錄的 auto_increment 的欄位值
           $url_display = '?op=DISPLAY&uid=' . $new_uid;
           header('Location: ' . $url_display);
        }
        else
        {
           header('Location: ?op=ERROR&type=add_save');
           echo print_r($pdo->errorInfo()) . '<br />' . $sqlstr; exit;  // 此列供開發時期偵錯用
        }
        break;
       
       
        
   case 'DISPLAY' :
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
<button onclick="location.href='?op=LIST_PAGE';">返回列表</button>
<h2>詳細資料</h2>
{$data}
HEREDOC;
        break;
        
        
   case 'EDIT' :
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
<form action="?op=EDIT_SAVE" method="post">
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
<button onclick="history.back();">返回</button>
<h2>修改資料</h2>
{$data}
HEREDOC;
        break;
        
        
        
   case 'EDIT_SAVE' :
        $sqlstr = "UPDATE item SET itemcode=:itemcode, membcode=:membcode, title=:title, category=:category, descr=:descr, maintext=:maintext, author=:author, picture=:picture, itemdate=:itemdate, status=:status, remark=:remark WHERE uid=:uid " ;
        
        $sth = $pdo->prepare($sqlstr);
$sth->bindParam(':itemcode', $itemcode, PDO::PARAM_STR);
$sth->bindParam(':membcode', $membcode, PDO::PARAM_STR);
$sth->bindParam(':title', $title, PDO::PARAM_STR);
$sth->bindParam(':category', $category, PDO::PARAM_STR);
$sth->bindParam(':descr', $descr, PDO::PARAM_STR);
$sth->bindParam(':maintext', $maintext, PDO::PARAM_STR);
$sth->bindParam(':author', $author, PDO::PARAM_STR);
$sth->bindParam(':picture', $picture, PDO::PARAM_STR);
$sth->bindParam(':itemdate', $itemdate, PDO::PARAM_STR);
$sth->bindParam(':status', $status, PDO::PARAM_STR);
$sth->bindParam(':remark', $remark, PDO::PARAM_STR);

        $sth->bindParam(':uid', $uid, PDO::PARAM_INT);
        
        // 執行SQL及處理結果
        if($sth->execute())
        {
           $url_display = '?op=DISPLAY&uid=' . $uid;
           header('Location: ' . $url_display);
        }
        else
        {
           header('Location: ?op=ERROR&type=edit_save');
           echo print_r($pdo->errorInfo()) . '<br />' . $sqlstr;  // 此列供開發時期偵錯用
        }
        break;
        
        

   case 'DELETE' :
        $sqlstr = "DELETE FROM item WHERE uid=:uid ";
        
        $sth = $pdo->prepare($sqlstr);
        $sth->bindParam(':uid', $uid, PDO::PARAM_INT);
        
        // 執行SQL及處理結果
        if($sth->execute())
        {
           $refer = $_SERVER['HTTP_REFERER'];  // 呼叫此程式之前頁
           header('Location: ' . $refer);
        }
        else
        {
           header('Location: ?op=ERROR&type=delete');
           echo print_r($pdo->errorInfo()) . '<br />' . $sqlstr;  // 此列供開發時期偵錯用
        }
        break;



   case 'ERROR' :
        $type = isset($_GET['type']) ? $_GET['type'] : 'default';
        
        $html = error_message($type);
        break;
        
        

   case 'PAGE' :
        $path = 'data/';   // 存放網頁內容的資料夾
        $filename = $path . $code . '.html';  // 規定副檔案為 .html
        
        if (!file_exists($filename))
        {
        	// 找不到檔案時的顯示訊息
            $html = error_message('page', ('錯誤：傳遞參數有誤。檔案『' . $filename . '』不存在！</p>'));
        }
        else
        {
            $html = join ('', file($filename));   // 讀取檔案內容並組成文字串
        } 
        break;


        
   case 'HOME' : 
        $html = '<p><br /><br /><br />Welcome...資料管理系統<br /><br /><br /><br /></p>';
        break;
   
   
   
   default :
        $html = '<p><br /><br /><br />Welcome...資料管理系統<br /><br /><br /><br /></p>';
     
}

$pdo = null;


include 'pagemake.php';
pagemake($html, '');
?>