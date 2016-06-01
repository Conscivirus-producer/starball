<?php
use Think\Log;
vendor('SMTP');
vendor('PHPMailer');

function sendMailNewVersion($mailContent, $type, $userInfo){
	$mail = new \PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->CharSet = 'UTF-8';                             // Set CharSet to UTF-8
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.ym.163.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'test@starballkids.com';                 // SMTP username
	$mail->Password = 'test123456';                           // SMTP password
	$mail->Port = 25;                                     // TCP port to connect to

	$mail->setFrom('test@starballkids.com', 'StarballKids');
	$mail->addAddress($userInfo["email"], $userInfo["userName"]);     // Add a recipient
	//$mail->addAddress('ellen@example.com');               // Name is optional
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	if($type == "payment"){
		$mail->Subject = 'StarballKids支付成功通知-订单号'.$mailContent["orderNumber"];
		$template = '下单成功！</br>
				     订单号: '.$mailContent["orderNumber"].'</br>';
		for ($i=0; $i < count($mailContent["orderItems"]); $i++) {
			$template = $template."商品: ".$mailContent["orderItems"][$i]["itemName"]."价格: ".$mailContent["orderItems"][$i]["price"].$mailContent["currency"]."<br/>";
		}
		$template = $template.'总价: '.$mailContent["totalAmount"].$mailContent["currency"];
		$mail->Body    = $template;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	}elseif($type == "delivered"){
		$mail->Subject = 'StarballKids发货通知-订单号'.$mailContent["orderNumber"];
		$mail->Body    = "您购买的商品订单号".$mailContent["orderNumber"]."已发货, 快递公司为".$mailContent["expressName"].", 快递号为".$mailContent["expressNumber"].", 请您注意查收.";
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	}else if($type == 'itemSubscription'){
		$mail->Subject = 'StarballKids到货通知';
		$mail->Body    = '您关注的商品已经到货了';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';		
	}

	if(!$mail->send()) {
		//echo 'Message could not be sent.';
		logInfo('Mailer Error: ' . $mail->ErrorInfo);
		return false;
	} 
}
function logInfo($msg){
	LOG::write($msg, 'INFO');
} 

function logWarn($msg){
	LOG::write($msg, 'WARN');
} 

function get_client_time(){
   return date("Y-m-d H:i:s");
}

function expodeAndDistinctAgeArray($ages){
	$ageArray = array();
	foreach($ages as $ageSection){
		//age是有区间的,拆分age,然后把值distinct出来
		$ageSectionArray = array_splice(explode(',', $ageSection['age']),0,-1);
		foreach($ageSectionArray as $age){
			if($age != '' && !in_array($age, $ageArray)){
				array_push($ageArray, $age);
			}
		}
	}
	asort($ageArray);
	
	//把数字转化为描述
	$itemSize = C('ITEMSIZE');
	$ageDescriptionArray = array();
	foreach($ageArray as $age){
		//用a作为分隔符
		$tmp['age'] = $age.'a';
		$tmp['ageName'] =  getSizeDescriptionByAge($age);
		array_push($ageDescriptionArray, $tmp);
	}
	return $ageDescriptionArray;
}

function getSizeDescriptionByAge($age){
	$sizeArray = C('ITEMSIZE');
	if(strpos($age, ',') <= 0){
		return $sizeArray[$age][0].'  ('.$sizeArray[$age][1].' - '.$sizeArray[$age][2].'cm)';
	}else{
		$numberArray = explode(',', $age);
		$numberArray = array_splice($numberArray,0,-1);
		$startAge = current($numberArray);
		$endAge = end($numberArray);
		if($endAge == '24'){
			//如果是圴码
			return $sizeArray[$startAge][0];
		}else if($endAge == $startAge){
			return $sizeArray[$startAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)';
		}else{
			return $sizeArray[$startAge][0].'-'.$sizeArray[$endAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)';
		}
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

function isMobile()
{ 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
} 