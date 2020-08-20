<?php
/**
 * Created by PhpStorm.
 * User: zhengjiang
 * Date: 2017/8/17
 * Time: 13:46
 */

namespace OAuth2;


class oauth
{
    private $storage;       //数据库链接
    private $server;        //OAuth2服务

    public function __construct()
    {
        \OAuth2\Autoloader::register();        //注册OAuth2服务
        $dsn = "mysql:dbname=dbname;host=127.0.0.1";
        $this->storage = new \OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => 'root', 'password' => '123456'));
        $this->server = new \OAuth2\Server($this->storage);
    }
    /**
     * 获取令牌（access_token）
     */
    public function token()
    {
        $this->server->addGrantType(new \OAuth2\GrantType\ClientCredentials($this->storage));
        $this->server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($this->storage));
        $this->server->handleTokenRequest(\OAuth2\Request::createFromGlobals())->send();
    }

    /**
     * 通过令牌获取用户信息
     */
    public function resource()
    {
        if (!$this->server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            $this->server->getResponse()->send();
            die;
        }
        $token = $this->server->getAccessTokenData(\OAuth2\Request::createFromGlobals());
        //$token['user_id']就是用户id，然后再通过user_id在数据库里查询用户信息并返回即可。
        echo "User ID associated with this token is {$token['user_id']}";
    }

    public function authorize()
    {
        $request = \OAuth2\Request::createFromGlobals();
        $response = new \OAuth2\Response();

        // validate the authorize request
        if (!$this->server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            exit();
        }
        // display an authorization form
        if($_POST['authorized']){
            $is_authorized = ($_POST['authorized']=='yes');
            $userid = $_SESSION['userid'];  //用户的id
            $this->server->handleAuthorizeRequest($request, $response, $is_authorized,$userid);
            if ($is_authorized) {
                //同意授权
                //保存用户授权的选项(略)
                //生成授权码(Authorization Code)
                $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
                //exit("SUCCESS! Authorization Code: $code");
                $response->send();exit();

            }else{
                //没有授权
            }
        }else{
            //展示授权视图
            exit('<form method="post"><label>Do You Authorize TestClient?</label><input name="authorized" type="submit" value="yes" /> <input name="authorized" type="submit" value="no" /></form>');
        }
    }
}