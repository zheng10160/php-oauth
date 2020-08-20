<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/28
 * Time: 下午2:08
 */

namespace app\controllers\base;

use yii;
use yii\web\Controller;
use app\service\oauth_service;
class OauthBaseController extends Controller
{
    /**
     * 获取用户openid
     * @param $access_token
     */
    public function getOpenid($access_token)
    {
        $openid = oauth_service::getInstance()->getOpenid($access_token);

        if($openid)  show_json(0,'success',['openid'=>$openid]);

        show_json(100012,Yii::$app->params['errorCode'][100012]);
    }

    /**
     * 检测用户access_token 登陆是否有效
     * @param $access_token
     * @param $client_id
     * @return bool
     */
    public function isCheckLogin($access_token,$client_id)
    {
        /*return oauth_service::getInstance()->isCheckLogin($access_token,$client_id);*/

        if(oauth_service::getInstance()->isCheckLogin($access_token,$client_id)){
            show_json(0,'login effective');//登陆有效
        }
        show_json(100000,'login invalid');//登陆无效
    }

    /**
     * @param $access_token
     * @param $openid
     */
    public function getUserInfo($access_token,$openid)
    {
        $userInfo = oauth_service::getInstance()->getUserInfo($access_token,$openid);

        if(!$userInfo){

            show_json(100014,Yii::$app->params['errorCode'][100014]);

        }
        show_json(0,'success',$userInfo);
    }

    /**
     * 刷新access_token 从新获取登陆信息
     * 免登陆操作
     * @param $client_id
     * @param $client_secret
     * @param $refresh_token
     */
    public function getRefreshToken($client_id,$client_secret,$refresh_token)
    {
        oauth_service::getInstance()->getRefreshToken($client_id,$client_secret,$refresh_token);
    }

    /**
     * 退出操作
     * @param $access_token
     * @param $callback
     */
    public function loginOut($access_token,$callback)
    {
        oauth_service::getInstance()->loginOut($access_token,$callback);
    }

    /**
     * 根据appid 获取对应的模版
     * @param $appid
     * @return mixed
     */
    public function getViewTypeString($appid)
    {
        return oauth_service::getInstance()->getViewTypeString($appid);
    }


    /**
     * 验证用户是否正确
     * @param $username
     * @param $password &response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>
     */
    protected function verifyUserinfo($username,$password,$client_id)
    {
         oauth_service::getInstance()->verifyUserinfo($username,$password,$client_id);
    }

    /**
     * 返回session存储的用户信息
     * @return string
     */
    protected function getUserinfoFromSession()
    {
        return isset($_SESSION['userinfo'])?$_SESSION['userinfo']:show_json(100010,Yii::$app->params['errorCode'][100010]);
    }
}