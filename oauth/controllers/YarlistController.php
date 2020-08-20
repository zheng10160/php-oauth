<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/10
 * Time: 下午3:45
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

//因为该类是rpc模式调用 需要提前加载
use app\controllers\AuthtController;
class YarlistController extends Controller
{
    public static $timeStampOut = 300;//请求超时时间

    public function init()
    {
        $this->enableCsrfValidation = false;

    }

    /**
     * 验证请求 是否合法
     * @param $data
     */
    protected function vCheckData()
    {
        $requestData = Yii::$app->request->get();

        isset($requestData['timeStamp'])?$requestData['timeStamp']:show_json(100000,'Missing interface parameters');//缺少接口参数
        isset($requestData['dataStr'])?$requestData['dataStr']:show_json(100000,'Missing interface parameters');//缺少接口参数
        if($requestData['timeStamp']+self::$timeStampOut < time()){
            throw new \Exception('Interface request timeout');//接口请求超时
        }
        if(!self::verifySign($requestData)){
            throw new \Exception('The request parameter is not valid. Please check the parameter');//请求参数不合法,请核对参数
        }
        return self::decodeDataStr($requestData['dataStr']);
    }
    /**
     * 用户检查token是否有效的接口，如果用户token有效返回用户基本数据信息
     */
    public function actionAuth()
    {
        try{
            $data = $this->vCheckData();

            $service = new \Yar_Server(new $data['class']());//注意只要实例化的类 都需要提前加载进来
            $service->handle();
        }catch (\Exception $e){
            return ['code'=>100000,'message'=>$e->getMessage(),'data'=>''];

        }

    }


    /*********************************************加密验证的一些规则*******************************************/

    public static function encodeDataStr ( array $data)
    {
        return base64_encode(json_encode($data));
    }
    public static function decodeDataStr ($data)
    {
        return json_decode(base64_decode($data),true);
    }
    public static function createSign(array $data)
    {
        $stringA=implode('&',$data);
        $stringSignTemp=$stringA."&secretKey=".Yii::$app->params['rpc']['secretKey'];
        $sign=strtoupper(hash_hmac("sha256",$stringSignTemp,Yii::$app->params['rpc']['secretKey']));
        return $sign;
    }
    public static function verifySign(array $data)
    {
        $srcData = $data;
        unset($srcData['sign']);
        $srcData = [
            'dataStr='.$srcData['dataStr'],
            'timeStamp='.$srcData['timeStamp'],
            'nonceStr='.$srcData['nonceStr'],
        ];
        $sign = self::createSign($srcData);
        return ($sign==$data['sign'])?true:false;
    }
    public static function getRandStr()
    {
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        return substr(str_shuffle($str),10,16);
    }
}