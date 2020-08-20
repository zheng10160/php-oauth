<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/1/9
 * Time: 上午11:09
 */

namespace app\models;

use Yii;
class Oauth2 extends \yii\db\ActiveRecord
{
    /**
     * mmm
     * 创建code 成功后 系统自动创建openid 对应userid 的关系表
     * @param $userid
     * @param $nickname
     * @param $sex
     * @param $avatar
     */
    public function setTposInfoFromDb($userid,$nickname,$sex=0,$avatar='',$client_id)
    {
        $http_avatar_md5_url = Yii::$app->params['account_senseplay_avatar_md5_url'].'/'.md5($userid);//头像加密后动态地址

      //  $http_nickname_md5_url = Yii::$app->params['account_senseplay_nickname_md5_url'].'/'.md5($userid);//昵称加密后动态地址

       /* if($nickname){
            $mn = $nickname;
            $nickname = filterData($nickname,'string',128);
        }*/
        $userinfo = $this->getOpenidFromDb_userid2($userid,$client_id);//db 获取openid  当用户失效 再次等路会刷新redis 缓存信息

        //先查询关系表是否已经有数据存在  存在不需要新建openid
        if($userinfo){
            //账户存在直接跳过 更新表
            $status_msg = 'update';
            //$this->UpOauthUserInfo($userid,$nickname,$sex,$avatar,$client_id);
            $this->UpOauthUserInfo($userid,filterData($nickname,'string',128),$sex,$avatar,$client_id);

            $this->setUserinfoToRedis($userid,['userid'=>$userid,'nickname'=>$nickname,'openid'=>$userinfo['openid'],'client_id'=>$client_id,'sex'=>$userinfo['sex'],'avatar'=>$http_avatar_md5_url,'create_time'=>$userinfo['create_time']]);//更新redis

        }else{
            //添加新的用户关系信息
            $status_msg = 'add';
            $this->addOauthUserInfo($userid,$nickname,$sex,$avatar,$client_id,$http_avatar_md5_url);

        }

        $this->setOauthUuid($userid);//设置全局的uuid 缓存redis

        //var_log([$userid,$nickname,$sex,$avatar,$status_msg],'oauth-com');//公用log

    }

    /**
     * mm
     * 当登陆成功之后需要 将uuid 存入redis uuid于userid具有相同的意义 都是唯一
     * uuid 与硬件绑定有关联 对硬件而言就是openid
     * @param $userid
     */
    public function setOauthUuid($userid)
    {
        $redis = Yii::$app->redis;

        $key = 'oauth_uuid_info:'.$userid;
        $data = $redis->get($key);


        if(!$data){
            //redis 不存在 重db获取
            $data = $this->getUuidInfo($userid);

            if(!$data){
                show_json(100000,'gain uuid error');
            }

            $redis->set($key,json_encode($data));

        }else{
            return json_decode($data,true);
        }

        return $data;
    }

    /**
     * mm
     * 将登陆的oauth的用户信息 存储redis
     * @param $userid
     * @param $userinfo
     */
    public function setUserinfoToRedis($userid,$userinfo)
    {
        $redis = Yii::$app->redis;

        $key = 'oauth_login_userinfo:'.$userid;
        $redis->set($key,json_encode($userinfo));//缓存redis
    }

    /**
     * 获取用户与oauth的等路信息
     * @param $userid
     * @return mixed
     */
    public function getUserinfoToRedis($userid)
    {
        $redis = Yii::$app->redis;

        $key = 'oauth_login_userinfo:'.$userid;
        return $redis->get($key);//缓存redis

    }
    /**
     * 根据客户端输入 appid 与appsecret 判断是否合法 合法 返回 appid 与appsecret
     * @param $APP_KEY
     * @param $APP_SECRET
     * @return array|bool|false
     */
    public function getAppInfoConfig($APP_KEY)
    {
        $spname = 'auth_sp_select_appinfo';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$APP_KEY."');")->queryOne();

        if($res){
            return $res;
        }
        return false;
    }

    /**
     * 数据库
     * 根据userid 获取openid
     * @param $userid
     * @return string
     */
    public function getOpenidFromDb_userid($userid,$client_id)
    {
        $spname = 'pst_sp_select_oauth_login_user_to_userid';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$userid.",'".$client_id."');")->queryOne();

        if($res){
            return $res['openid'];//返回用户openid
        }

        return false;


        //userid get userifo error
        //throw new \Exception('userid get user info error');
    }

    /**
     * 数据库
     * 根据userid 获取openid
     * @param $userid
     * @return string
     */
    public function getOpenidFromDb_userid2($userid,$client_id)
    {
        $spname = 'pst_sp_select_oauth_login_user_to_userid';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$userid.",'".$client_id."');")->queryOne();

        if($res){
            return $res;//返回用户x消息
        }

        return false;
    }

    /**
     * 根据openid 获取db存储的用户信息
     * @param $openid
     * @return array|bool|false
     */
    public function getOauthLoginUserInfo($openid,$client_id)
    {
        $spname = 'pst_sp_select_oauth_login_user_to_openid';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$openid."','".$client_id."');")->queryOne();

        if($res){
            return $res;
        }

        return false;
    }


    /**
     * 刷新refresh token 时 需要重新获取用户信息
     * @param $userid
     * @return array|bool|false
     */
    public function getUserInfoToRefreshToken($userid)
    {
        $spname = 'pst_sp_by_userid_to_userinfo_select';
        $u = Yii::$app->db->createCommand("call ".$spname."(".$userid.");")->queryOne();

        if($u){
            return $u;
        }

        return false;
    }


    /**_getPstUserInfo
     * @param $userid
     * @return array|bool|false
     */
    public function getPstUserInfo($userid)
    {
        $spname = 'pst_sp_by_userid_to_userinfo_select';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$userid.");")->queryOne();

        if($res){
            $userinfo = ['nickname'=>$res['nickname'],'userid'=>$res['userid']];
            return $userinfo;//目前只返回用户昵称
        }
        return [];
    }

    /**
     * //创建code 成功后 系统自动创建openid 对应userid 的关系表
     * 授权成功 用户存在更新用户信息
     * @param $userid
     * @param $nickname
     * @param $sex
     * @param $avatar
     */
    public function UpOauthUserInfo($userid,$nickname,$sex,$avatar,$client_id)
    {
        //账户存在直接跳过 更新表
        Yii::$app->db->createCommand("call pst_sp_up_oauth_login_user_userid("
            .$userid.",'"
            .$client_id."','"
            .$nickname."',"
            .$sex.",'"
            .$avatar.
            "',@ret)")->query();
        $res = Yii::$app->db->createCommand("select @ret")->queryOne();

        //error write log
        $data = [
            'userid'=>$userid,
            'nickname'=>$nickname,
            'sex'=>$sex,
            'avatar'=>$avatar
        ];

        if(isset($res['@ret']) && intval($res['@ret']) == 1){
            return true;
        }
        var_log($data,'oauth-Authorize');
        return false;
    }

    /**
     *创建code 成功后 系统自动创建openid 对应userid 的关系表
     * 授权成功 建立用户与平台的关系
     * @param $userid
     * @param $nickname
     * @param $sex
     * @param $avatar
     */
   /* public function addOauthUserInfo($userid,$nickname,$sex,$avatar,$client_id)
    {
        $openid = createGuid();// create openid sprintf('%02s',$val['TYPE']['TRANSPARENT']['USA']);
       // $openid = str_pad($userid,32,0,STR_PAD_RIGHT);
        $created_ts = time();
        Yii::$app->db->createCommand("call pst_sp_add_oauth_login_user("
            .$userid.",'"
            .$nickname."','"
            .$openid."','"
            .$client_id."',"
            .$sex.",'"
            .$avatar."','"
            .$created_ts.
            "',@ret)")->query();

        $res = Yii::$app->db->createCommand("select @ret")->queryOne();;


        //error write log
        $data = [
            'userid'=>$userid,
            'nickname'=>$nickname,
            'openid' =>$openid,
            'client_id'=>$client_id,
            'sex'=>$sex,
            'avatar'=>$avatar,
            'create_time'=>$created_ts
        ];

        if(isset($res['@ret']) && intval($res['@ret']) == 1){
            $this->setUserinfoToRedis($userid,$data);//更新redis
            return true;
        }
        var_log($data,'oauth-Authorize');
        return false;
    }*/
    public function addOauthUserInfo($userid,$nickname,$sex,$avatar,$client_id,$http_avatar_md5_url)
    {
        $openid = createGuid();// create openid sprintf('%02s',$val['TYPE']['TRANSPARENT']['USA']);
        // $openid = str_pad($userid,32,0,STR_PAD_RIGHT);
        $created_ts = time();
        Yii::$app->db->createCommand("call pst_sp_add_oauth_login_user("
            .$userid.",'"
            .filterData($nickname,'string',128)."','"
            .$openid."','"
            .$client_id."',"
            .$sex.",'"
            .$avatar."','"
            .$created_ts.
            "',@ret)")->query();

        $res = Yii::$app->db->createCommand("select @ret")->queryOne();;


        //error write log
        $data = [
            'userid'=>$userid,
            /*'nickname'=>$nickname,*/
            'nickname'=>$nickname,
            'openid' =>$openid,
            'client_id'=>$client_id,
            'sex'=>$sex,
            'avatar'=>$http_avatar_md5_url,
            'create_time'=>$created_ts
        ];

        if(isset($res['@ret']) && intval($res['@ret']) == 1){
            $this->setUserinfoToRedis($userid,$data);//更新redis
            return true;
        }
        var_log($data,'oauth-Authorize');
        return false;
    }

    /**
     * 根据用户的userid 获取唯一的uuid 只有硬件才使用uuid
     * @param $userid
     * @return array|bool|false
     */
    protected function getUuidInfo($userid)
    {
        $spname = 'pst_sp_uuid_info_select_one';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$userid.");")->queryOne();

        if($res){
            return $res;//目前只返回用户昵称
        }
        return false;
    }
}