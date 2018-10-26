<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>{$title}</title>
<meta name="keywords" content="{$settings['meta_keywords']}">
<meta name="description" content="{$settings['meta_description']}">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="/static/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="/static/js/login.js"></script>
<link href="/static/css/login2.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>{$settings['site_title']}<sup>XDDrive</sup></h1>

<div class="login" style="margin-top:50px;">
    
    <div class="header">
        <div class="switch" id="switch"><a class="switch_btn_focus" id="switch_qlogin" href="javascript:void(0);" tabindex="7">重置密码</a>
			<a class="switch_btn" id="switch_login" href="/account/signin" tabindex="8">快速登录</a>
			<div class="switch_bottom" id="switch_bottom" style="position: absolute; width: 64px; left: 0px;"></div>
        </div>
    </div>    
<!--#if($error && $task!='reset'){#-->
	<div class="web_qr_login" id="web_qr_login" style="display: block; height: 152px;"> 
		<div class="web_login" id="web_login">
		   <div class="login-box">
			<div class="login_form">
				<div id="userCue" class="cue">
				<font color="red"><b>{$sysmsg}</b></font>
				</div>
				<div style="padding-left:50px;margin-top:20px;"><a href="/account/forget" style="width:150px;line-height: 40px;" class="button_blue">重新找回密码</a></div>
		   </div>
		 </div>
		</div>
	</div>
<!--#}elseif($reset_ok){#-->
	<div class="web_qr_login" id="web_qr_login" style="display: block; height: 152px;"> 
		<div class="web_login" id="web_login">
		   <div class="login-box">
			<div class="login_form">
				<div id="userCue" class="cue">
				<font color="red"><b>密码修改成功</b></font>
				</div>
				<div style="padding-left:50px;margin-top:20px;"><a href="/account/signin" style="width:150px;line-height: 40px;" class="button_blue">立即登录</a></div>
		   </div>
		 </div>
		</div>
	</div>
<!--#}else{#-->
    <div class="qlogin" id="qlogin" style="display: block; ">
   
    <div class="web_login">
		<form name="form2" id="regUser" accept-charset="utf-8"  action="/account/reset" method="post">
	      <input type="hidden" name="task" value="reset"/>
		  <input type="hidden" name="code" value="{$code}"/>
		  <input type="hidden" name="formhash" value="{$formhash}"/>
        <ul class="reg_form" id="reg-ul">
        		<div id="userCue" class="cue">
					<!--#if($sysmsg){#-->
					<font color="red"><b>{$sysmsg}</b></font>
					<!--#}else{#-->
					请牢记您新输入的密码
					<!--#}#-->
				</div>
                <li>
                <label for="passwd" class="input-tips2">新的密码：</label>
                    <div class="inputOuter2">
                        <input type="password" id="passwd"  name="password" maxlength="32" class="inputstyle2"/>
                    </div>
                    
                </li>
                <li>
                <label for="passwd2" class="input-tips2">确认密码：</label>
                    <div class="inputOuter2">
                        <input type="password" id="password2" name="password2" maxlength="32" class="inputstyle2" />
                    </div>
                </li>
                <li>
                    <div class="inputArea">
                        <input type="submit" style="margin-top:10px;margin-left:85px;" class="button_blue" value="立即修改"/>
                    </div>
                    
                </li><div class="cl"></div>
            </ul></form>
           
    
    </div>
   
    
    </div>
<!--#}#-->
</div>
<div class="jianyi">*推荐使用ie8或以上版本ie浏览器或Chrome内核浏览器访问本站</div>
</body></html>