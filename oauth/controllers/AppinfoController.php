<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/10/23
 * Time: 下午5:04
 */

namespace app\controllers;

use Yii;
use Oauth2\Autoloader;
use OAuth2\Storage\Pdo;
use OAuth2\Storage\Redis;
use OAuth2\Server;
use app\controllers\base\AppinfoBaseController;
class AppinfoController extends AppinfoBaseController
{

    private $server_redis;
    private $storage_redis;
    public function init()
    {
        $this->enableCsrfValidation = false;

        //redis 存储

        $this->storage_redis = new Redis(Yii::$app->redis);
        $this->server_redis = new Server($this->storage_redis);

    }


    /**
     * code 模式
     * 临时设置申请的passport appid appkey
     */
    public function actionAddappinfo()
    {
        $client_id = createGuid();
        $client_secret = createGuid();
      /*  $redirect_uri = '192.168.99.62/index.php?r=clientoauth/oauth';//回掉地址页面*/
        $redirect_uri = 'http://dev.senseplay.dev.com/index.php?r=dev/callback';//回掉地址页面
        $grant_types = 'authorization_code';
        $scope = '';
        $issso = 1;
        $logout_url='';
        $isrefresh_token=1;
        $viewType = 1;//定义使用的模版 1 默认官方模版
       var_dump($this->storage_redis->setClientDetails($client_id, $client_secret, $redirect_uri, $grant_types, $scope, $issso,$logout_url,$isrefresh_token,$viewType));
        //var_dump('vfads');die;
        //return $this->render('addappinfo');


    }

    /**
     * 密码模式
     */
    public function actionTest()
    {

        $client_id = createGuid();
        $client_secret = createGuid();
        $redirect_uri = 'http://test.account.senseplay.com/clientoauth/oauth';//回掉地址页面
        $grant_types = 'password';//确定使用授权码模式 or 密码模式
        $scope = '';
        $issso = 1;
        $logout_url='';
        $isrefresh_token=1;
        $viewType = 1;//定义使用的模版 1 默认官方模版
        var_dump($this->storage_redis->setClientDetails($client_id, $client_secret, $redirect_uri, $grant_types, $scope, $issso,$logout_url,$isrefresh_token,$viewType));
        //var_dump('vfads');die;
        //return $this->render('addappinfo');


    }

    /**
     * code 模式
     * 临时设置申请的passport appid appkey
     */
    public function actionSet_appinfo()
    {

        $client_id = createGuid();
        $client_secret = createGuid();
        /*  $redirect_uri = '192.168.99.62/index.php?r=clientoauth/oauth';//回掉地址页面*/
        $redirect_uri = 'http://test.account.senseplay.com/clientoauth/senseplay_app_callback';//回掉地址页面
        $grant_types = 'authorization_code';
        $scope = '';
        $issso = 1;
        $logout_url='';
        $isrefresh_token=1;
        $viewType = 7;//定义使用的模版 1 默认官方模版
        var_dump($this->storage_redis->setClientDetails($client_id, $client_secret, $redirect_uri, $grant_types, $scope, $issso,$logout_url,$isrefresh_token,$viewType));
        //var_dump('vfads');die;
        //return $this->render('addappinfo');


    }

}