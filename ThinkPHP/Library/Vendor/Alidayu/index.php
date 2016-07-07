<?php 
include "TopSdk.php"; 
date_default_timezone_set('Asia/Shanghai'); 
//$c = new TopClient;
//$c->appkey = '23371344';
//$c->secretKey = '90105984ad9e30b95c61f73527b04865';
//$req = new AlibabaAliqinFcSmsNumSendRequest;
//$req->setSmsType("normal");
//$req->setSmsFreeSignName("当幸福来敲门");
//$req->setSmsParam("{code:'$code',product:'$product'}");
//$req->setRecNum("18780125305");
//$req->setSmsTemplateCode("SMS_9696192");
//$resp = $c->execute($req);
//var_dump($resp);

$c = new TopClient;
$c->appkey = '23371344';
$c->secretKey = '90105984ad9e30b95c61f73527b04865';
$req = new AlibabaAliqinFcSmsNumSendRequest;
$req->setSmsType("normal");
$req->setSmsFreeSignName("当幸福来敲门");
$req->setSmsParam("{'code':'$code','product':'霸道总裁'}");
$req->setRecNum("18780125305");
$req->setSmsTemplateCode("SMS_9696192");
$resp = $c->execute($req);
var_dump($resp);