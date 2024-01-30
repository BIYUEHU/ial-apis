<?php

header('Content-type: application/json');
header('Access-Control-Allow-origin: *');
echo file_get_contents("http://api.bilibili.com/x/space/bangumi/follow/list?type=1&ps={$_REQUEST['limit']}&pn={$_REQUEST['page']}&vmid={$_REQUEST['uid']}");