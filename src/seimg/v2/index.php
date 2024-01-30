<?php
/*
 * @Author: Biyuehu biyuehuya@gmail.com
 * @Blog: http://hotaru.icu
 * @Date: 2023-01-08 15:11:31
 */

require(__DIR__ . '../../../core/controller.php');

class Seimg
{
    private const SEIMG_VERSION = 'v2.2';
    private const SEIMG_SPEEDCOM = 'https://i.pixiv.re';
    public const AppConfig = [
        'version' => self::SEIMG_VERSION,
    ];

    public function __construct()
    {
        /* 定义区 */
        file_get_contents('https://api.hotaru.icu/api/stat?name=seimg_ialapi&op=write');

        $result = self::handel();
        if ($result !== false) {
            printResult($result[0], $result[1], self::AppConfig);
        }
    }

    private static function getData($r18, $tag, $limit = 1)
    {
        $tagArr = explode('|', $tag);
        $tagArrMax = count($tagArr) - 1;

        $result = [];
        for ($init = 0; $init < $limit; $init++) {
            $tag = $tagArr[rand(0, $tagArrMax)];
            $sql = "SELECT * FROM ial_seimg WHERE {$r18} tags LIKE '%{$tag}%' ORDER BY RAND() limit 1";
            array_push($result, dbQuery($sql));
        }
        return $result;
    }


    private static function handel()
    {
        // 参数处理
        $limit = intval($_REQUEST['limit']);
        $limit = empty($limit) || $limit < 1 || $limit > 10 ? 1 : $limit;
        $format = $_REQUEST['format'];
        $tag = $_REQUEST['tag'] ?? '%';
        $r18 = intval($_REQUEST['r18']);
        switch ($r18) {
            case 1:
                $r18 = "r18 = 'true' AND ";
                break;
            case 2:
                $r18 = '';
                break;
            default:
                $r18 = "r18 = 'false' AND ";
        }


        $code = 501;
        $data = null;
        $rows = self::getData($r18, $tag, $limit);
        if (!empty($rows)) {
            $url = self::SEIMG_SPEEDCOM . parse_url($rows[0]['url'])['path'];
            switch ($format) {
                case 'img':
                    self::format_img($url, $rows[0]['type']);
                    return false;
                case 'img2':
                    self::format_img2($url, $rows[0]['width'], $rows[0]['height']);
                    return false;
                case 'img3':
                    header("location: {$url}");
                    return false;
                case 'text':
                    self::format_text($rows);
                    return false;
                default:
                    $data = self::format_json($rows);
                    $code = $data[1];
                    $data = $data[0];
            }
        }
        return [$code, $data];
    }


    private static function format_img($url, $type)
    {
        header("Content-type: image/{$type}");
        echo file_get_contents($url);
    }

    private static function format_img2($url, $width, $height)
    {
        header("Content-type: text/html");
        echo '<html style="height: 100%;">
            <head>
                <meta name="viewport" content="width=device-width, minimum-scale=0.1">
                <style id="lcn56vmv.de7">
                    * {filter: none !important}
                </style>
            </head>
                
            <body style="margin: 0px; background: #0e0e0e; height: 100%">
                <img style="display: block;-webkit-user-select: none;margin: auto;cursor: zoom-in;background-color: hsl(0, 0%, 90%);transition: background-color 300ms;"
                    src="' . $url . '" width="' . $width . '" height="' . $height . '">
            </body>
            <div id="sc-translator-shadow" style="all: initial;"></div>
        </html>';
    }

    private static function format_text($arr)
    {
        header('content-type:text/plain');
        $url = '';
        for ($i = 0; $i < count($arr); $i++) {
            $val = $arr[$i];
            $url = $i == 0 ? self::SEIMG_SPEEDCOM . parse_url($val['url'])['path'] : $url . ',' . self::SEIMG_SPEEDCOM . parse_url($val['url'])['path'];
        }
        echo $url;
    }

    private static function format_json($arr)
    {
        $data = [];
        foreach ($arr as $val) {
            $tagsArr = explode(',', $val['tags']);
            $tags = [];
            foreach ($tagsArr as $value) {
                if (!empty($value)) {
                    array_push($tags, $value);
                }
            }

            $url = self::SEIMG_SPEEDCOM . parse_url($val['url'])['path'];
            $code = 500;

            if (!empty($val['pid'])) {
                array_push($data, array(
                    'pid' => intval($val['pid']),
                    'uid' => intval($val['uid']),
                    'title' => $val['title'],
                    'author' => $val['author'],
                    'r18' => $val['r18'] == 'true' ? true : false,
                    'tags' => $tags,
                    'width' => intval($val['width']),
                    'height' => intval($val['height']),
                    'type' => $val['type'],
                    'url' => $url
                ));
            } else {
                $code = 501;
            }
        }
        return [$data, $code];
    }
}

(new Seimg);
