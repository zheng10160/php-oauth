<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/2/26
 * Time: 上午10:39
 */

namespace app\controllers;

use Yii;
use app\models\Oauth2;
use common\util\IFilter;
use common\util\IReq;
class InternalapiController
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
    public function actionCheckUserInfo()
    {

        try{
            $access_token = IFilter::act(IReq::get('access_token'),'string',50);

            $client_id = IFilter::act(IReq::get('client_id'),'string',32);

            if(!$client_id){
                show_json(100006,Yii::$app->params['errorCode'][100006]);
            }

            if(!$access_token){
                show_json(100011,Yii::$app->params['errorCode'][100011]);
            }


            $data = $this->getMsgUserInfo($access_token,$client_id);

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