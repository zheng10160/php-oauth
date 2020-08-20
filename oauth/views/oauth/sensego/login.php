<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <script src="/js/sensego/public/rem.js" ></script>
    <link rel="stylesheet" type="text/css" href="/css/public/base.css">
    <link rel="stylesheet" type="text/css" href="/css/public/normalize.css">
    <link rel="stylesheet" type="text/css" href="/css/sensego/login.css">
    <title></title>
</head>
<body>
    <header id="header" class="head">
       <img src="/images/sensego/logo-login.png" alt="">

    </header>
    <!-- /header -->
    <form action="/oauth/authorize?response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>&language=<?=Yii::$app->language?>" method="post" onsubmit="return toVaild(this);">
        <section class="login-form ">
            <!-- username -->
            <section class="input-box">
                <input type="hidden" name="authorized" value="yes">
                <span class='tubiao'></span>
                <input type="text"  name="username" id="u_p" placeholder="<?=Yii::t('app','SENSEPLAY_ACCOUNT')?>" maxlength="128">
                <span class="clear clear-text"></span>
            </section>

            <!-- password-->
            <section class="input-box">
                <span class='pass'></span>
                <input type="password" name="pwd" maxlength="20" id="pwd"  type="password"  placeholder="<?=Yii::t('app','PASSWORD')?>">
                <span class="clear clear-text"></span>
            </section>

            <!-- denglu -->
            <button class="login">
                <?=Yii::t('app','LOGIN')?>
                <!-- 提示信息 -->
                <span class="ts tishi"><?=Yii::t('app','USERNAME_PWD')?></span>
            </button>

            <!-- operate -->
            <section class="input-box operate-con" >
               <a  href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reset_email?client_id=44E7F9DC2DE558BFBC5D808E38269876&language=<?=Yii::$app->language?>"><?=Yii::t('app','FORGET')?></a>
               <a href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reg_email?client_id=44E7F9DC2DE558BFBC5D808E38269876&language=<?=Yii::$app->language?>"><?=Yii::t('app','REGISTER')?></a>
            </section>

            <!-- third -->
            <section class="input-box third" >
               <span></span>
               <span><?=Yii::t('app','LOGIN_WITH')?></span>
               <span></span>
            </section>

             <section class="input-box third" >
                <!-- google -->
                <a href="<?=Yii::$app->params['account_senseplay_url']?>/third_login/authorize" class="qita"></a>
            </section>
        </section>
    </form>



    <script src="/js/sensego/public/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8" ></script>
    <script src="/js/public/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8" ></script>
    <script src="/js/public/jquery.lazyload.js" type="text/javascript" charset="utf-8"></script>

    <script>

        // 表单验证
        /*验证表单字段值的合法性*/
        // 点击登录按钮
        function toVaild()
        {
            var username = $('#u_p').val();//用户名称


            var pwd = $('#pwd').val();

           if(username=="" || pwd==""){//密码或用户名不能为空USER_NAME_NULL
                $('.tishi').html('<?=Yii::t('app','USER_NAME_NULL')?>');
                $('.tishi').show();
                $('span.tishi').delay(2000).hide(0);
                return false;
            }

            if(!username){
                $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
                $('.tishi').show();
                $('span.tishi').delay(2000).hide(0);
                return false;
            }


            if(isPhoneVerify(username) || isEmailVerify(username)){
                //处理其他信息
            }else{
                $('.tishi').html('<?=Yii::t('app','EMAIL_USERNAME')?>');  // 提示账号只能是email
                $('.tishi').show();
                $('span.tishi').delay(2000).hide(0);
                return false;
            }

            if(!pwd){
                $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
                $('.tishi').show();
                $('span.tishi').delay(2000).hide(0);
                return false;
            }
            //密码不含有空格
            if (pwd.indexOf(" ") != -1) {
                $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
                $('.tishi').show();
                $('span.tishi').delay(2000).hide(0);
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
            $('span.tishi').delay(2000).hide(0);
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

        //邮箱验证
        function isEmailVerify(email)
        {
            // var reg =/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/g;
            var reg =/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,8})$/;



            if(!reg.test(email)){
                $('.tishi').html('<?=Yii::t('app','EMAIL_USERNAME')?>');
                $('.tishi').show();
                $('span.tishi').delay(2000).hide(0);
                return false;
            }

            return true;
        }

        //清除按钮的出现
        $('#pwd').focus(function(){
            $(this).next('.clear-text').show();

        })
        $('#u_p').focus(function(){
            $(this).next('.clear-text').show();

        })

        //清除内容
        $('.clear-text').click(function(){
            $(this).prev().val('');
            $(this).hide();
        })
</script>
</body>
</html>