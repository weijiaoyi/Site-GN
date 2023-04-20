<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
function onHttps() {
    if (defined('HTTPS') && HTTPS) return true;
    if (!isset($_SERVER)) return false;
    if (!isset($_SERVER['HTTPS'])) return false;
    if ($_SERVER['HTTPS'] === 1) {  //Apache
        return true;
    } elseif ($_SERVER['HTTPS'] === 'on') { //IIS
        return true;
    } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
        return true;
    }
    return false;
}
/**
 * 取得根域名
 * @param type $domain 域名
 * @return string 返回根域名
 */
function GetUrlToDomain($domain) {
    $re_domain = '';
    $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
    $array_domain = explode(".", $domain);
    $array_num = count($array_domain) - 1;
    if ($array_domain[$array_num] == 'cn') {
        if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
            $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        } else {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
    } else {
        $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
    }
    return $re_domain;
}
// [ 应用入口文件 ]
header("Content-type: text/html; charset=utf-8"); 

$HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
$DOMAIN = GetUrlToDomain($_SERVER['HTTP_HOST']);
if($_SERVER['HTTP_HOST'] != 'admin.'.$DOMAIN){
    if($HTTP_REFERER == "" ) 
    { 
        header("HTTP/1.1 302 Moved Permanently");
        header("Location:" . (onHttps() ? 'https://' : 'http://') . $DOMAIN);
        exit;
    }else{
        //指定来源域名
    }

    $arr = explode('.',$_SERVER['HTTP_HOST']);

    if(count($arr) < 3 || $arr[0] == 'www'){
        header("HTTP/1.1 302 Moved Permanently");
        header("Location:" . (onHttps() ? 'https://' : 'http://') . $DOMAIN);
        exit; 
    }

    if($arr[0] != 'dn' && $arr[0] != 'sj'){
        header("HTTP/1.1 302 Moved Permanently");
        header("Location:" . (onHttps() ? 'https://' : 'http://') . $DOMAIN);
        exit;
    }
}

//开启session
session_start();

// 定义应用目录
//die( __DIR__ . '/application/');
define('APP_PATH', __DIR__ . '/application/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';