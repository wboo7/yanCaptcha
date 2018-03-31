<?php
/**
 * @link http://www.yanphp.com/
 * @copyright Copyright (c) 2016 YANPHP Software LLC
 * @license http://www.yanphp.com/license/
 */

//.根据自身情况引入，建议写在入口文件中
include_once __DIR__.'/../../../yan/Yan.php';

use yan\plugins\captcha\YanCaptcha;

$yanCaptcha = new YanCaptcha(100012,'2e9e56817877630b9e36aace7fc051ec','http://api.yanphp.com/dun/v1/verify');
$result = $yanCaptcha->verify($_POST['validate']);

//.验证成功后发送手机短信
if($result['status'] == 'success')
{
    //.发送手机短信，业务自行处理
    if(true)
    {
        echo "success";
    }
}
else
{
    echo "fail";
}


