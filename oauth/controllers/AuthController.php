<?php
/**
 * 仅供php 使用
 * 本类采用yar rpc模式
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/8
 * Time: 下午2:51
 */

namespace app\controllers;

use Yii;
use app\models\Oauth2;
class AuthController
{
    public function init()
    {
        $this->enableCsrfValidation = false;

    }
    /**
     * 验证登陆的用户token是否失效
     * @param $client_id $arg[0] 系统办法的appid
     * @param $access_token $arg[1] 用户登陆成功后的唯一值 默认缓存一个月
     */
    public function checkUserInfo()
    {

        try{

            $arg = func_get_args();//[0] client_id  [1] access_token

            if(!isset($arg[0])) show_json(100006,Yii::$app->params['errorCode'][100006]);

            if(!isset($arg[1])) show_json(100000,'Missing token parameter');

            $data = $this->getMsgUserInfo($arg[1],$arg[0]);

            return ['code'=>0,'message'=>'success','data'=>$data];
        }catch (\Exception $e){
            return ['code'=>100000,'message'=>$e->getMessage(),'data'=>''];
        }

    }

    /**
     * 根据access_token获取用户参数信息
     * @param $access_token
     */
    private function getMsgUserInfo($access_token,$client_id)
    {
        $redis = Yii::$app->redis;

        $key = 'oauth_access_tokens:'.$access_token;

        $act = json_decode($redis->get($key),true);

        if($act){

            //验证client_id 是否合法
            if(isset($act['client_id']) && $act['client_id']  != $client_id){
                throw new \Exception('The request parameter client id is not valid');//请求数据不合法
            }

            $Oauth2 = new Oauth2();
            //目前直接在db获取 后面会增加缓存

            $openid = $Oauth2->getOpenidFromDb_userid($act['user_id'],$client_id);//mysql_list_dbs resource mysql_list_dbs openid

            if(!$openid){
                throw new \Exception('Unauthorized login');//用户信息存在 但未授权登陆过
            }

            $uuidInfo = $Oauth2->setOauthUuid($act['user_id']);//获取uuid
            //获取redis用户缓存信息
            $u_key = 'oauth_login_userinfo:'.$act['user_id'];
            $userinfo = json_decode($redis->get($u_key),true);//openid get userinfo

            if(!$userinfo){
                //redis 不存在 重db获取数据
                $userinfo = $Oauth2->getOauthLoginUserInfo($openid,$client_id);

                if(!$userinfo)  throw new \Exception(Yii::$app->params['errorCode'][100014]);
            }

            $userinfo['uuid'] = $uuidInfo['uuid'];
            return array_merge($act,$userinfo);//返回用户基本信息
        }

        throw new \Exception(Yii::$app->params['errorCode'][100012]);
    }
}


