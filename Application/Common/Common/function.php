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

	$mail->setFrom('test@starballkids.com', 'test');
	$mail->addAddress($userInfo["email"], $userInfo["userName"]);     // Add a recipient
	//$mail->addAddress('ellen@example.com');               // Name is optional
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	if($type == "payment"){
		$mail->Subject = 'Here is the subject';
		$template = '下单成功！</br>
				     订单号: '.$mailContent["orderNumber"].'</br>';
		for ($i=0; $i < count($mailContent["orderItems"]); $i++) {
			$template = $template."商品: ".$mailContent["orderItems"][$i]["itemName"]."价格: ".$mailContent["orderItems"][$i]["price"].$mailContent["currency"]."<br/>";
		}
		$template = $template.'总价: '.$mailContent["totalAmount"].$mailContent["currency"];
		$mail->Body    = $template;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	}elseif($type == "delivered"){
		$mail->Subject = 'StarballKids发货通知';
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