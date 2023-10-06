<?php
/*
    ByBIYUEHU
    http://hotaru.icu
*/
die('已停用,请使用V2版本接口:/api/seimg/v2/');
header('Access-Control-Allow-Origin: *');
// file_get_contents('http://82.157.165.201/api/stat?name=seimg_ialapi&op=write');

global $seimg_version, $by2, $code, $result;
$seimg_version = "v1.0";
$by2 = "http://hotaru.icu";
 $speedCom = "https://i.pixiv.re";
//$speedCom = "https://sex.nyan.xyz";

//单复数随机
$timeStamp = time(); //时间戳
$randTemp = rand(0,3);
$temp3 = $randTemp <= 1 ? 3 : rand(0, 2);
if ($temp3 == 0) {$temp3 = null;};

$r18_get = trim($_GET['r18']);
$format_get = trim($_GET['format']);

$r18_get == "true" ? $r18Temp = "r18" : $r18Temp;
$format_get != "json" && $format_get != "img3" && $format_get != "img2" && $format_get != "img" && $format_get != "text" ? $format_get = "json" : $format_get;
$res = '../../res/text/seimg' . $r18Temp . $temp_3 . '.txt';


//打开文件并随机取行
$openFile = file($res);
$lines = count($openFile);
$rand = rand(0,$lines);


if ($format_get == "img3" || $format_get == "img2" || $format_get == "img" || $format_get == "text") { 
    $json_back = $openFile[$rand];
    $array = json_decode($json_back);
        
    if ($temp_2 == 1) {
        $url = $array -> url;
    } else {
        $arrayTemp_2 = $array -> data;
        $array = $arrayTemp_2[0];
        
        $urlTemp = $array -> urls;
        $url = $urlTemp -> original;
    }
    
    $temp_4 = strpos($url, "re");
    ($temp_4 == '' || $temp_4 == null || $temp_4 == '') ? $numTemp = 19 : $numTemp = 18;
    $url = $speedCom . substr($url, $numTemp);
        
  
    switch ($format_get) {
        case "img3":
            //直接跳转到图片URL
            header("location: $url");
        break;
        case "img2":
            echo '<html style="height: 100%;">
               <head>
                  <meta name="viewport" content="width=device-width, minimum-scale=0.1">
               </head>
               <body style="margin: 0px; background: #0e0e0e; height: 100%">
                  <img style="display: block;-webkit-"user-select: none;margin: auto;background-color: hsl(0, 0%, 25%);" src="' . $url . '" width="' . $width . '" height="' . $height .'">
               </body>
            </html>
            ';
        break;
        case "text":
            //文本输出图片URL
            header('content-type:text/plain');
            echo $url;
        break;
        default:
            $temp_4 = strripos($url, ".");
            $imgType = substr($url, $temp_4 + 1);
            if ($imgType == 'jpg') { $imgType = 'jpeg';}
            
            header('content-type:image/' . $imgType . ';');
            $content = file_get_contents($url);
            echo $content;
        break;
    }
} else {
    if ($rand != null && $rand != "") {
        $json_back = $openFile[$rand];
        $array = json_decode($json_back);
        
        //用PHP解码JSON并逐个解析每个键值
        //先解决掉两个txt文件里JSON数据的格式不同
        if ($temp_2 == 1) {
            $url = $array -> url;
        } else {
            $arrayTemp_2 = $array -> data;
            $array = $arrayTemp_2[0];
            
            $urlTemp = $array -> urls;
            $url = $urlTemp -> original;
        }
        
        
        $pid = $array -> pid;
        $uid = $array -> uid;
        $title = $array -> title;
        $author = $array -> author;
        $r18 = $array -> r18;
        $r18 == 1 ? $r18 = "true" : $r18 = "false";
        $width = $array -> width;
        $height = $array -> height;
        
        //处理tags这个数组为字符串
        $tags = "[\"";
        //方法1:for + switch逐个遍历并组合成字符串
        /*for ($i = 0;$i < count($array -> tags);$i++) {
            switch ($i) {
                case count($array -> tags) - 1:
                    $tags = $tags . ($array -> tags)[count($array -> tags) - 1] . "\"]";
                break;
                default:
                    $tags = $tags . ($array -> tags)[$i] . "\",\"";
                break;
            }
        }*/
        //方法2:直接用foreacht
        foreach ($array -> tags as $value) {
            if ($value != ($array -> tags)[count($array -> tags) - 1]) {
                $tags = $tags . $value . "\",\"";
            } else {
                $tags = $tags . $value . "\"]";
            }
        }
        unset($value);
        
        header('content-type:application/json');
        $code = 500;
        
        //解析完毕开始组合
        $result = "{\"code\":$code,\"time\":$timeStamp,\"version\":\"$seimg_version\",\"by\":\"$by2\",\"data\":{\"pid\":$pid,\"uid\":$uid,\"title\":\"$title\",\"author\":\"$author\",\"r18\":$r18,\"width\": $width,\"height\":$height,\"tags\":$tags,\"url\":\"$url\"}}";
    } else {
        header('content-type:application/json');
        $code = 499;
        $result = "{\"code\":$code,\"time\":$timeStamp,\"version\":\"$seimg_version\",\"by\":\"$by2\",\"data\":null}";
    }
    
    echo $result;
}

/*
send:
名称 必填 类型 说明
r18 否 boolean 是否为R18图片,默认false 
format 否 string 返回方式,json或img,默认json

returnJson:
名称 类型 说明
code int 状态码,参考下方
time int 请求时间戳
version string API版本
by string API作者
data object 返回数据

$data
pid int 图片id
uid int 作者uid
title string 图片标题
author string 作者名字
r18 boolean 是否为R18图片
width int 图片宽度(px)
height int 图片高度(px)
tags array 图片标签
url string 图片链接

codeValue:
499 内部错误
500 请求成功

*/
