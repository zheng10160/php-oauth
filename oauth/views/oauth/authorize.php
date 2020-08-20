<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>注册</title>
    <link rel="stylesheet" href="<?= Url::base() ?>/assets/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= Url::base() ?>/css/signin.css"/>
    <link rel="stylesheet" href="<?= Url::base() ?>/css/style.css"/>
    <script src="<?= Url::base() ?>/assets/assets/js/jquery-3.2.1.min.js"></script>

</head>

<body>
<div class="signin">
    <div class="signin-head">
        <!-- <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA4NyA1MCI+PGcgZmlsbD0iIzRDNEM0QyIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDAuOTA4LjI4NWgxNEw0Ny42MzQgMzAuMjljLTEuNCA1Ljc5LTUuNzY2IDcuMTgzLTkuODAzIDcuMTgzSDUuNTdjLTMuNTU1IDAtNi41MzEtMS40OS00LjkyLTguMTc4bDIuOTA4LTExLjk2OWMxLjQ3LTYuMDcgNi4wNDgtNy40NTUgOS4zNTgtNy40NTVoMjIuNTJsLTEuODE3IDcuNDhIMjIuMTIyYy0xLjY5IDAtMi42MTYuMzU4LTMuMDg3IDIuMzAzbC0xLjg1OCA3LjY0NWMtLjY2MSAyLjczOC4zMSAyLjkyNyAyLjM0NiAyLjkyN2gxMC41MzRjMS45MjggMCAzLjYyNi0uMTIgNC40Ni0zLjU2M0w0MC45MDguMjg1ek03Mi40NzYgOS44NjFsLTYuNTg4IDI3LjYwOGgxMy42MDFsNi41ODUtMjcuNjA4SDcyLjQ3NiIvPjxwYXRoIGQ9Ik01NS44MDYgOS44NjJsLTUuNTQ0IDIyLjc3OWMtMS4yNTIgNS4xNy00Ljg2MyA2LjkxMy04LjQ5NCA3LjIxMi0uMzYuMDQ1LTEwLjUwNS0uMDA2LTEwLjUwNS0uMDA2bC0yLjI4MiA5LjM1N2gxOS4wNzZjNC45OTUgMCAxMi4zNzMtMi41MiAxNS4wMTEtMTMuMzkxbDYuMzM3LTI1Ljk1aC0xMy42Ii8+PC9nPjwvc3ZnPg==" alt="" class="img-circle">-->
    </div>
    <!-- PAGE CONTENT BEGINS -->
 <!--   <form  class="form-signin" role="form" id="user-form" action="./index.php?r=oauth/login&response_type=code&client_id=<?/*=$client_id*/?>&redirect_uri=<?/*=$redirect_uri*/?>&state=<?/*=$state*/?>" method="post">-->
    <form  class="form-signin" role="form" id="user-form" action="/oauth/authorize&response_type=code&client_id=testclient&redirect_uri=http://www.gw_user.com.cn/index.php?r=clientoauth/oauth&state=xsxs" method="post">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken?>">

       <input name="authorized" type="radio" value="yes" />
        <input name="authorized" type="radio" value="no" />

        <div>
            <button class="ajaxForm btn btn-lg btn-warning btn-block" type="submit">确认授权</button>
        </div>
    </form>
</div>

</body>
</html>