<?php

/**
 *  加盐    加密
 * @param string $password 原始密码
 * @param string $salt     盐
 * @return string
 */
function salt_string($password,$salt){
    return md5(md5($password) . $salt);
}

/**
 * 保存/获取用户session信息.
 * @param array $data
 * @return null|array
 */
function login($data = null){
    if($data){
        session('USER_INFO',$data);   //将登录信息保存到session中
    }else{
        return session('USER_INFO');
    }
}

/**
 * 阿里大鱼的短信验证格式
 * @param $telphone
 * @param $data
 * @param string $sign
 * @param string $tmplate
 * @return bool
 */
function sendSms($telphone,$data,$sign='当幸福来敲门',$tmplate='SMS_9696192'){
        //引入阿里大鱼的自动加载文件
        vendor('Alidayu.Autoloader');
        date_default_timezone_set('Asia/Shanghai');
        $c            = new \TopClient;
        $c->format='json';
        $c->appkey    = '23371344';  //ak
        $c->secretKey = '90105984ad9e30b95c61f73527b04865';  //sk
        $req          = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($sign);          //短信签名
//        $code = (string)mt_rand(1000, 9999);
        $req->setSmsParam(json_encode($data));    //验证码内容
        $req->setRecNum($telphone);
        $req->setSmsTemplateCode($tmplate);       //模板ID
        $resp         = $c->execute($req);
        if(isset($resp->result) && isset($resp->result->success)){
            $status = true;
        }else{
            $status = false;
        }
        return $status;
    }

/**
 * 发送邮件
 * @param array|string $address 收件人地址
 * @param string $subject 标题
 * @param string $content 内容
 * @return type
 */
function sendmail($address,$subject,$content){
    //获取配置
    $setting = C('EMAIL_SETTING');
    //加载自动载入类库
    vendor('PHPMailer.PHPMailerAutoload');
    //创建发送邮件的对象
    $mail = new \PHPMailer;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host       = $setting['host'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                               // Enable SMTP authentication
    $mail->Username   = $setting['username'];                 // SMTP username
    $mail->Password   = $setting['password'];                   // SMTP password
    $mail->SMTPSecure =$setting['SMTPSecure'];                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = $setting['port'];                       // TCP port to connect to

    $mail->setFrom($setting['username']);
    //如果是数组,就批量发送
    if(is_array($address)){
        foreach($address as $item){
            $mail->addAddress($item);     // Add a recipient
        }
    }else{
        $mail->addAddress($address);     // Add a recipient
    }

    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->CharSet = 'utf-8';
    $mail->Subject = $subject;
    $mail->Body    = $content;
    return $mail->send();
//    return $mail->send();
//    $mail->send();
//    dump($mail->ErrorInfo);
//    exit;
//    return ;

}


/**
 * 获取redis 数据
 * @staticvar type $redis$redis
 * @return \Redis
 */
function getRedis(){
    static $redis = null;
    if(!$redis instanceof Redis){
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
    }
    return $redis;
}

/**
 * 计算金额以小数点两位为准
 * @param number|string $number
 * @return string
 */
function money_format($number){
    return number_format($number,2,'.','');
}

/**
 * 循环输出下拉列表的方法
 * @param array $data   结果集
 * @param string $field_value  值字段名
 * @param string $field_name   提示文字字段名（可以理解为option的字段名的值）
 * @param $name  下拉列表的属性值，用于提交表单的
 * @return string
 */
function selects(array $data, $field_value, $field_name, $name, $selected_value = ''){
//    $html='<select name="brand_id">';
    $html='<select name="'.$name.'"class="'.$name.'">';
    $html.='<option value="">请选择...</option>';
    foreach($data as $val){
        if($selected_value==$val[$field_value]){
            $html.='<option value="'.$val[$field_value].'"selected="selected">'.$val[$field_name].'</option>';
        }else{
            $html.='<option value="'.$val[$field_value].'">'.$val[$field_name].'</option>';
        }
    }
    $html.='</select>';
    return $html;
}