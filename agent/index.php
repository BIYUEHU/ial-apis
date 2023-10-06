<?php
header('Access-Control-Allow-Origin: *');
$url = $_REQUEST['url'];
echo $url ? file_get_contents($url) : '';