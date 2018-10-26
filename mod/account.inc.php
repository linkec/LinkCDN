<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
switch($act){
	case'signin':
		if($task=='login'){
			form_auth(gpc('formhash','P',''),formhash());
			$email = trim(gpc('email','P',''));
			$password = gpc('password','P','');
			$md5_pwd = md5($password);
			$remember = (int)gpc('remember','P',0);
			
			if(checklength($password,6,20)){
				$error = true;
				$out['pwd_hint'] = '请输入6-20位的密码';
			}
			
			$rs = $db->fetch_one_array("select * from {$tpf}users where email='$email' limit 1");
			if(!$rs){
				$error = true;
				$out['email_hint'] = '您所登录的账户不存在。';
			}else{
				if($md5_pwd != $rs['password']){
					$error = true;
					$out['pwd_hint'] = '您输入的帐号密码不匹配。';
				}elseif($rs['is_locked']){
					$error = true;
					$out['email_hint'] = '您所登录的用户已被锁定，请联系客户服务。';
				}else{
					$userid = (int)$rs['userid'];
				}
			}
			if(!$error){
				$out['status'] = 'success';
				$_SESSION['uid'] = $userid;
				// $ins = array(
					// 'email_to'=>$email,
					// 'status'=>'pendding',
					// 'title'=>$settings['site_title'].' 用户注册验证邮箱',
					// 'body'=>$email_body,
					// 'in_time'=>$timestamp,
				// );
				// $db->query("insert into {$tpf}email set ".$db->sql_array($ins).";");
				// $db->query_unbuffered("UPDATE {$tpf}users SET last_login_ip='$onlineip',last_login_time='$timestamp' WHERE uid='$userid'");
				echo json_encode($out);
			}else{
				$out['status'] = 'fail';
				echo json_encode($out);
			}
		}else{
			if($app_uid){
				header('location:/home');
			}
			require_once template_echo("$mod/$act",$user_tpl_dir);
		}
		break;
	case 'logout':
		unset($_SESSION['uid']);
		header('location:/');
		break;
	case 'signup':
		if($task=='reg'){
			// error_reporting(1);
			form_auth(gpc('formhash','P',''),formhash());
			$username = trim(gpc('username','P',''));
			$password = trim(gpc('password','P',''));
			$confirm_password = trim(gpc('confirm_password','P',''));
			$email = trim(gpc('email','P',''));
			$phone = trim(gpc('phone','P',''));
			if(!is_numeric($phone) || strlen($phone)<11){
				$error = true;
				$sysmsg['phone'] = '请输入正确的手机号码';
			}else{
				$rs = $db->result_first("select count(*) from {$tpf}users where phone='$phone'");
				if($rs){
					$error = true;
					$sysmsg['phone'] = '手机号码已经注册，请重试。';
				}
				unset($rs);
			}
			if(checklength($username,2,60)){
				$error = true;
				$sysmsg['username'] = '请输入正确的用户名';
			}elseif(is_bad_chars($username)){
				$error = true;
				$sysmsg['username'] = '您输入的用户名中有非法字符';
			}else{
				$rs = $db->fetch_one_array("select username from {$tpf}users where username='$username' limit 1");
				if($rs){
					if(strcasecmp($username,$rs['username']) ==0){
						$error = true;
						$sysmsg['username'] = '用户名已经存在，请重试。';
					}
				}
				unset($rs);
			}
			if(checklength($password,6,20)){
				$error = true;
				$sysmsg['password'] = '请输入6-20位的密码';
			}else{
				if($password == $confirm_password){
					$md5_pwd = md5($password);
				}else{
					$error = true;
					$sysmsg['password'] = '您两次输入的密码不一致。';
				}
			}
			if(!checkemail($email)){
				$error = true;
				$sysmsg['email'] = '请输入正确的邮箱地址';
			}else{
				$rs = $db->fetch_one_array("select email from {$tpf}users where email='$email' limit 1");
				if($rs){
					if(strcasecmp($email,$rs['email']) ==0){
						$error = true;
						$sysmsg['email'] = '该邮箱已经被使用了。';
					}
					unset($rs);
				}
			}
			// exit($upline_uid);
			if(!$error){
				$ins = array(
					'username'=>$username,
					'password'=>$md5_pwd,
					'secure_pwd'=>$md5_pwd,
					'email'=>$email,
					'reg_time'=>$timestamp,
					'phone'=>$phone,
					'reg_ip'=>$onlineip,
				);
				$db->query("insert into {$tpf}users set ".$db->sql_array($ins).";");
				$userid = $db->insert_id();
				
				// $email_content_url = $settings['website_url']."account/verify/$verify_code";
				// $email_content_body = '请点击以下链接以完成验证邮箱。';
				// $email_body = get_email_tpl('default',$email_content_body,$email_content_url);
				
				// $ins = array(
					// 'email_to'=>$email,
					// 'status'=>'pendding',
					// 'title'=>$settings['site_title'].' 用户注册验证邮箱',
					// 'body'=>$email_body,
					// 'in_time'=>$timestamp,
				// );
				// $db->query("insert into {$tpf}email set ".$db->sql_array($ins).";");
				
				// $userinfo_hash = md5('vbqo34qbvy23hcz'.$userid);
				// $cookie_info = "$userid|$userinfo_hash";
				
				// app_setcookie($session_cookie_name,$cookie_info,0,'/',$settings['cookie_domain']);
				$_SESSION['uid'] = $userid;
				header("location:/home");
				exit;
			}
		}
		require_once template_echo("$mod/$act",$user_tpl_dir);
		break;
	case'forget':
		if($task=='forget'){
			form_auth(gpc('formhash','P',''),formhash());
			$email = trim(gpc('email','P',''));
			if(!checkemail($email)){
				$error = true;
				$sysmsg = '× 请输入正确的邮箱地址';
			}else{
				$rs = $db->fetch_one_array("select userid,gid,username,password,email,is_locked from {$tpf}users where email='$email' limit 1");
			}
			if($rs['email']){
				$last_time = $timestamp - 600;
				$rs = $db->result_first("SELECT count(*) FROM {$tpf}email WHERE email_to='$email' and in_time>$last_time");
				if($rs){
					$error = true;
					$sysmsg = '× 十分钟内只能找回一次密码。';
				}
			}else{
				$error = true;
				$sysmsg = '× 您输入的邮箱不匹配任何用户。';
			}
			if(!$error){
				$reset_code = md5('uidact'.$rs['userid'].rand(999,9999));
				$db->query_unbuffered("update {$tpf}users set reset_code='$reset_code' WHERE email='$email'");
				$email_content_url = $settings['website_url']."account/reset/$reset_code";
				$email_content_body = '请点击以下链接完成验证邮箱并找回密码。';
				$email_body = get_email_tpl('default',$email_content_body,$email_content_url);
				
				$ins = array(
					'email_to'=>$email,
					'status'=>'pendding',
					'title'=>'沸点云 用户找回密码验证邮件',
					'body'=>$email_body,
					'in_time'=>$timestamp,
				);
				$db->query("insert into {$tpf}email set ".$db->sql_array($ins).";");
				$sysmsg = '验证邮件发送成功，请查收。';
			}
		}
		require_once template_echo("$mod/$act",$user_tpl_dir);
		break;
	case'reset':
		if($task=='reset'){
			$code = trim(gpc('code','P',''));
			$password = trim(gpc('password','P',''));
			$confirm_password = trim(gpc('password2','P',''));
			if(!$code){
				$error = true;
				$sysmsg = '× 重置码不正确，请重试。';
			}
			if(checklength($password,6,20)){
				$error = true;
				$sysmsg = '× 请输入6-20位的密码';
			}else{
				if($password == $confirm_password){
					$md5_pwd = md5($password);
				}else{
					$error = true;
					$sysmsg = '× 您两次输入的密码不一致。';
				}
			}
			$rs = $db->fetch_one_array("SELECT * FROM {$tpf}users WHERE reset_code='$code'");
			if(!$rs){
				$error = true;
				$sysmsg = '× 重置码不正确，请重试。';
			}
			if(!$error){
				$reset_ok = true;
				$db->query_unbuffered("update {$tpf}users set reset_code='',password='$md5_pwd' where userid='$rs[userid]'");
			}
		}else{
			$code = trim($hiconsole_req[3]);
			if(!$code){
				$error = true;
				$sysmsg = '× 重置码不正确，请重试。';
			}
			if(!$error){
				$rs = $db->fetch_one_array("SELECT * FROM {$tpf}users WHERE reset_code='$code'");
				if(!$rs){
					$error = true;
					$sysmsg = '× 重置码不正确，请重试。';
				}
			}
		}
		
		require_once template_echo("mod/$mod/$act",$user_tpl_dir);
		break;
	case'verify':
		$code = trim($hiconsole_req[3]);
		$rs = $db->fetch_one_array("SELECT * FROM {$tpf}users WHERE verify_code='$code'");
		if($rs){
			if($rs['is_activated']==1){
				echo '解除绑定成功';
				$db->query_unbuffered("UPDATE {$tpf}users SET is_activated=0,verify_code='' WHERE userid='$rs[userid]'");
			}else{ 
				echo '验证成功';
				$db->query_unbuffered("UPDATE {$tpf}users SET is_activated=1,verify_code='' WHERE userid='$rs[userid]'");
			}
		}else{
			echo '验证码不存在';
		}
		break;
	case'signout':
		app_setcookie($session_cookie_name,'',0,'/',$settings['cookie_domain']);
		header("location:/");
		break;
}
?>