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
	for ($i = 0; $i < count($userInfo); $i++) {
		$mail->addAddress($userInfo[$i]["email"], $userInfo[$i]["userName"]);     // Add a recipient
	}
	//$mail->addAddress('ellen@example.com');               // Name is optional
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	if($type == "payment"){
		/*$address = D('ShippingAddress', 'Logic')->getDefaultAddress($mailContent['userId']);
		$address = parseAddressCode($address);
		$mail->Subject = 'StarballKids支付成功通知-订单号'.$mailContent["orderNumber"];
		$template = '尊敬的'.$userInfo["userName"].':</br>
				     非常感谢您对StarBall.Kids的支持，您的订单下单时间为'.date('y-m-d H:i:s',time()).'，您的订单号码为'.$mailContent["orderNumber"].'。</br>
				     我们正在打包您的包裹。当您的包裹开始邮寄时，您将会收到另一封邮件，包含您的包裹追踪号码。您可以登录快递公司官方网站，输入您的包裹追踪号码进而跟踪您的商品。</br>
				     您的订单详情：</br>';
		for ($i=0; $i < count($mailContent["orderItems"]); $i++) {
			$template = $template."商品: ".$mailContent["orderItems"][$i]["itemName"].",价格: ".$mailContent["orderItems"][$i]["price"].$mailContent["currency"]."<br/>";
		}
		$template = $template.'总价: '.$mailContent["totalAmount"].$mailContent["currency"];
		
		$template = $template.'</br>您提供的收货地址：</br>'.$address['address'];
		if($address['city'] != ''){
			$template = $template.','.$address['city'];
		}
		if($address['province'] != ''){
			$template = $template.','.$address['province'];
		}
		$template = $template.','.$address['country'].'</br>';

		// 添加logo图片
		$template = $template.'StarBall.Kids是一家来自香港的婴幼儿品牌集合店，主营进口婴幼儿童服装，这里有世界各地的大牌潮牌衣服供您选择。</br>';
		// 添加另外一张图
		$template = $template.'www.starballkids.com';
		$mail->Body    = $template;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';*/
		// XXXXXX修改为相应的变量
		$template = "";
		$template = $template."<p>尊敬的"."XXX".":</p>";
		$template = $template."<p>非常感谢您对StarBall.Kids的支持，您的订单下单时间为"."XXXXXX"."，您的订单号码为"."XXXXXX"."。</p>";
		$template = $template."<p>我们正在打包您的包裹。当您的包裹开始邮寄时，您将会收到另一封邮件，包含您的包裹追踪号码。您可以登录快递公司官方网站，输入您的包裹追踪号码进而跟踪您的商品。</p>";
		$template = $template."<p>您的订单详情: </p>";
		$tableContent = "";
		$tableContent = $tableContent."<table width='600' cellpadding='0' cellspacing='0' style='border: 1px #F2F2F2 solid; background-color: #F8F8F8'><tbody><tr><td>描述</td><td>数量</td>><td>尺寸</td><td>价钱</td></tr><tr><td>XXX</td>><td>XXX</td>><td>XXX</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>XXX</td>><td>XXX</td>><td>XXX</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>运费</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>总优惠金额</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>信用票据总额</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>总金额</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."</tbody></table>";
		$template = $template.$tableContent;
		$template = $template."<p>您提供的收货地址：</p>";
		$template = $template."<p>XXXXXX<br>XXXXXX<br></p>";
		$template = $template."<p><img src='http://7xr7p7.com2.z0.glb.qiniucdn.com/1660857294.jpg' width='80' height='51'></p>";
		$template = $template."<p>StarBall.Kids是一家来自香港的婴幼儿品牌集合店，主营进口婴幼儿童服装，这里有世界各地的大牌潮牌衣服供您选择。</p>";
		$template = $template."<p>联系我们：邮件＋电话（稍后提供）</p>";
		$mail->Subject = 'StarballKids支付成功通知-订单号'."XXXXXXXX";
		$mail->Body = $template;
	}elseif($type == "delivered"){
		$mail->Subject = 'StarballKids发货通知-订单号'."XXXXXXXXX";
		$template = "";
		$template = $template."<p>尊敬的"."XXX".":</p>";
		$template = $template."<p>很高兴的通知您，您的订单XXXX已经发货了。</p>";
		$template = $template."<p>您的订单详情：</p>";
		$tableContent = "";
		$tableContent = $tableContent."<table width='600' cellpadding='0' cellspacing='0' style='border: 1px #F2F2F2 solid; background-color: #F8F8F8'><tbody><tr><td>描述</td><td>数量</td>><td>尺寸</td><td>价钱</td></tr><tr><td>XXX</td>><td>XXX</td>><td>XXX</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>XXX</td>><td>XXX</td>><td>XXX</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>运费</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>总优惠金额</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>信用票据总额</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."<tr><td>总金额</td>><td>-</td>><td>-</td><td>XXX</td></tr>";
		$tableContent = $tableContent."</tbody></table>";
		$template = $template.$tableContent;
		$template = $template."<p>您可以登录快递公司的官方网站，输入您的包裹追踪号码来跟踪您的商品。</p>";
		$template = $template."<p>您的快递公司：XXX</p>";
		$template = $template."<p>您的包裹追踪号码：XXXXX。</p>";
		$template = $template."<p>您的收货地址：</p>";
		$template = $template."<p>XXXXXX<br>XXXXXX<br></p>";
		$template = $template."<p>有关于商品退换货事宜，请查看官网 www.starballkids.com 主页下方的退换政策了解详情。或联系我们的客服微信 starballkidshk. 我们会在第一时间给您回复并处理相关事宜。</p>";
		$template = $template."<p>非常感谢您选择StarBall.Kids，相信是一次愉快的购物体验。希望很快再见到您，谢谢光临。</p>";
		$template = $template."<p><img src='http://7xr7p7.com2.z0.glb.qiniucdn.com/1660857294.jpg' width='80' height='51'></p>";
		$template = $template."<p>StarBall.Kids是一家来自香港的婴幼儿品牌集合店，主营进口婴幼儿童服装，这里有世界各地的大牌潮牌衣服供您选择。</p>";
		$template = $template."<p>联系我们：邮件＋电话（稍后提供）</p>";

		$mail->Body = $template;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	}else if($type == 'itemSubscription'){
		$mail->Subject = 'StarballKids到货通知';
		$template = "";
		$template = $template."<p>尊敬的顾客：</p>";
		$template = $template."<p>很高兴的通知您，您喜爱的商品 "."XXX"."高领毛衣 现货已登陆StarBall.Kids官方网站。库存有限，立即行动吧。</p>";
		$template = $template."<p>点击下方链接进行购买</p>";
		$template = $template."<p>http://www.starballkids.com/Starball/Item/index/itemId/115.html</p>";
		$template = $template."<p>我们的官方网站 www.starballkids.com 还有更多选择，欢迎浏览购买，相信会是一次愉快的购物体验。感谢您对StarBall.Kids的支持。</p>";
		$template = $template."<p><img src='http://7xr7p7.com2.z0.glb.qiniucdn.com/1660857294.jpg' width='80' height='51'></p>";
		$template = $template."<p>StarBall.Kids是一家来自香港的婴幼儿品牌集合店，主营进口婴幼儿童服装，这里有世界各地的大牌潮牌衣服供您选择。</p>";
		$mail->Body    = $template;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';		
	}else if($type == 'notifyMyself'){
		$mail->Subject = 'StarballKids下单通知';
		$template = "";
		$template = $template."<p>用户XXXXX下单成功!</p>";
		$template = $template."<p>订单号:XXXXXXXXX</p>";
		$template = $template."<p>商品:XXXXXXXXX,单价:XXXXXXXX</p>";
		$template = $template."<p>总价:XXXXXXXX</p>";
		$mail->Body    = $template;
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

function parseAddressCode($address){
	$provinceList = C('CHINA_PROVINCE_LIST');
	$countryList = C('COUNTRY_LIST');
	if($address['country'] != ''){
		$address['country'] = L($countryList[$address['country']]);
	}
	if($address['province'] != ''){
		$address['province'] = current($provinceList[$address['province']]);
	}
	return $address;
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