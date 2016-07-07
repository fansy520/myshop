<?php
header('Content-Type: text/html;charset=utf-8');
class demo extends Thread{
    public function run(){
        echo __METHOD__;
    }
}

$obj=new demo();
$obj->start();
