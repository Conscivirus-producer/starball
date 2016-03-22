<?php
use Think\Log;
function logInfo($msg){
	LOG::write($msg, 'INFO');
} 

function logWarn($msg){
	LOG::write($msg, 'WARN');
} 

function get_client_time(){
   return date("Y-m-d H:i:s");
}