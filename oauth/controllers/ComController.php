<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/2/26
 * Time: 下午1:22
 */

namespace app\controllers;

use Yii;
use app\controllers\base\ComBaseController;
class ComController extends ComBaseController
{
    /**
     * 切换语言
     */
    public function actionGet_language()
    {

        $language = Yii::$app->request->get('language','');

        if(!$language){
            show_json(100000,'The parameter cannot be empty');
        }

        if(!in_array($language,array_keys(Yii::$app->params['language']))){

            show_json(100000,'set language be defeated');
        }

        setcookie('language',$language,time()+3600*24*30,'/','.senseplay.com');//设置全局cookie
        //setcookie('language',$language,time()+3600*24*30,'/');//设置全局cookie

        //$this->goBack(Yii::$app->request->headers['Referer']);//跳回刚才的页面
        show_json(0,'set language success');

    }
}