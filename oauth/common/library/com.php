<?php
namespace common\library;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/10/29
 * Time: 上午11:42
 */
use yii;
class com
{
    const PRIXIP = 'pst_login_reglimit:';//ip前缀

    /**
     * 处理ip防刷
     * 判断当前ip是否受限制 1
     * (注意过滤如：注册次数过多 限制当前的ip)
     *
     * 请求方式 curl post
     */
    public function isLimitIp()
    {
        $redis = Yii::$app->redis;

        $ip = ip2long(getIp());//获取ip 特殊字符处理

        try{
            $key = self::PRIXIP.$ip;//限制ip redis key

            $redis->rpush($key,time());//只记录当前时间

            $data = $redis->lrange($key,0,-1);//获取全部数据

            $this->disposeFromRedisIpInfo($data,$key);

            return true;
        }catch (\Exception $e){
            var_dump($e->getMessage());die;
            show_json(200000,'be defeated');
        }
    }


    /**
     * 处理ip数据
     * @param $data
     * @param $key
     */
    protected function disposeFromRedisIpInfo($data,$key)
    {

        $limitTime = Yii::$app->params['limitTime'];//系统限制时间

        $atLegalIp_arr = [];//当前时间段已合法 不同时间段的记录容器

        /*遍历循环 取当前系统时间 与 每个时间段 时间做比较 排除系统规定时间外的数据*/
        foreach($data as $k=>$value){
            //当前系统时间 - 限制时间段 判断记录值 是否大于商值 大于的就是时间段已有的log
            if(intval(time()-$limitTime) <= $value){

                array_push($atLegalIp_arr,$value);

            }
        }

        /*判断当前记录总数是否超过 系统配置 限制数*/
        if(count($atLegalIp_arr) >= Yii::$app->params['limitipnum']){

            Yii::$app->redis->rpop($key);//弹出当前已插入 不合法的数据

            show_json(200001, 'The number of operations has exceeded the limit until later',$atLegalIp_arr);
        }

        Yii::$app->redis->expire($key,Yii::$app->params['regloglimittime']);//设置key 过期时间 清除注册后的脏数据;

    }
}