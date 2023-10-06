<?php
/*
    ByBIYUEHU
    http://hotaru.icu
*/

die('已停用,请使用V2版本接口:/api/hitokoto/v2/');
header('Access-Control-Allow-Origin: *');
$format = $_GET['format'];

$timeStamp = time();
$by = "http://hotaru.icu";
// file_get_contents('http://82.157.165.201/api/stat?name=hitokoto_ialapi&op=write');

include('./init.php');

$temp = $DB->query('SELECT * FROM ial_hitokoto ORDER BY RAND() limit 1');
$row = $temp->fetch_assoc();
$msg = $row['msg'];
$from = $row['_from'];

if ($format != 'text') {
    header('content-type:application/json');

    $length = mb_strlen($msg);
    $result = array(
        "code" => 500,
        "time" => $timeStamp,
        "by" => $by,
        "data" => array(
            "msg" => $msg,
            "from" => $from,
            "length" => $length
        )
    );
    $result = stripcslashes(json_encode($result, 256));
} else {
    header('content-type:text/plain');
    $result = $msg . '——' . $from;
}

$DB->close();

echo $result;
