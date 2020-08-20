<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width = device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta charset="UTF-8">

    <meta name=format-detection content="telephone=no">
    <meta content="user-scalable=no,width=device-width,initial-scale=1,maximum-scale=1" name=viewport>


    <link rel="stylesheet" href="/css/public/base.css">
    <link rel="stylesheet" href="/css/public/normalize.css">
    <link rel="stylesheet" href="/css/public/swiper.min.css">

    <link rel="stylesheet" href="/css/login.css">

    <meta name=keywords content="SDK, aerial photography, aerial filming, drone, UAV, camera gimbal,quadcopter">

    <meta name=description content="As the market leader in easy-to-fly drones and aerial photography systems, sdk quadcopters like the Phantom are the standard in consumer drone technology.">

    <meta name=keywords content="SDK, SDK_MOBILE">

    <meta name=description content="">

    <script src="/js/public/jquery-3.1.1.min.js"></script>
    <script src="/js/public/jquery.lazyload.js"></script>
    <title></title>
</head>
<style>body { font-size:16px;background-color: #f2f2f2;font-family: Roboto;}</style>
<body>
<!-- header start -->
<header id="header" class="header">
    <img width="24px" height="24px" src="/images/close_black.svg" alt="close" class="close" style="display:none">
    <h4><?=Yii::t('app','LOGIN')?></h4>
</header>
<!-- /header -->

<section class="xx-box">
    <section class="wrap" style="margin-bottom:3.5rem/* 56px */">
        <form action="/oauth/authorize?response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>&language=<?=Yii::$app->language?>" method="post" onsubmit="return toVaild(this);">
            <!-- username -->
            <div class="xx" style="display:flex;">
                
                <input type="hidden" name="authorized" value="yes">
                <input autocomplete="off" type="text" name="username" id="u_p" class="input-box" placeholder="<?=Yii::t('app','PHONE_EMAIL')?>">
                <!-- <img src="/images/close_black.svg" alt=""> -->
            </div>
            <!-- password -->
            <div class="xx-wrap xx">
                <input autocomplete="off" type="password" name="pwd"  id="pwd"  class="input-box" placeholder="<?=Yii::t('app','PASSWORD')?>">
                <a href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reset"><?=Yii::t('app','FORGET')?></a>
                <span class="tishi"><?=Yii::t('app','PASSWORD_ERROR')?></span>
            </div>
            <!-- denglu -->
            <button class="login-dl xx" style="background:#28c5ee;border:none;outline:none"><?=Yii::t('app','LOGIN')?></button>
        </form>


        <!-- platform -->
        <div class="platform xx" style="background:none;border:0;">
            <span></span>
            <h5><?=Yii::t('app','LOGIN_WITH')?></h5>
            <span></span>
        </div>

        <div class="more">

            <a href="<?=Yii::$app->params['account_senseplay_url']?>/third_login/authorize">
                <img src="/images/Mobile/OAuth_Google.svg" alt="Google">
                <h6>Google</h6>
            </a>

            <a href="<?=Yii::$app->params['account_senseplay_url']?>/third_login/oauth_login?type=wechat">
                <img src="/images/Mobile/OAuth_Wechat.svg" alt="Wechat">
                <h6>Wechat</h6>
            </a>
        </div>
    </section>

    <!-- footer -->
    <footer style="margin-bottom:114px;">
        <div class='foot-box' style="margin:0 auto;">
            <a href="javascript:;"><?=Yii::t('app','NOT_REG')?></a>
            <a href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reg_email" style="color:#28C5EE"><?=Yii::t('app','CREATE_ACCOUNT')?></a>
        </div>

    </footer>


<!-- 语言切换 -->
    <section class="lan">
        <div class="language">
            <img src="/images/Mobile/language.png" alt="">
            <h4><?=Yii::$app->language?></h4>
            <img src="/images/Mobile/arrow_up_line.png" alt="">

        </div>
    </section>


    <div class="xzyy">
        <header id="header" class="header">
            <img width="24px" height="24px" src="/images/close_black.svg" alt="close" class="close">
            <h4 style="font-size:20px;text-align:center;padding-left:0;"><?=Yii::t('app','LANGUAGE')?></h4>
        </header>
        <h4 class="select"><?=Yii::t('app','SELECT_LANGUAGE')?></h4>
        <ul>
            <li><a href="javascript:;" class="language_btn" data-value="en"><?=Yii::t('app','ENGLISH')?></a></li>
            <li><a href="javascript:;" class="language_btn" data-value="zh"><?=Yii::t('app','SIMPLIFIED_CHINESE')?></a></li>

        </ul>

    </div >




    <!-- 版权 信息 -->
    <div class="banquan">
        Copyright © 2017 SENSEPLAY Rights Reserved
    </div>

</section>
</body>

<script>

    /*验证表单字段值的合法性*/
    function toVaild()
    {
        var username = $('#u_p').val();//用户

        var pwd = $('#pwd').val();

        if(!username){
            $('.tishi').html('<?=Yii::t('app','USER_NAME_NULL')?>');
            $('.tishi').show();
            return false;
        }

        if(isPhoneVerify(username) || isEmailVerify(username)){
            //处理其他信息
        }else{
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
            $('.tishi').show();
            return false;
        }

        if(!pwd){
            $('.tishi').html('<?=Yii::t('app','PASSWORD_ERROR')?>');
            $('.tishi').show();
            return false;
        }
        if (pwd.indexOf(" ") != -1) {
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
            $('.tishi').show();
            return false;
        }

        return true;
    }


    /**
     * 错误提示信息
     * @type {string}
     */
    var sen_value = '<?=Yii::$app->getSession()->getFlash('error-login')?>';
    if(sen_value){
        $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');//用户名和密码不匹配
        $('.tishi').show();
    }

    //手机验证
    function isPhoneVerify(phone)
    {
        var reg = /^[1][3,4,5,7,8][0-9]{9}$/;

        if(!reg.test(phone)){
            return false;
        }

        return true;
    }

    //手机验证
    function isEmailVerify(email)
    {
        // var reg =/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/g;
        var reg =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,8})$/;

        if(!reg.test(email)){
            return false;
        }

        return true;
    }


    $('.language').click(function(){
        $('.xzyy').show();

    })
    $('.xzyy').find('img.close').click(function(){
        $('.xzyy').hide();

    })

    //切换语言
    $('.language_btn').click(function(){

        var v = $(this).attr('data-value');
        $.ajax({
            url: '<?=Url::toRoute('com/get_language')?>',
            type: 'get',
            dataType: 'json',
            data: {'language':v},
            success:function (data) {
                console.log(data);
                if(data.code==0){
                    window.location.reload();
                }else{
                    //return false;
                    alert(data.message);
                }

            }
        })
    });
</script>
</html>