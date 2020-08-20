<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="<?= Url::base() ?>css/login.css">
    <link rel="stylesheet" href="<?= Url::base() ?>css/jqueryui.css">

</head>
<body style='background:#F2F2F2;overflow:-scrool;overflow-x:hidden;'>
<!-- /header -->
<header id="header" class="header">
    <article class="header-wapper">
        <div>
            <img src="<?= Url::base() ?>/" alt="">
            <h2>SENSEPLAY</h2>
        </div>

        <div>
            <form action="#">
                <select name="speed" id="speed">
                    <option>繁体中文</option>
                    <option>韩语</option>
                    <option selected="selected">简体中文</option>
                    <option>英语</option>
                    <option>日语</option>
                </select>
            </form>
        </div>

    </article>
</header>

<!-- banner   -->
<section class="banner">
    <section class="banner-left" id="focus-banner-list">

        <div class="ssmp2 ssmpyd" id="ssmpyd">
            <div class="lunbo"  id="focus-banner-list">
                <li>
                    <a href="JavaScript:;" class="focus-banner-img">
                        <img src="<?= Url::base() ?>/img/bg-login.png">
                    </a>
                </li>
                <li>
                    <a href="JavaScript:;"  class="focus-banner-img">
                        <img src="<?= Url::base() ?>/img/bg-login.png">
                    </a>
                </li>
                <li>
                    <a href="JavaScript:;"  class="focus-banner-img">
                        <img src="<?= Url::base() ?>/img/bg-login.png">
                    </a>
                </li>

            </div>
            <!-- 箭头 -->
            <div id="next-img" class="focus-handle wLeft ssmpjtl ">
                <img src="<?= Url::base() ?>/img/arrow-left-big-white.png">
            </div>
            <div   id="prev-img" class="wRight ssmpjtr">
                <img src="<?= Url::base() ?>/img/arrow-right-big-white.png">
            </div>

            <ul id="focus-bubble"></ul>

        </div>
    </section>



    <!-- login-->
    <section id="login-form" class="banner-right banner-login" style="width:30rem/* 480px */;padding-top:4rem/* 64px */ ;">
        <form action="./index.php?r=auth/dispose_login" method="post" accept-charset="utf-8" class="login_form" >
           <!--隐藏-->
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken?>">
            <input type="hidden"  name="state" value="<?=$state?>">
            <input type="hidden"  name="client_id" value="<?=$client_id?>">
            <!--隐藏end-->
            <div class="login">
                登录
            </div>
            <div class="wrapper">
                <div class="input-dl">
                    <input type="text" id="mobile" name="username" class="register_input" placeholder="邮箱/手机号码" onblur="__changeUserName('mobile');">
                    <!--  <input required class="register_input" type="text" id="mobile" name="mobile" maxLength="11" value="" onblur="__changeUserName('mobile');" placeholder="邮箱/手机号码"> -->
                    <!-- <span class="control-group" id="yhmm">*用户名输入输入有误</span>-->
                </div>
                <div class="input-dl" style="position:relative">
                    <input id="pass" class="visible pwd1" type="password"  name="pwd"  placeholder="密码"  >
                    <img type="button" name="" id="btn" class="show" src="<?= Url::base() ?>/img/btn-visible.png" alt=""  >
                    <!-- <span class="control-group" id="mmts">*密码输入有误</span>
-->
                </div>
                <div class="input-dl" style="position:relative">
                    <!-- 图形验证码  -->
                    <!-- <input type="" name="" placeholder="验证码"  id="my_button">
                    <div id="v_container"></div> -->
                    <input type="text" name="captcha" value="" placeholder="请输入验证码" id="my_button" class="inputyz">
                    <img id="captcha_img" border="1" src="http://www.gw_user.com.cn/index.php?r=user/get_captcha&t=<?=rand()?>" margin-top='9px' alt="">

                    <!--<span class="control-group" id="yzm">*验证码输入有误</span>-->

                </div>
                <button class="input-dl buttondl" id="input-dl">立即登录</button>
                <div class="input-dl cz">
                    <a href="register.php">注册账号</a>
                    <a href="#" id="forgetmm">忘记密码？</a>
                </div>
            </div>
            <div class="clearfix"></div>


            <div class="bottom-fs">
                <div class="fs">
                    <span></span>
                    <span>其他登录方式</span>
                    <span></span>

                </div>
                <div class="wlpt">
                    <a href="javascript:;"></a>
                    <a href="javascript:;"></a>
                    <a href="javascript:;"></a>
                    <a href="javascript:;"></a>
                </div>


            </div>
        </form>
        <!-- 找回密码重置密码 -->
        <section id="success" style="" class="success">
            <div class="success-box">
                <span></span>
                <span>重置密码</span>
                <div class="input-dl buttondl" id="next-step" style="line-height:48px">通过手机找回</div>
                <div class="input-dl buttondl" id="next-step1" style="line-height:48px">通过邮箱找回</div>

            </div>
        </section>
    </section>
</section>
<!-- footer -->
<footer class="footer">
    <p>Copyright © 2017  上海感悟通信科技有限公司版权所有  沪ICP备17022468</p>
    <p>如果您有任何关于产品问题请联系：service@senseplay.com</p>

</footer>
<script src="<?= Url::base() ?>/js/util/jquery-1.8.3.min.js"></script>
<script src="<?= Url::base() ?>/js/util//jq22.js"></script>
<script src="<?= Url::base() ?>//util/jqueryui.js"></script>
<script src="<?= Url::base() ?>//util/animate.js"></script>
<script src="<?= Url::base() ?>//util/gVerify.js"></script>
<script src="<?= Url::base() ?>//common/common.js"></script>
<script src="<?= Url::base() ?>/js/login.js"></script>

</body>
<script>
    /*登陆*/
    /* $('#input-dl').click(function(){
     var data = $('#login_form').serialize();
     $.ajax({
     url: "./index.php?r=oauth/login&state=<?=$state?>&client_id=<?=$client_id?>&client_secret=<?=$client_secret?>",
     type: 'post',
     dataType: 'json',
     data: data,
     success:function (data) {
     console.log(data);
     if(data.code==200){
     alert('success login');
     }else{
     alert('success error');
     }

     }
     })
     });*/
    /*end*/
</script>
</html>

















