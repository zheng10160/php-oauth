<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index,follow" />
    <meta name="googlebot" content="index,follow" />
    <meta name="google" content="index,follow" />
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="senseplay,senseplay app sdk,develop,developer" />
    <meta name=description content="As the market leader in easy-to-fly drones and aerial photography systems, sdk quadcopters like the Phantom are the standard in consumer drone technology.">
    <meta name=keywords content="SDK, SDK">
<!--<link rel="icon" href="../../favicon.ico"> -->
    <title>Senseplay Developer</title>
    <link href="/css/pc/common/normalize.css" rel="stylesheet">
    <link href="/css/pc/common/reset.css" rel="stylesheet">
    <link href="/css/pc/login.css" rel="stylesheet">
     <script src="/js/public/jquery-3.1.1.min.js"></script>
    <title></title>
</head>
<body style="position:relative;top:0;;min-width:1200px;height:1080px;">
    <section class="login-box">

            <section class="login-left">
                <img src="/images/pc/loginbg_01.png" alt="">

            </section>


            <section class="login-right">
                <section class="right-top">
                    <h4><a href="http://test.account.senseplay.com/pc/developer/reg_email" style="display:block;width: 100%;height: 100%;color:#666"><?=Yii::t('app','SIGN_UP')?></a></h4>
                    <h4><?=Yii::t('app','NO_ACCOUNT')?></h4>
                </section>
                <!-- 中间输入框 -->
                <section class="right-m">

                    <form action="/oauth/authorize?response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>&language=<?=Yii::$app->language?>" method="post" onsubmit="return toVaild(this);">
                        <!-- title -->
                        <div class="title" ><?=Yii::t('app','LOGIN')?>
                            <!-- 信息提示 -->
                            <span class="login-ts tishi"><?=Yii::t('app','USER_ERROR')?></span>
                        </div>
                        <!-- 用户名输入框 -->
                        <div class="xx-box">
                            <h4><?=Yii::t('app','PHONE_EMAIL')?></h4>
                            <div class="email-box">
                                <input type="hidden" name="authorized" value="yes">
                                <input autocomplete="off" type="text" name="username" id="u_p" class="hidden-wz input-box" placeholder="<?=Yii::t('app','PHONE_EMAIL')?>">
                            </div>

                        </div>
                        <!-- 输入密码 -->
                        <div class="xx-box">
                            <div class="xxts">
                                <h4><?=Yii::t('app','PASSWORD')?></h4>
                                <!-- 忘记密码 -->
                                <a href="http://test.account.senseplay.com/pc/developer/reset"><?=Yii::t('app','FORGET')?></a>

                            </div>

                            <div class="email-box">
                                <input autocomplete="off" type="password"  name="pwd" maxlength="20" id="pwd" minlength="6" class="hidden-wz input-box" autocomplete="off"  placeholder="<?=Yii::t('app','PASSWORD')?>">
                            </div>

                        </div>

                         <!-- 登录按钮 -->

                        <button class="dl login-dl xx" style="outline:none;border:none;"><?=Yii::t('app','LOGIN')?></button>

                        <!-- 其他格式 -->
                        <div class="platform">
                            <div class="xian">
                                <span></span>
                                <h6><?=Yii::t('app','LOGIN_WITH')?></h6>
                                <span></span>
                            </div>

                            <div class="fs">
                                
                                <a href="" style="display: none"><img src="/images/pc/Facebook.png" alt="" /></a>
                                <!-- Google -->
                                <a href="http://test.account.senseplay.com/third_login/authorize"><img src="/images/pc/Google.png" alt="" /></a>
                                <!-- 微信 -->
                                <a href="http://test.account.senseplay.com/third_login/oauth_login?type=wechat"><img src="/images/pc/Wechat.png" alt="" /></a>
                                <a href="" style="display: none"><img src="/images/pc/Twitter.png" alt="" /></a>
                                
                                <a href="" style="display: none"><img src="/images/pc/QQ.png" alt="" /></a>

                            </div>
                        </div>
                    </form>
                </section>


                <!-- 底部 -->
                <section class="right-bottom">
                    <h5>Copyright © 2017 SENSEPLAY Rights Reserved</h5>
                    <div class="info-box">
                        <img src="/images/pc/Icons/language.png" alt="">
                        <h5><?=Yii::$app->language?></h5>
                        <img src="/images/pc/Icons/arrow_up_line.png" alt="">
                        <!-- 语言 -->
                        <ul class="xzyy" style="display:none;">
                            <li data-value="<?=Yii::t('app','ENGLISH')?>"  class="language_btn"><?=Yii::t('app','ENGLISH')?></li>
                            <li data-value="<?=Yii::t('app','SIMPLIFIED_CHINESE')?>"  class="language_btn"><?=Yii::t('app','SIMPLIFIED_CHINESE')?></li>
                        </ul>
                    </div>
                </section>

            </section>
    </section>
</body>
<script>

    /*验证表单字段值的合法性*/
    function toVaild()
    {
        var username = $('#u_p').val();//用户

        var pwd = $('#pwd').val();

        if(!username){
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
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
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
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
        $('.tishi').html(sen_value);
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
        var reg =/^[\w-.]+@[\w-]+(.[\w_-]{0,128})+(\.[a-zA-Z0-9]{2,8})$/;

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

    $('.right-bottom').click(function(){
        $('.xzyy').toggle();

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
