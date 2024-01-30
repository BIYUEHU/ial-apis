<?php
header('Access: *');
header('Access-Control-Allow-Origin: *');
$url = $_REQUEST['url'];
$type = $_REQUEST['content-type'];
$type && header("Content-type: " . $type);
echo $url ? file_get_contents($url) : '';