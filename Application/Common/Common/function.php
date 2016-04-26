<?php
use Think\Log;
vendor('SMTP');
vendor('PHPMailer');

function sendMail($mailContent, $type){
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
	$mail->addAddress('509146778@qq.com', '梁潇');     // Add a recipient
	//$mail->addAddress('ellen@example.com');               // Name is optional
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');
	
	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML
	
	if($type="payment"){
		$mail->Subject = 'Here is the subject';
		$template = '下单成功！</br>
				     订单号: '.$mailContent["orderNumber"].'</br>';
		for ($i=0; $i < count($mailContent["orderItems"]); $i++) { 
			$template = $template."商品: ".$mailContent["orderItems"][$i]["itemName"]."价格: ".$mailContent["orderItems"][$i]["price"].$mailContent["currency"]."<br/>";
		}
		$template = $template.'总价: '.$mailContent["totalAmount"].$mailContent["currency"];	
	    $mail->Body    = $template;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	}elseif($type="delivered"){
		//TODO
	}
	
	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    logInfo(json_encode($mailContent));
		logInfo($type);
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
		$startAge = current(explode(',', $age));
		$endAge = end(explode(',', $age));
		return $sizeArray[$startAge][0].'-'.$sizeArray[$endAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)';
	}
}

function getSizeDescriptionAndPriceByAge($age, $price, $currency){
	$sizeArray = C('ITEMSIZE');
	if(strpos($age, ',') <= 0){
		return $sizeArray[$age][0].'  ('.$sizeArray[$age][1].' - '.$sizeArray[$age][2].'cm)'.' - '.$currency.' '.$price;
	}else{
		$startAge = current(explode(',', $age));
		$endAge = end(explode(',', $age));
		return $sizeArray[$startAge][0].'-'.$sizeArray[$endAge][0].'  ('.$sizeArray[$startAge][1].' - '.$sizeArray[$endAge][2].'cm)'.' - '.$currency.' '.$price;
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