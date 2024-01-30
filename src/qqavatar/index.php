<?php

require(__DIR__ . '../../core/const.php');


$qq = $_REQUEST['qq'] ?? DEFAULT_QQAVATAR_QQ;
$size = $_REQUEST['size'] ?? 640;

header("Content-type: image/jpeg");
echo file_get_contents("https://q.qlogo.cn/g?b=qq&s=$size&nk=$qq");
