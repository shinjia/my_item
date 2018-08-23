<?php
include '../common/config.php';
include '../common/utility.php';

$code = isset($_GET['code']) ? $_GET['code'] : '';

$path = "data/";   // 存放網頁內容的資料夾
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

include 'item_pagemake.php';
pagemake($html);
?>