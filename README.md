# IAL-APIS

## List

- [agent](./agent/) 代理
- [background](./background/) 随机二刺猿背景图片(自定义)
- [core](./core/) 核心
- [getbilianime](./getbilianime/) 获取 Bilibili 用户番剧信息,参考[biyuehu/getbilianime](https://github.com/BIYUEHU/getBiliAnime)
- [hitokoto](./hitokoto/) HOTARU 随机一言(需数据库)
- [huimg](./huimg/) HOTARU 随机图片(需数据库)
- [seimg](./seimg/) Pixiv 随机色图(需数据库)

## Core Config

```php
<?php
return [
    'callLimit' => [
        'cycle' => 20,
        'cycleNum' => 6,
        'refuseMsg' => 'Bad request'
    ],
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'dbName' => '',
        'userName' => '',
        'passWord' => ''
    ]
];
```
