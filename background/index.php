<?php
$arr = file('./background.txt');
$url = $arr[rand(0, count($arr) - 2)];
$url = substr($url, 0, -2);

header("location: $url");
