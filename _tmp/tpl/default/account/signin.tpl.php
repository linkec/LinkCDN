<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2018-10-26 20:17:13

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[LinkCDN] Access Denied!'); ?>
<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>登录</title>
    <link href="<?=STATIC_PATH?>/dist/css/style.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url(<?=STATIC_PATH?>/assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box">
                <div id="loginform">
                    <div class="logo">
                        <span class="db"><img src="<?=STATIC_PATH?>/assets/images/logo-icon.png" alt="logo" /></span>
                        <h5 class="font-medium m-b-20">登录管理平台</h5>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form class="form-horizontal m-t-20" id="loginform" action="index.html">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder="登录邮箱" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder="密码" aria-label="Password" aria-describedby="basic-addon1">
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox">
                                            <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i class="fa fa-lock m-r-5"></i> 忘记密码？</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-block btn-lg btn-info" type="submit">登录</button>
                                    </div>
                                </div>
                                <div class="form-group m-b-0 m-t-10">
                                    <div class="col-sm-12 text-center">
                                        注册即享10G免费流量 <a href="/account/signup" class="text-info m-l-5"><b>注册</b></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="recoverform">
                    <div class="logo">
                        <span class="db"><img src="<?=STATIC_PATH?>/assets/images/logo-icon.png" alt="logo" /></span>
                        <h5 class="font-medium m-b-20">找回密码</h5>
                        <span>请输入您的登录邮箱，并点击发送按钮</span>
                    </div>
                    <div class="row m-t-20">
                        <form class="col-12" action="/account/reset">
                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control form-control-lg" type="email" required="" placeholder="登录邮箱">
                                </div>
                            </div>
                            <div class="row m-t-20">
                                <div class="col-12">
                                    <button class="btn btn-block btn-lg btn-danger" type="submit" name="action">发送</button>
                                </div>
                            </div>
                        </form>
						<div class="form-group m-b-0 m-t-10">
							<div class="col-sm-12 text-center">
								返回<a  href="javascript:void(0)" id="to-login" class="text-info m-l-5"><b>登录</b></a> 或 <a href="/account/signup" class="text-info m-l-5"><b>注册</b></a>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?=STATIC_PATH?>/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="<?=STATIC_PATH?>/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?=STATIC_PATH?>/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#to-login').on("click", function() {
        $("#recoverform").slideUp();
        $("#loginform").fadeIn();
    });
    </script>
</body>
</html>