# IAL-APIS

## List

- [agent](./src/agent/) 代理
- [background](./src/background/) 随机二刺猿背景图片(自定义)
- [core](./src/core/) 核心
- [hitokoto](./src/hitokoto/) HOTARU 随机一言(需数据库)
- [huimg](./src/huimg/) HOTARU 随机图片(需数据库)
- [qqavatar](./src/qqavatar/) QQ 头像图片代理
- [seimg](./src/seimg/) Pixiv 随机色图(需数据库)

## Core Config

```php
<?php
return [
    'callLimit' => [
        'cycle' => 2,
        'cycleNum' => 1,
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
