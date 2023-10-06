<?php
class CallLimit
{
    public function __construct($config)
    {
        $nowtime = time();
        // 判断SESSION是否被设置以区分是否为第一次访问
        if (isset($_SESSION['callinfo'])) {
            $callInfo = $_SESSION['callinfo'];
            // 当前时间减去记录的初始后最后调用时间(格式均为时间戳)
            $quo = $nowtime - $callInfo['lasttime'];
            if ($quo > $config['cycle']) {
                // 超过时间周期则重置为当前时间并重置调用次数
                $callInfo['lasttime'] = $nowtime;
                $callInfo['num'] = 1;
            } else if ($quo <= $config['cycle'] && $callInfo['num'] > $config['cycleNum']) {
                // 在时间周期内并超过调用次数限制则打印JSON数据结果
                // 传入message提示信息
                header('Content-type: text/plain');
                exit($config['refuseMsg'] . 'ss');
            } else {
                // 在时间周期内但未超过调用次数限制则+1
                $callInfo['num'] = $callInfo['num'] + 1;
            }
        } else {
            // 第一次访问则设置值
            $callInfo['lasttime'] = $nowtime;
            $callInfo['num'] = 1;
        }
        $_SESSION['callinfo'] = $callInfo;
    }
}


class Db
{
    public static $db;

    public function __construct($config)
    {
        self::$db = new mysqli($config['host'], $config['userName'], $config['passWord'], $config['dbName'], $config['port']);

        if (self::$db->connect_error) {
            die("fail:database content failed" . self::$db->connect_error);
        }
    }

    public static function query($sql, $all = false)
    {
        return $all == false ? self::$db->query($sql)->fetch_assoc() : self::$db->query($sql)->fetch_all();
    }

    public static function exec($sql)
    {
        return self::$db->query($sql);
    }
}
