<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>senseracing</title>
    <link href="/css/senseracing/base.css" rel="stylesheet" />
    <link href="/css/senseracing/mui.min.css" rel="stylesheet" />
    <!-- 预加载大背景图片 -->
    <link rel="prefetch" href="/images/senseracing/login11.png" />
    <link rel="prefetch" href="/images/senseracing/Btns_Primary_Enable_L1.png" />
    <link href="/css/senseracing/login.css" rel="stylesheet" />
    <script src="/js/senseracing/jquery-3.1.1.min.js"></script>

</head>
<body style="overflow-y:scroll">
	

		<!--提交信息-->
		<section class="big-wrap">
			<form action="/oauth/authorize?response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>&language=<?=Yii::$app->language?>" method="post" onsubmit="return toVaild(this);">
	
				<section class="login-form mui-content">
					<!--登录标题-->
					<div class="user">
						<h1 class="biaoti" style="line-height: 48px;"><?=Yii::t('app','LOGIN')?></h1>
					</div>

					<!-- username -->
					<div class="user">
						<input type="hidden" name="authorized" value="yes">
						<!-- 用户名输入框 -->
						<input type="text" name="username" id="u_p" class="mui-input-clear mui-input input-box" placeholder="<?=Yii::t('app','PHONE_EMAIL')?>" autocomplete="off" maxlength="128"/>
					</div>
					
					<!--密码-->
					<div class="user" style="position:relative;">
						 <!-- 密码输入框 -->
						<input  name="pwd" maxlength="20" id="pwd"  type="password" class="mui-input-clear mui-input input-box"  placeholder="<?=Yii::t('app','PASSWORD')?>" autocomplete="off"/>
						<!--清除按钮-->
						<img src="/images/senseracing/Icon_TextFiled_Clear.png" class="clear-text" alt="" />
							<!--提示信息-->
					    <div class=" ts tishi"><?=Yii::t('app','USERNAME_PWD')?></div>
					</div>
				
					<!--登录按钮-->
					<button style="outline: none;border:0;line-height: 32px;height: 48px;" class="user submit login-dl xx">
						<?=Yii::t('app','LOGIN')?>
					</button>
					 <!--没有账号-->
					<div class="account-box">
						<div class="box">
							<span><?=Yii::t('app','NO_ACCOUNT')?></span>

							<!-- 注册默认邮箱-->
							<a href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reg_email?client_id=44E7F9DC2DE558BFBC5D808E38299999&language=<?=Yii::$app->language?>"><?=Yii::t('app','CREATE_ACCOUNT')?></a>
						</div>
						<div class="box" style="float: right;">
							<a href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reset?client_id=44E7F9DC2DE558BFBC5D808E38299999&language=<?=Yii::$app->language?>"><?=Yii::t('app','FORGET')?></a>
						</div>
	
					</div>
					<!--第三方登录-->
					<div class="account-box other-box">
						<div class="box">
							<span><?=Yii::t('app','LOGIN_WITH')?></span>
	
						</div>
						<div class="box sf" style="float: right;">
							<!-- 微信 -->
							<a href="<?=Yii::$app->params['account_senseplay_url']?>/third_login/oauth_login?type=wechat"></a>
							<!-- google -->
							<a href="<?=Yii::$app->params['account_senseplay_url']?>/third_login/authorize"></a>
						</div>
	
					</div>
					
					 
					
				</section>
		
		
			</form>
		</section>
	
<script src="/js/senseracing/mui.min.js"></script>

<script>
	mui.init({
		statusBarBackground: '#f7f7f7',
		gestureConfig: {
			doubletap: true
		},
	});
	mui.plusReady(function() {
		//仅支持横屏显示 竖屏显示portrait-secondary
		plus.screen.lockOrientation("landscape-primary");
		main = plus.webview.currentWebview();
	});
	//初始化，并预加载webview模式的选项卡			
	function preload () {
		mui.preload({
			url:'<?=Yii::$app->params['account_senseplay_url']?>/sdk/reg_email?client_id=44E7F9DC2DE558BFBC5D808E38299999',
		
		});

		mui.preload({
			url:"<?=Yii::$app->params['account_senseplay_url']?>/sdk/reset?client_id=44E7F9DC2DE558BFBC5D808E38299999l",
		
		});
	};


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
            $('div.tishi').delay(2000).hide(0);
            return false;
        }

        if(!username){
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }


        if(isPhoneVerify(username) || isEmailVerify(username)){
            //处理其他信息
        }else{
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');  // 提示账号或密码错误new
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }

        if(!pwd){
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }
        //密码不含有空格
        if (pwd.indexOf(" ") != -1) {
            $('.tishi').html('<?=Yii::t('app','USERNAME_PWD')?>');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
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
        $('div.tishi').delay(2000).hide(0);
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
            return false;
        }

        return true;
    }



	//清除按钮的出现
	$('#pwd').focus(function(){
		$('.clear-text').show();

	})

	//清除内容
	$('.clear-text').click(function(){
		$('#pwd').val('');
		$('.clear-text').hide();
	})

</script>
</body>
</html>