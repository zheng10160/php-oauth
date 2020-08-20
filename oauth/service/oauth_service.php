<?php
namespace app\service;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/28
 * Time: 上午10:21
 */
use app\api_interface\oauth\oauth_interface;
use OAuth2\Storage\Redis;
use yii;
use OAuth2\Server;
use app\models\Oauth2;
use app\models\PstUser;
class oauth_service implements oauth_interface
{

    public static $_instance;

    public $storage_redis;//redis 句柄

    public $server_redis;

    protected static $appInfo_key = 'oauth_clients:';//appinfo 存储redis的键值

    public function __construct()
    {
        _initSess();//开启session

        $this->storage_redis = new Redis(Yii::$app->redis);
        $this->server_redis = new Server($this->storage_redis);
    }

    //初始化该类
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();//当前配置文件
        }
        return self::$_instance;
    }

    /**
     * 获取openid
     * @param 用户登陆系统办法的唯一凭证 $access_token
     * @return bool|string
     */
    public function getOpenid($access_token)
    {
        $act = $this->storage_redis->getAccessToken($access_token);

        if ($act) {
            $Oauth2 = new Oauth2();
            //目前直接在db获取 后面会增加缓存

            $OauthUserinfo = $Oauth2->getUserinfoToRedis($act['user_id']);

            if($OauthUserinfo){
                $openid = json_decode($OauthUserinfo,true)['openid'];
            }else{
                $openid = $Oauth2->getOpenidFromDb_userid($act['user_id'],$act['client_id']);//mysql_list_dbs resource mysql_list_dbs openid
            }

            if ($openid) {
                return $openid;
            }
        }

        return false;
    }

    /**
     * 检测用户是否在登陆状态 access_token是否失效
     * @param $access_token
     * @param $client_id
     * @return bool
     */
    public function isCheckLogin($access_token,$client_id){

        /*$act = json_decode($this->storage_redis->getAccessToken($access_token),true);*/
        $act = $this->storage_redis->getAccessToken($access_token);

        if($act && $act['client_id'] == $client_id){
            return true;
        }

        return false;
    }

    /**
     * 根据appid 获取对应的模版
     * @param $appid
     * @return mixed
     */
    public function getViewTypeString($appid)
    {
        $data = [];
        if(!$appid){
            show_json(100006,Yii::$app->params['errorCode'][100006]);
        }

        $data['APP_ID'] = $appid;
        $appinfo = $this->initThirdLoginApps($data);

        return isset($appinfo['viewType'])?Yii::$app->params['viewType'][$appinfo['viewType']]:Yii::$app->params['viewType'][1];//默认使用官方

    }

    /**
     * 根据用户的openid 获取用户信息
     * @param 用户登陆的唯一凭证 $access_token
     * @param 用户的openid $openid
     * @return bool|mixed
     */
    public function getUserInfo($access_token,$openid){

        $act = $this->storage_redis->getAccessToken($access_token);

        if(!$act){
            show_json(100000,'access_token invalid');//access_token 无效
        }

        //获取redis用户缓存信息
        $userinfo = $this->getUserInfoFromRedis($act['user_id']);

        if(!$userinfo){
             return false;
        }

        return $userinfo;
    }

    /**
     * 根据用户名识别是邮箱还是手机登陆
     * @param $username
     * @return int
     */
    protected function getRegtypeInt($username)
    {
        $pattern = Yii::$app->params['preg_match']['email'];//邮箱
        $pattern1 = Yii::$app->params['preg_match']['mobile'];//手机

        if(preg_match( $pattern, $username )){

            return 2;

        }elseif(preg_match( $pattern1, $username )){

            return 3;

        }else{
            //默认用户名
            return 1;
        }
    }


    /**
     * 验证用户是否正确
     * @param $username
     * @param $password &response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>
     */
    public function verifyUserinfo($username,$password,$client_id)
    {
        $pattern = Yii::$app->params['preg_match']['email'];//邮箱
        $pattern1 = Yii::$app->params['preg_match']['mobile'];//手机

        //todo 需要加登陆次数过多的 限制ip操作 防止暴力破解
        if (preg_match($pattern, $username) || preg_match($pattern1, $username)) {
            //成功处理
            //判断用户是否存在
            $PstUser = new PstUser();

            $regtype = $this->getRegtypeInt($username);

            $userinfo = $PstUser->getCheckUserInfo($username, $password, $regtype);

            if ($userinfo) {

                $u1 = ['nickname' => $userinfo['nickname'], 'userid' => $userinfo['userid'], 'client_id' => $client_id];

                $this->setUserinfoFromSession($userinfo['userid'], $u1);//save session  basic information

                return true;
            }

        }

        //错误时回跳到登陆也
        Yii::$app->getSession()->setFlash('error-login','Username or password is not legal'); //错误提示信息
        $params = [
            'response_type'=>'code',
            'client_id'=>Yii::$app->request->get('client_id', ''),
            'state' => Yii::$app->request->get('state', ''),
            'redirect_uri' => Yii::$app->request->get('redirect_uri', ''),
            'language' => Yii::$app->language
        ];//参数

        $url = combineURL(Yii::$app->params['oauth_login_url'],$params);
        header("Location:$url");
        exit;
    }

    /**
     * 刷新access_token
     * @param 系统办法的唯一客户端id $client_id
     * @param 系统的键 $client_secret
     * @param 客户端保存的 $refresh_token
     */
    public function getRefreshToken($client_id,$client_secret,$refresh_token){

        $app_config_arr = $this->initThirdLoginApps(array('APP_ID'=>$client_id ,'APP_SECRET'=>$client_secret));

        //isrefresh_token 为1 时 可以调用此接口
        if($app_config_arr && $app_config_arr['isrefresh_token'] == 1){

            $act = $this->storage_redis->getRefreshToken($refresh_token); //redis access_token

            if($act && $act['user_id']){
                //refresh_token 只能用一次 用过删除
                 $this->storage_redis->unsetRefreshToken($refresh_token); //redis access_token

                $this->freshAccess_token_oauth_login($act['user_id'],$client_id,$client_secret,$app_config_arr['redirect_uri']);//直接跳过登录 进入授权
                exit;
            }


            show_json(100009,Yii::$app->params['errorCode'][100009]);//刷新失败  ##
        }

        show_json(100016,Yii::$app->params['errorCode'][100016]);
    }

    /**
     * oauth退出操作
     * @param $access_token
     * @param 回跳的地址 $callback
     */
    public function loginOut($access_token,$callback)
    {
        $act = $this->storage_redis->getAccessToken($access_token);

        if(!$act){
            show_json(0,'success');//如果不存在access_token时候 默认已退出
        }

        var_log(['access_token'=>$access_token,'callback'=>$callback],'logout');

        //删除redis accesstoken
        if($this->storage_redis->unsetAccessToken($access_token)){
            if($callback){
                header("Location:$callback");
                exit;
            }
            show_json(0,'success');
        }
        exit;
    }

    /**
     * 根据用户user_id 获取redis用户信息
     * @param $user_id
     * @return mixed
     */
    protected function getUserInfoFromRedis($user_id)
    {
        //获取redis用户缓存信息
        $redis = Yii::$app->redis;

        $u_key = 'oauth_login_userinfo:'.$user_id;

        return json_decode($redis->get($u_key),true);//openid get userinfo

    }

    /**
     * 供 sdk 使用 必须判断 appkey 与 appsecret 合法性
     * 根据appid 与 appsecret 判断是否合法 合法返回组合信息
     * @param $config
     * @throws \Exception array('APP_KEY'=>'xx','APP_SECRET'=>'vfvf')
     */
    protected function initThirdLoginApps($config)
    {

        $APP_ID    = $config['APP_ID'];

        //先判断redis 是否存在数据 不存在 在判断db是否存在 都不存在 就是无效的key
        $redis = Yii::$app->redis;

        $appinfo = json_decode($redis->get(self::$appInfo_key.$APP_ID),true);

        if(!$appinfo){

            /*//todo db redis不存在判断db数据是否存在合法数据 都不合法则不能完成授权
            $Oauth2 = new Oauth2;

            $appinfo = $Oauth2->getAppInfoConfig($APP_ID);

            if(!$appinfo){
                var_log([$config,'ip'=>getIp()],'app-id-secret-config');//信息错误 记录带的参数信息和ip地址 防止数据强刷

                show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法
            }*/

            show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法

        }

        if($appinfo['grant_types'] != 'authorization_code'){
            show_json(100000,'at present client_id not code the authorization model');//当前client id 不是code授权模式
        }
        if($APP_ID != $appinfo['client_id']){
            show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法
        }else{
            return $appinfo;
        }
    }

    /**
     * 刷新refresh_token跳过登陆
     * @param $userid
     * @param $client_id
     * @param $client_secret
     * @param $redirect_uri
     */
    protected function freshAccess_token_oauth_login($userid,$client_id,$client_secret,$redirect_uri)
    {

        $request = \OAuth2\Request::createFromGlobals();
        $response = new \OAuth2\Response();

        $Oauth2 = new Oauth2();

        $u = $Oauth2->getUserInfoToRefreshToken($userid);//获取db 数据

        $request->query['redirect_uri'] = $redirect_uri;//设置回掉url
        $request->query['response_type'] = 'code';//设置回掉url
        $request->query['state'] = 'senseplay';//设置回掉url

        if($u) {

            if ($u['regtype'] == 2) {
                $username = $u['useremail'];
            } elseif ($u['regtype'] == 3) {
                $username = $u['usermobile'];
            }else{
                $username = $u['username'];
            }


            $log = [
                'username' => $username,
                'pwd' => '',
                'regtype' => $u['regtype'],
                'state' => '',
                'appconfig' => array('APP_ID' => $client_id, 'APP_SECRET' => $client_secret),
            ];


            var_log($log, 'req-refresh-token');//log日志

            try{

                $is_authorized = true;

                //验证提交的用户信息是否存在
                $this->setFromRefreshTokenUserinfoToRedis($userid,$u['nickname'],$u['client_id'],$client_id);//验证用户是否存在 不存在抛出错误


                $this->server_redis->handleAuthorizeRequest($request, $response, $is_authorized, $u['userid']);

                if ($is_authorized) {
                    //同意授权
                    //生成授权码(Authorization Code)
                    $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);

                    $response->send();
                    exit();

                }

            }catch (\Exception $e){
                show_json(100009,Yii::$app->params['errorCode'][100009]);
            }


        }
    }


    /**
     * 刷新refresh_token 跳过登陆 使用该方法
     * @param $userid
     * @param $nickname
     * @return bool
     */
    protected function setFromRefreshTokenUserinfoToRedis($userid,$nickname,$client_id)
    {

        $PstUser = new PstUser();

        $u2 = $PstUser->getPstUserProfileInfo($userid);

        $u1 = ['nickname'=>$nickname,'userid'=>$userid,'client_id'=>$client_id,'avatar'=>$u2['avatar'],'sex'=>$u2['sex']];

        $this->setUserinfoFromSession($userid,$u1);//save session  basic information

    }

    /**
     * 设置redis缓存用户信息
     * 合并用户基础信息与扩展信息
     * @param $userid
     * @param $u1
     */
    protected function setUserinfoFromSession($userid,$u1)
    {
        $PstUser = new PstUser;

        $u2 = $PstUser->getPstUserProfileInfo($userid);

        $_SESSION['userinfo'] = array_merge($u1,$u2);//存储session

        //为了获取token时创建用户的对应关系 当前用户信息需要存储redis 便于后面处理
        //redis 存储
        $key = 'pst_form_userid_userinfo:'.$userid;//键

        $redis = Yii::$app->redis;

        $redis->set($key,json_encode($_SESSION['userinfo']));//缓存redis

    }

}