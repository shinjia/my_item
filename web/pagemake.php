<?php

function pagemake($content='', $head='')
{  
  $html = <<< HEREDOC
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>作品發表平台</title>
<base target="_blank">
</head>
<body>
<h1>作品發表平台的前台網頁</h1>

{$content}

</body>
</html>
HEREDOC;

echo $html;
}

?>