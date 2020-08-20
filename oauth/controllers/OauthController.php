<?php
namespace app\controllers;

/**
 * 当前控制器使用redis作为oauth2的缓存机制，如果使用db存储 请参考oauthDb控制器
 */
use app\models\Oauth2;
use OAuth2\Response;
use Yii;
use Oauth2\Autoloader;
use OAuth2\Storage\Pdo;
use OAuth2\Storage\Redis;
use OAuth2\Server;
use app\controllers\base\OauthBaseController;
use app\models\PstUser;
use common\library\com;

use common\util\IFilter;
use common\util\IReq;
class OauthController extends OauthBaseController
{
    protected static $appInfo_key = 'oauth_clients:';//appinfo 存储redis的键值

    /**
     * redis链接
     * @var
     */
    private $storage_redis;

    /**
     * OAuth2服务 redis
     * @var
     */
    private $server_redis;

    public function init()
    {
        $this->enableCsrfValidation = false;

        $this->layout = false;

       _initSess();//开启session

        $Autoloader = new Autoloader();
        // \OAuth2\Autoloader::register();        //注册OAuth2服务
        $Autoloader::register();

        //redis 存储
        $this->storage_redis = new Redis(Yii::$app->redis);
        $this->server_redis = new Server($this->storage_redis);

    }

    /**************************************************授权模式 目前采用两种 1 code  2 密码模式 start**********************************************************************/
    /**
     * authorization_code  授权模式
     *  获取令牌（access_token）
     *  请求方式 curl请求 post方法
     *  参数 code 上一步请求到的 code
     *  ['code' => $code]
     *  code 有效合法 会返回 access_token 有效期30天
     */
    public function actionToken()
    {
        //redis
        //$this->server_redis->addGrantType(new \OAuth2\GrantType\ClientCredentials($this->storage_redis));
        $this->server_redis->addGrantType(new \OAuth2\GrantType\AuthorizationCode($this->storage_redis));

        $this->server_redis->handleTokenRequest(\OAuth2\Request::createFromGlobals())->send();
    }

    /**
     * passport  授权模式 此模式仅供passport内部对此调用
     *  获取令牌（access_token）
     *  请求方式 curl请求 post方法
     *  参数
     *  ['client_id'=>'','client_secret'=>'','grant_type' => 'password','username'=>'','password'=>'','scope'=>'']
     *  code 有效合法 会返回 access_token 有效期30天
     */
    public function actionToken_pwd()
    {
        //redis
        $this->server_redis->addGrantType(new \OAuth2\GrantType\UserCredentials($this->storage_redis));

        $this->server_redis->handleTokenRequest(\OAuth2\Request::createFromGlobals())->send();
    }
    /******************************************************授权模式 end****************************************************************/



    /**
     *  access_token 换取 用户 userid
     *  请求方式 curl请求 post方法
     *  请求参数 access_token
     *  ['access_token' => $access_token]
     *  请求成功 返回用户userid 失败则提示 access_token 无效
     *  注意：此方法 只供 会员系统平台使用 因为会员系统有权限直接操作userid
     *  其他子系统与第三方 只能操作openid会与userid有关联
     * @return userid
     */
    public function actionResource()
    {

        if (!$this->server_redis->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            $this->server_redis->getResponse()->send();
            die;
        }
        $token = $this->server_redis->getAccessTokenData(\OAuth2\Request::createFromGlobals());
        //$token['user_id']就是用户id，然后再通过user_id在数据库里查询用户信息并返回即可。
        //echo "User ID associated with this token is {$token['user_id']}";
        return $token['user_id'];
    }

    /**
     *  code 授权登陆模式 仅供作为第三方登陆使用的sdk
     *  获取 code
     *  授权操作 此方法为系统内部操作 客户端不可见
     *  登录成功后 会自动跳转到此方法
     *  所带参数 例如 ./index.php?r=oauth/authorize&response_type=code&client_id=xx&redirect_uri=http://www.gw_user.com.cn/index.php?r=clientoauth/oauth&state
     *  1 state 必须参数
     *  2 client_id 必须参数
     *  3 redirect_uri 必须参数
     *
     * 是否授权 返回code
     * @return string
     *
     * 客户端确认授权后跳转到回调页
     * 回调页可以带着 code 再次去oauth 获取 access_token
     *
     * 2018-01-30
     * update 修改了登陆页面与授权页面为同一个页面
     * @return string
     */
    public function actionAuthorize()
    {

        //  Yii::$app->getSession()->setFlash('success','Your username or password error'); 提示信息
        $request = \OAuth2\Request::createFromGlobals();
        $response = new \OAuth2\Response();

        // display an authorization form
        if(Yii::$app->request->isPost){//验证是post提交 并且确定授权

            // validate the authorize request  验证appid 回掉url是否合法  进入登陆页面不需要验证appid 提交登陆信息在验证
            if (!$this->server_redis->validateAuthorizeRequest($request, $response)) {
                $response->send();
                exit();
            }

            $is_authorized = ($_POST['authorized'] == 'yes');

            $username = IFilter::act(IReq::get('username','post'),'string',128);//登陆用户名

            $pwd = IFilter::act(IReq::get('pwd','post'),'string',20);//密码

            //验证提交的用户信息是否存在
            $client_id = IFilter::act(IReq::get('client_id'),'string',32);//client_id

            $this->verifyUserinfo($username,$pwd,$client_id);//验证用户是否存在 不存在抛出错误

            $userinfo = $this->getUserinfoFromSession();//session 获取用户信息

            $this->server_redis->handleAuthorizeRequest($request, $response, $is_authorized, $userinfo['userid']);

            if ($is_authorized) {
                (new com)->isLimitIp(); //限制ip 短时间内 访问次数 判断是否合法
                //同意授权
                //生成授权码(Authorization Code)
                $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);

                var_log(['userinfo'=>$userinfo,'ip'=>getIp(),'is_authorized'=>true,'code'=>$code],'oauth-authorize-2');

                $response->send();
                exit();

            } else {
                //没有授权
                //错误时回跳到登陆也
                Yii::$app->getSession()->setFlash('error-login','Unauthorized access is not allowed'); //未授权 禁止登陆
                $params = [
                    'response_type'=>'code',
                    'client_id'=>Yii::$app->request->get('client_id', ''),
                    'state' => Yii::$app->request->get('state', ''),
                    'redirect_uri' => Yii::$app->request->get('redirect_uri', ''),
                ];//参数

                $url = combineURL(Yii::$app->params['oauth_login_url'],$params);
                header("Location:$url");
                // $response->send();
                exit();
            }
        }else{
            //登陆页面
            $state = IFilter::act(IReq::get('state'),'string',32);//客户端传来的state 调用oauth接口使用
            $client_id = IFilter::act(IReq::get('client_id'),'string',32);//客户端传来的appid 调用oauth接口使用
            $redirect_uri = IFilter::act(IReq::get('redirect_uri'),'string');//callback 回掉地址

            $view = $this->getViewTypeString($client_id);//获取使用的模版

            return $this->render($view.'/login', [
                'state' => $state,
                'client_id' => $client_id,
                'redirect_uri' => $redirect_uri
            ]);
        }
    }

    /**
     * 供所有第三方 与 子系统使用
     * 获取当前授权应用的openid 通过openid可以得到用户信息
     * curl post 请求方式
     * 请求参数 ['access_token'=>$access_token]
     * @return  openid
     * @error  提示错误
     */
    public function actionGet_openid()
    {

        $access_token = IFilter::act(IReq::get('access_token'),'string',50);

        if(!$access_token){
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

        $this->getOpenid($access_token);
    }


    /**
     * 是否有登入操作
     * 检测access_token是否有效
     * @return bool
     */
    public function actionCheck_is_login()
    {
        $access_token = IFilter::act(IReq::get('access_token'),'string',50);

        $client_id = IFilter::act(IReq::get('client_id'),'string',32);

        if(!$access_token){
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

        $this->isCheckLogin($access_token,$client_id);
    }


    /**
     * 供第三方与子系统调用
     * 通过openid 获取用户基本信息
     * 请求方式 curl post方式
     * 参数 ['openid' => $openid,'access_token'=>]
     * success 返回用户信息
     * @return mixed
     */
    public function actionGet_userinfo()
    {

        $openid = IFilter::act(IReq::get('openid'),'string',32);

        if(!$openid){
            show_json(100013,Yii::$app->params['errorCode'][100013]);
        }

        $access_token = IFilter::act(IReq::get('access_token'),'string',50);

        if(!$access_token){
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

        $this->getUserInfo($access_token,$openid);

    }


    /**
     * 当access_token 过期时
     * 调用 refresh_token 刷新生成新的access_token  可以跳过登录操作
     * 申请appid与appkey时会告知是否有刷新refresh_token权限
     * 请求方式 curl post方式
     * 参数 ['APP_ID' => '','APP_SECRET'=>'','refresh_token'=>'']
     */
    public function actionGet_refresh_token()
    {
        $client_id = IFilter::act(IReq::get('client_id'),'string',32);

        $client_secret = IFilter::act(IReq::get('client_secret'),'string',32);

        $refresh_token = IFilter::act(IReq::get('refresh_token'),'string',50);

        if(!$client_id || !$client_secret || !$refresh_token){
            show_json(100015,Yii::$app->params['errorCode'][100015]);
        }

        $this->getRefreshToken($client_id,$client_secret,$refresh_token);
    }


    /**
     * 退出操作
     */
    public function actionLoginout()
    {

        $access_token = IFilter::act(IReq::get('access_token'),'string',50);

        $callback_url = IFilter::act(IReq::get('callback'),'string');

        if(!$access_token){
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

        $this->loginOut($access_token,$callback_url);
    }

    /**
     * 根据client_id 验证是否合法 是否存在
     * @return bool
     */
    public function actionCheck_is_client_info()
    {
        $client_id = Yii::$app->request->post()['client_id'];

        if(!$client_id){
            show_json(100006,Yii::$app->params['errorCode'][100006]);
        }
        //先判断redis 是否存在数据 不存在 在判断db是否存在 都不存在 就是无效的key
        $redis = Yii::$app->redis;

        $appinfo = json_decode($redis->get(self::$appInfo_key.$client_id),true);

        if(!$appinfo){

            /*//todo db redis不存在判断db数据是否存在合法数据 都不合法则不能完成授权
            $Oauth2 = new Oauth2;

            $appinfo = $Oauth2->getAppInfoConfig($APP_ID);

            if(!$appinfo){
                var_log([$config,'ip'=>getIp()],'app-id-secret-config');//信息错误 记录带的参数信息和ip地址 防止数据强刷

                show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法
            }*/

            /* show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法*/
            show_json(100000,'error');

        }

        show_json(0,'success',$appinfo);

    }
}


