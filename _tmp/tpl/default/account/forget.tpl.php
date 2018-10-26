<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-05-11 01:00:06

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>找回密码 | 睿速 - 睿智的加速选择</title>


    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>


    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="<?=$static_url?>/css/bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="<?=$static_url?>/css/nifty.min.css" rel="stylesheet">


    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="<?=$static_url?>/css/demo/nifty-demo-icons.min.css" rel="stylesheet">


        
    <!--Demo [ DEMONSTRATION ]-->
    <link href="<?=$static_url?>/css/demo/nifty-demo.min.css" rel="stylesheet">


    <!--Magic Checkbox [ OPTIONAL ]-->
    <link href="<?=$static_url?>/plugins/magic-check/css/magic-check.min.css" rel="stylesheet">

    <!--Bootstrap Validator [ OPTIONAL ]-->
    <link href="<?=$static_url?>/plugins/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">






    
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="<?=$static_url?>/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=$static_url?>/plugins/pace/pace.min.js"></script>


    <!--jQuery [ REQUIRED ]-->
    <script src="<?=$static_url?>/js/jquery.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="<?=$static_url?>/js/bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="<?=$static_url?>/js/nifty.min.js"></script>
    
    <!--Bootstrap Validator [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>




    
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
		                <h3 class="h4 mar-no">找回密码</h3>
		            </div>
		            <form id="login-form" action="/account/signin">
						<input type="hidden" name="task" value="login">
						<input type="hidden" name="formhash" value="<?=$formhash?>">
		                <div class="form-group">
		                    <input type="text" class="form-control" placeholder="注册邮箱" autofocus name="email">
		                </div>
		                <button class="btn btn-success btn-block" type="submit">下一步</button>
		            </form>
		        </div>
		
		        <div class="pad-all">
		            <a href="/account/signin" class="btn-link mar-rgt">登录</a>
		            <a href="/account/signup" class="btn-link mar-lft">创建新的帐号</a>
		
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