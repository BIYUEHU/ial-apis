<?php
/*
 * @Author: Biyuehu biyuehuya@gmail.com
 * @Blog: http://hotaru.icu
 * @Date: 2023-01-08 15:11:31
 */

require(__DIR__ . '../../../core/controller.php');

class Hitokoto
{
    private const HITOKOTO_VERSION = 'v2.2';
    public const AppConfig = [
        'version' => self::HITOKOTO_VERSION
    ];

    /* SQL */
    private const HitokotoGetModel = "SELECT * FROM ial_hitokoto ORDER BY RAND() limit 1";
    private const HitokotoListModel = "SELECT * FROM ial_hitokoto WHERE view = 'true' ORDER BY likes DESC";


    public function __construct()
    {
        /* 定义区 */
        file_get_contents('https://api.hotaru.icu/api/stat?name=hitokoto_ialapi&op=write');

        $result = self::handel();
        if ($result !== false) {
            printResult($result[0], $result[1], self::AppConfig);
        }
    }


    private static function handel()
    {
        $op = $_REQUEST['op'];

        switch ($op) {
            case 'likes':
                return self::likes();
            case 'list':
                return self::listView();
            default:
                return self::get();
        }
    }


    private static function likes()
    {
        // 处理
        $id = $_REQUEST['id'];

        $code = 501;
        $data = null;
        if (!empty($id)) {
            // 获取数据并手动自加
            $row = dbQuery("SELECT * FROM ial_hitokoto WHERE id = '{$id}'");
            if (!empty($row['id'])) {
                $likes = intval($row['likes']) + 1;
                dbExec("UPDATE ial_hitokoto SET likes = '{$likes}' WHERE id = '{$id}'");
                $code = 500;
                $data = [];
            }
        }

        return [$code, $data];
    }


    private static function get()
    {
        $format = $_REQUEST['format'];
        // 取数据
        $row = dbQuery(self::HitokotoGetModel);


        switch ($format) {
            case 'text':
                header('content-type:text/plain');
                $result = $row['msg'] . (empty($row['_from']) ? '' : '——' . $row['_from']);
                echo $result;
                return false;
            default:
                $typeList = array(
                    1 => 'ACG',
                    2 => '文学',
                    3 => '俗语',
                    4 => '杂类'
                );

                $data = array(
                    'msg' => $row['msg'],
                    'from' => $row['_from'],
                    'type' => $typeList[intval($row['type'])]
                );

                return [500, $data];
        }
    }


    private static function listView()
    {
        $code = 500;

        /* 处理参数 */
        $page = $_REQUEST['page'] ?? 1;
        $limit = $_REQUEST['limit'] ?? 20;

        $code = 501;
        $data = null;
        if ($limit >= 1 && $limit <= 35) {
            $rows = dbQuery(self::HitokotoListModel, true);
            $data = [];
            $count = 0;

            /* 生成 */
            foreach ($rows as $key => $val) {
                $count++;
                if ((($page - 1) * $limit) <= $key && $key < ($page * $limit)) {
                    array_push($data, array(
                        'id' => intval($val[0]),
                        'msg' => $val[1],
                        'from' => $val[2],
                        'type' => $val[3],
                        'likes' => $val[4],
                        'view' => $val[5] == 'true' ? true : false,
                        'reg_date' => $val[6]
                    ));
                }
            }
            $code = 500;
            $data = [$count, $data];
        }

        return [$code, $data];
    }
}


(new Hitokoto);
