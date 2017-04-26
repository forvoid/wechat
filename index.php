<?php
/**
 * Created by PhpStorm.
 * User: forvoid
 * Date: 3/29/2017
 * Time: 9:54 AM
 */
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else {
    $wechatObj->responseMsg();
}

class wechatCallBackapiTest
{
    public function vaild()
    {
        $echostr = $_GET["echostr"];
        if ($this -> checkSignature()) {
            echo $echostr;
            exit;
        }
    }
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestap = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestap, $nonce);
        sort($tmpArr);
        $tempStr = implode($tmpArr);
        $tempStr = sha1($tempStr);

        if ($tempStr == $signature){
            return true;
        }else{
            return false;
        }

    }
    public function responseMsg(){
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trime($postObj -> Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUerName><![CDATA[%s]]></ToUerName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if ($keyword == "?" || $keyword == "?")
            {
                $msgType = "text";
                $contenStr = date("Y-m-d : H:i:s", time());
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contenStr);
                echo $resultStr;
            }
        }else{
            echo "";
            exit;
        }
    }
}