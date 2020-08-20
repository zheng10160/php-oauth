<?php
namespace app\api_interface\oauth;
/**
 * oauth相关的接口定义
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/27
 * Time: 下午8:07
 */
interface oauth_interface
{
    /**
     * 获取用户的openid
     * @param access_token 用户登陆系统办法的唯一凭证 换取用户openid
     * @return mixed
     */
    public function getOpenid($access_token);

    /**
     * 检测用户是否登陆 判断access_token 是否失效
     * @param $access_token
     * @param $client_id
     * @return mixed
     */
    public function isCheckLogin($access_token,$client_id);

    /**
     * 获取用户详细信息
     * @param $access_token 用户登陆的唯一凭证
     * @param $openid 用户的openid
     * @return mixed
     */
    public function getUserInfo($access_token,$openid);

    /**
     * 延长登陆时效
     * 刷新access_token
     * @param $client_id 系统办法的唯一客户端id
     * @param $client_secret 系统的键
     * @param $refresh_token 客户端保存的
     * @return mixed
     */
    public function getRefreshToken($client_id,$client_secret,$refresh_token);

    /**
     * oauth授权退出操作
     * @param $access_token
     * @param $callback 回跳的地址
     * @return mixed
     */
    public function loginOut($access_token,$callback);
}