<?php
/*
 * @Author: Biyuehu biyuehuya@gmail.com
 * @Blog: http://hotaru.icu
 * @Date: 2023-01-28 18:16:10
 */


/* 初始化区 */
// 关闭报错
// error_reporting(0);

// 开启SESSION
session_start();

// 跨域请求
header('Access-Control-Allow-Origin: *');

// 引入config
$config = require(__DIR__ . '/config.php');

// 引入class
require(__DIR__ . '/class.php');

// 实例化调用限制类
$demo = new CallLimit($config['callLimit']);


/* 定义区 */
define('ABOUT_AUTHOR', 'https://hotaru.icu');


/* 公共函数区 */
/**
 * 数据库查询
 * @param string $sql
 * @param boolean $all
 */
function dbQuery($sql, $all = false)
{
    global $config;
    // 实例化数据库类
    $dbDemo = new Db($config['database']);
    // 执行
    return $dbDemo::query($sql, $all);
}

/**
 * 数据库执行
 * @param string $sql
 */
function dbExec($sql)
{
    global $config;
    // 实例化数据库类
    $dbDemo = new Db($config['database']);
    // 执行
    return $dbDemo::exec($sql);
}

/**
 * 打印结果
 * @param number $code
 * @param array|null $data
 * @param array $appConfig
 */
function printResult($code, $data, $appConfig)
{
    header('Content-type: application/json');
    $errorCode = array(
        499 => 'fail:database error',
        500 => 'success',
        501 => 'fail:not found'
    );

    $result = array(
        'code' => $code,
        'message' => $errorCode[$code],
        'time' => time(),
        'version' => $appConfig['version'],
        'by' => ABOUT_AUTHOR,
        'data' => $data
    );
    // echo stripslashes(urldecode(json_encode($result, 256)));

    $result = urldecode(json_encode($result, 256));
    echo str_replace('\/', '/', $result);
    exit();
}
