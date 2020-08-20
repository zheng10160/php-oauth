<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!--禁止浏览器缓存当前页面的内容和数据-->
	<meta http-equiv="Pragma" content="no-cache">
	<!--浏览器每次请求页面都会访问服务器-->
	<meta http-equiv="Cache-Control" content="no-store" />
	<!--缓存设置-->
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
	<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
	<meta http-equiv="expires" content="0">

    <title>senseplayApp</title>
    <!-- js>public下 -->
    <script src='/js/public/rem.js' type="text/javascript" charset="utf-8" async defer></script>
    <!-- css>public下 -->
    <link href="/css/public/base.css" rel="stylesheet" />
    <!-- iconfonts  在 css>fonts下-->
    <link href="/css/fonts/iconfont.css" rel="stylesheet" />
    <script src="/css/fonts/iconfont.js"></script>
    <!-- 在 css > senseplayapp下 -->
    <link href="/css/senseplayapp/login.css" rel="stylesheet" />
    <!-- js>public下 -->
    <script src="/js/public/jquery-3.1.1.min.js"></script>

</head>
<body >
		<!--提交信息-->
		<section class="big-wrap" style="overflow-y:scroll">

 			<form action="/oauth/authorize?response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>&language=<?=Yii::$app->language?>"
				  method="post" onsubmit="return toVaild(this);">
				<input type="hidden" name="authorized" value="yes">
				<section class="login-form">
                    <header class=" header-nav">
                        <span class="font_family icon-Clip back-btn" style="transform: rotateY(180deg);"></span>
                        <a href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reg_phone?client_id=87D5226816CEB915441E6452E6AE13F6&language=<?=Yii::$app->language?>" class='sl' >注册新账号</a>
                    </header>
                    <!-- 标题 -->
                    <h4 class='login-denglu sl' >登录</h4>
                    <span class='dl-des sl dl-des-dl' >用SENSEPLAY账号登录</span>
                    <!-- 手机号 -->
                    <span class='input-des dl-des'>请输入手机号</span>
					<div class="user" style="position:relative;height:40px;position: relative;">
						<input type="text" name="username" id="u_p" class="mui-input-clear mui-input input-box sl" placeholder="" autocomplete="off" maxlength="11"/>
                       <span class="font_family icon-close_solid clear-text clear-user" ></span>
					</div>

					<!--密码-->
                    <span class='input-des dl-des'>请输入密码</span>
					<div class="user" style="position:relative;height:40px;position: relative;">
						<input  name="pwd" maxlength="20" id="pwd"  type="password" class="mui-input-clear mui-input input-box sl"  placeholder="" autocomplete="off"/>
						<!--清除按钮-->
						<span class="font_family icon-invisible clear-text clear-pwd" ></span>
						<!--提示信息-->
					    <div class=" ts tishi">账号密码不匹配</div>
					</div>
					 <!--没有账号-->
					<div class="account-box">
						<div class="box" style="float: left;">
							<a href="<?=Yii::$app->params['account_senseplay_url']?>/sdk/reset_phone?client_id=87D5226816CEB915441E6452E6AE13F6&language=<?=Yii::$app->language?>">忘记密码</a>
						</div>
					</div>

                    <!--登录按钮-->
                    <button style="outline: none;border:0;line-height: 32px;height: 48px;background:#14c867;" class="user submit login-dl xx">
                        登录
                    </button>
					<!--第三方登录-->
					<div class="account-box other-box">
						<div class="box">
							<span>其他方式登录</span>
						</div>
						<a class='wechat' href='javascript:;'>
                            <svg class="icon" aria-hidden="true">
                              <use xlink:href="#icon-wechat_color"></use>
                            </svg>
                        </a>
					</div>

				</section>
			</form>
		</section>
<script src="/js/senseplayapp/login.js"></script>
<script>

    /*
    点击微信登录调取unity 微信 登录
     */
     $('.wechat').on('tap',function(){
        window.location.href = 'uniwebview://game-over?wechat=';

     })

    // http://test.auth.senseplay.com/oauth/authorize?client_id=87D5226816CEB915441E6452E6AE13F6&redirect_uri=http://test.account.senseplay.com/game/oauth&state=senseplay&language=en-US

    /**
     * 与u3d交互事件 左上角发返回事件
     */

    $(".back-btn").on("tap",function(){
         // window.location.href = 'uniwebview://game-over?back=';
         window.location.href='back.com';
    });
    /*
    end
     */


	/*验证表单字段值的合法性*/
	// 点击登录按钮
     function toVaild()
	 {
    	var username = $('#u_p').val();//用户名称
        var pwd = $('#pwd').val();
        var reg = /^[1][3,4,5,7,8][0-9]{9}$/;

        if(username==""){
        	$('.tishi').html('用户名不能为空');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }
        if(!reg.test(username)){
            $('.tishi').html('用户名格式不正确');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }
        if(pwd==''){
        	$('.tishi').html('密码不能为空');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }

        if(!username){
            $('.tishi').html('账号密码不匹配');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }


        if(isPhoneVerify(username)){
            //处理其他信息
        }else{
            $('.tishi').html('账号密码不匹配');  // 提示账号或密码错误new
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }

        if(!pwd){
            $('.tishi').html('密码不正确');
            $('.tishi').show();
            $('div.tishi').delay(2000).hide(0);
            return false;
        }
        //密码不含有空格
        if (pwd.indexOf(" ") != -1) {
            $('.tishi').html('密码中不能含有空格');
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
        $('.tishi').html('账号和密码不匹配');//用户名和密码不匹配
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

</script>
</body>
</html>