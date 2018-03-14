<?php
namespace frontend\aliyun;
use yii\base\Component;

class SmsHandler extends Component{
    public $ak;
    public $template;
    public $sk;
    public $sign;
    private $_tel;
    private $_param;
    public function setTel($tel){
        $this->_tel = $tel;
        return $this;
    }
    public function setParam($params){
        $this->_param = $params;
        return $this;
    }
    public function  send(){
        $params = [
            'PhoneNumbers'=>$this->_tel,
            'SignName'=>$this->sign,
            'TemplateCode'=>$this->template,
            'TemplateParam'=>$this->_param,
        ];

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $this->ak,
            $this->sk,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );

        return ($content->Message=='OK' && $content->Code=='OK');
    }
}