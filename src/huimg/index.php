<?php
/*
 * @Author: Biyuehu biyuehuya@gmail.com
 * @Blog: http://hotaru.icu
 * @Date: 2023-01-08 15:11:31
 */

require(__DIR__ . '../../core/controller.php');

class HuImg
{
    private const HUIMG_VERSION = 'v1.0';
    public const AppConfig = [
        'version' => self::HUIMG_VERSION
    ];

    /* SQL */
    private const HuimgGetModel = "SELECT * FROM ial_huimg ORDER BY RAND() limit 1";
    // private const HuimgListModel = "SELECT * FROM ial_huimg WHERE view = 'true' ORDER BY likes DESC";


    public function __construct()
    {
        /* 定义区 */
        // file_get_contents('https://api.hotaru.icu/api/stat?name=huimg_ialapi&op=write');

        $result = self::handel();
        if ($result !== false) {
            printResult($result[0], $result[1], self::AppConfig);
        }
    }


    private static function handel()
    {
        $op = $_REQUEST['op'];

        switch ($op) {
            case 'add':
                return self::add();
            default:
                return self::get();
        }
    }


    private static function add()
    {
        // 处理
        $reqData = $_POST['data'];
        $code = 501;
        $data = null;
        if (!empty($reqData)) {
            $sql = "INSERT INTO `ial_huimg` (`type`, `tag`, `url`) VALUES";
            foreach ($reqData as $array) {
                $sql .= "({$_REQUEST['type']}, '{$array[0]}', '{$array[1]}'),";
            }
            $sql = substr($sql, 0, -1) . ';';
            $row = dbExec($sql);
            if (!empty($row)) {
                $code = 500;
                $data = [];
            } else {
                // echo $row, '\n', $sql;
                // exit();
            }
        }

        return [$code, $data];
    }


    private static function get()
    {
        $format = $_REQUEST['format'];
        // 取数据
        $row = dbQuery(self::HuimgGetModel);


        switch ($format) {
            case 'img':
                header("location: {$row['url']}");
                return false;
            default:
                $typeList = array(
                    1 => '通用',
                    2 => '手机',
                    3 => '电脑'
                );

                $data = array(
                    'tag' => explode(",", $row['tag']),
                    'url' => $row['url'],
                    'type' => $typeList[intval($row['type'])]
                );

                return [500, $data];
        }
    }
}


(new HuImg);
