<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册 | 睿速 - 睿智的加速选择</title>


    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>


    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="{$static_url}/css/bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="{$static_url}/css/nifty.min.css" rel="stylesheet">


    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="{$static_url}/css/demo/nifty-demo-icons.min.css" rel="stylesheet">


        
    <!--Demo [ DEMONSTRATION ]-->
    <link href="{$static_url}/css/demo/nifty-demo.min.css" rel="stylesheet">


    <!--Magic Checkbox [ OPTIONAL ]-->
    <link href="{$static_url}/plugins/magic-check/css/magic-check.min.css" rel="stylesheet">

    <!--Bootstrap Validator [ OPTIONAL ]-->
    <link href="{$static_url}/plugins/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">






    
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="{$static_url}/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="{$static_url}/plugins/pace/pace.min.js"></script>


    <!--jQuery [ REQUIRED ]-->
    <script src="{$static_url}/js/jquery.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="{$static_url}/js/bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="{$static_url}/js/nifty.min.js"></script>
    
    <!--Bootstrap Validator [ OPTIONAL ]-->
    <script src="{$static_url}/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>




    
    <!--=================================================

    REQUIRED
    You must include this in your project.


    RECOMMENDED
    This category must be included but you may modify which plugins or components which should be included in your project.


    OPTIONAL
    Optional plugins. You may choose whether to include it in your project or not.


    DEMONSTRATION
    This is to be removed, used for demonstration purposes only. This category must not be included in your project.


    SAMPLE
    Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


    Detailed information and more samples can be found in the document.

    =================================================-->
        

</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->

<body>
	<div id="container" class="cls-container">
		
		<!-- BACKGROUND IMAGE -->
		<!--===================================================-->
		<div id="bg-overlay"></div>
		
		
		<!-- LOGIN FORM -->
		<!--===================================================-->
		<div class="cls-content">
		    <div class="cls-content-sm panel">
		        <div class="panel-body">
		            <div class="mar-ver pad-btm">
		                <h3 class="h4 mar-no">RayCDN Console</h3>
		                <p class="text-muted">立即注册睿速帐号</p>
		            </div>
		            <form action="/account/signup" method="post">
						<input type="hidden" name="task" value="reg">
						<input type="hidden" name="formhash" value="{$formhash}">
		                <div class="row">
		                    <div class="col-sm-12">
		                        <div class="form-group">
		                            <input type="text" class="form-control" placeholder="邮箱（用于登陆）" name="email" value="{$email}">
									<!--#if($sysmsg['email']){#--><small class="help-block text-danger">{$sysmsg['email']}</small><!--#}#-->
		                        </div>
		                    </div>
		                    <div class="col-sm-12">
		                        <div class="form-group">
		                            <input type="text" class="form-control" placeholder="手机号码" name="phone" value="{$phone}">
									<!--#if($sysmsg['phone']){#--><small class="help-block text-danger">{$sysmsg['phone']}</small><!--#}#-->
		                        </div>
		                    </div>
		                    <div class="col-sm-12">
		                        <div class="form-group">
		                            <input type="text" class="form-control" placeholder="用户名" name="username" value="{$username}">
									<!--#if($sysmsg['username']){#--><small class="help-block text-danger">{$sysmsg['username']}</small><!--#}#-->
		                        </div>
		                    </div>
		                    <div class="col-sm-12">
		                        <div class="form-group">
		                            <input type="password" class="form-control" placeholder="密码" name="password">
									<!--#if($sysmsg['password']){#--><small class="help-block text-danger">{$sysmsg['password']}</small><!--#}#-->
		                        </div>
							</div>
		                    <div class="col-sm-12">
		                        <div class="form-group">
		                            <input type="password" class="form-control" placeholder="确认密码" name="confirm_password">
		                        </div>
							</div>
		                </div>
		                <div class="checkbox pad-btm text-left">
		                    <input id="demo-form-checkbox" class="magic-checkbox" type="checkbox">
		                    <label for="demo-form-checkbox">我已阅读并同意 <a href="#" class="btn-link">用户使用条款和协议</a></label>
		                </div>
		                <button class="btn btn-primary btn-block" type="submit">立即注册</button>
		            </form>
		        </div>
		        <div class="pad-all">
		            <a href="/account/signin" class="btn-link mar-lft">已有帐号 ？立即去登录</a>
		
		            <div class="media pad-top bord-top">
		                <div class="media-body text-center">
		                    RayCDN Ltd. @2017
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<!--===================================================-->
		
		
	</div>
	<!--===================================================-->
	<!-- END OF CONTAINER -->
		<script type="text/javascript">
			$('#login-form').bind('submit',function(){
					var form = $('#login-form');
					$('button').attr('disabled',"true");
					  $.ajax({
							url: form.attr('action'),
							type: "POST",
							data: form.serialize(),
							dataType:'json',
							success: function (data) {
							  if(data.status=='success'){
								  location = '/';
							  }else{
								  if(data.email_hint){
									 $('#email_state').removeClass('state-success'); 
									 $('#email_state').addClass('state-error'); 
									 $('#email_state').next('em').html(data.email_hint);
									 $('#email_state').next('em').css('display','block');
								  }
								  if(data.pwd_hint){
									 $('#pwd_state').removeClass('state-success'); 
									 $('#pwd_state').addClass('state-error'); 
									 $('#pwd_state').next('em').html(data.pwd_hint);
									 $('#pwd_state').next('em').css('display','block');
								  }
							  }
							  $('button').removeAttr('disabled',"true");
							},
							error: function (jqXhr, textStatus, errorThrown) {
								$('button').removeAttr('disabled',"true");
								alert(errorThrown);
							}
					  });
			    event.preventDefault();
				return false;
			});
		</script>

	</body>
</html>