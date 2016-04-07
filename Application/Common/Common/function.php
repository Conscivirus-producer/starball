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

function getSizeDescriptionByAge($age){
	$sizeArray = C('ITEMSIZE');
	if(strpos($age, ',') <= 0){
		return $sizeArray[$age][0].'  ('.$sizeArray[$age][1].' - '.$sizeArray[$age][2].'cm)';
	}else{
		$startAge = current(explode(',', $age));
		$endAge = end(explode(',', $age));
		return $sizeArray[$startAge][0].'-'.$sizeArray[$endAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)';
	}
}

function array_customized_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
    if(is_array($arrays)){   
        foreach ($arrays as $array){   
            if(is_array($array)){   
                $key_arrays[] = $array[$sort_key];   
            }else{   
                return false;   
            }   
        }   
    }else{   
        return false;   
    } 
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
    return $arrays;   
} 