<?php
define('API_URL', 'http://admin.821712.com/99/index/api/');
while(true){
	$t = time();
    if($t % 3 == 0){
        file_get_contents(API_URL . 'getdate');
        file_get_contents(API_URL . 'order');
        file_get_contents(API_URL . 'allotorder');
    }
    if($t % 30 == 0){
        file_get_contents(API_URL . 'checkbal');
    }
    if($t % 60 == 0){
        file_get_contents(API_URL . 'interest');
    }
    sleep(1);
}