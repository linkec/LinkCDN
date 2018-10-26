<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
switch($act){
	case'profile':
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'add_limit_rule':
	case'edit_limit_rule':
		if($task=='add'){
			$fee = gpc('fee','P',0);
			$fee = is_numeric($fee) ? $fee : number_format($fee,2,'.','');
			$domain = trim(gpc('domain','P',0));
			$host = trim(gpc('host','P',0));
			$unit = trim(gpc('unit','P',0));
			if(!$fee||!$domain||!$host||!$unit){
				show_error('参数不完整');
			}
			$rs = $db->result_first("SELECT COUNT(*) FROM {$tpf}limit_rules WHERE userid='$app_uid' and host='$host' and domain='$domain'");
			if($rs){
				show_error('规则已经存在');
			}
			$db->query_unbuffered("INSERT INTO {$tpf}limit_rules SET fee='$fee',host='$host',domain='$domain',unit='$unit',userid='$app_uid'");
			header('location:/settings/limit');
		}elseif($task=='update'){
			$id = (int)gpc('id','P',0);
			$fee = gpc('fee','P',0);
			$fee = is_numeric($fee) ? $fee : number_format($fee,2,'.','');
			$unit = trim(gpc('unit','P',0));
			if(!$fee||!$unit){
				show_error('参数不完整');
			}
			$rule = $db->fetch_one_array("SELECT * FROM {$tpf}limit_rules WHERE userid='$app_uid' and id='$id'");
			if($rule['unit']!=$unit){
				if($unit=='day'){
					$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400";
				}elseif($unit=='week'){
					$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400*7";
				}elseif($unit=='month'){
					$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400*30";
				}
			}
			$db->query_unbuffered("UPDATE {$tpf}limit_rules SET fee='$fee',unit='$unit'$do_sql WHERE id='$id' and userid='$app_uid'");
			header('location:/settings/limit');
		}else{
			if($act=='edit_limit_rule'){
				$id = $hiconsole_req[3];
				$rule = $db->fetch_one_array("SELECT * FROM {$tpf}limit_rules WHERE userid='$app_uid' and id='$id'");
				if(!$rule){
					echo '错误：规则不存在';
					exit;
				}
			}else{
				$rule['fee'] = '10.00';
				$rule['unit'] = 'day';
			}
			$q = $db->query("SELECT domain FROM {$tpf}mysites WHERE userid='$app_uid' and status='success'");
			$sites = array();
			while($rs=$db->fetch_array($q)){
				$sites[] = $rs;
			}
			require_once template_echo("settings/add_limit_rule",$user_tpl_dir);
		}
		break;
	case'limit':
		$q = $db->query("SELECT * FROM {$tpf}limit_rules WHERE userid='$app_uid'");
		$rules = array();
		while($rs=$db->fetch_array($q)){
			if($rs['unit']=='day'){
				$rs['unit'] = '天';
			}elseif($rs['unit']=='week'){
				$rs['unit'] = '周';
			}elseif($rs['unit']=='month'){
				$rs['unit'] = '月';
			}
			$rs['start_time'] = date('Y-m-d H:i',$rs['start_time']);
			$rs['end_time'] = date('Y-m-d H:i',$rs['end_time']);
			$rules[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'topup':
		require_once template_echo("topup",$user_tpl_dir);
		break;
	case'pay_topup':
		$pay_method = trim(gpc('method','P','alipay'));
		$money = (int)gpc('money','P','alipay');
		// echo 'asd';
		if($money<10){
			exit('金额太小啦，最低充值金额为10元。');
		}
		$order_num = $app_uid.date('YmdHis').random(6);
		$ins = array(
			'userid'=>$app_uid,
			'trade_num'=>$order_num,
			'type'=>'topup',
			'money'=>$money,
			'status'=>'pendding',
			'in_time'=>$timestamp,
		);
		$db->query_unbuffered("INSERT INTO {$tpf}orders SET ".$db->sql_array($ins));
		
		echo suike_pay('RAYCDN充值 '.$money.' 元', $money, $order_num,$pay_method);
		break;
	case'save_phone':
		// require_once template_echo("frame",$user_tpl_dir);
		$phone_num = trim(gpc('phone_num','P',''));
		if(!is_numeric($phone_num) || strlen($phone_num)<11){
			$out['status'] = 'fail';
			$out['msgs'] = '请输入正确的手机号码';
		}else{
			$out['status'] = 'success';
			$db->query_unbuffered("UPDATE {$tpf}users SET phone='$phone_num' WHERE userid='$app_uid'");
		}
		echo json_encode($out);
		break;
	case'del_limit_rule':
		$id = (int)$hiconsole_req[3];
		$out['status'] = 'success';
		$db->query_unbuffered("DELETE FROM {$tpf}limit_rules WHERE userid='$app_uid' and id='$id'");
		echo json_encode($out);
		break;
	case'reset_limit_rule':
		$id = (int)$hiconsole_req[3];
		$out['status'] = 'success';
		$rule = $db->fetch_one_array("SELECT * FROM {$tpf}limit_rules WHERE userid='$app_uid' and id='$id'");
		if($rule['unit']=='day'){
			$do_sql = ",end_time=$timestamp+86400";
		}elseif($rule['unit']=='week'){
			$do_sql = ",end_time=$timestamp+86400*7";
		}elseif($rule['unit']=='month'){
			$do_sql = ",end_time=$timestamp+86400*30";
		}
		$db->query_unbuffered("UPDATE {$tpf}limit_rules SET used_fee=0,start_time=$timestamp{$do_sql} WHERE userid='$app_uid' and id='$id'");
		echo json_encode($out);
		break;
	case'load_host':
		$domain = trim($hiconsole_req[3]);
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and domain='$domain'");
		if(!$site){
			$error = false;
			$msg[] = '该域名不属于你';
		}else{
			//获取主机记录
			if($site['cdn_type']=='ns'){
				$q = $db->query("SELECT * FROM {$tpf}site_dns WHERE site_id='{$site['id']}' and userid='$app_uid'");
			}elseif($site['cdn_type']=='cn'){
				$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE site_id='{$site['id']}' and userid='$app_uid'");
			}
			$records = array();
			while($rs=$db->fetch_array($q)){
				if(in_array($rs['dns_type'],array('A','CNAME'))){
					$records[$rs['dns_host']] = $rs['dns_host'];
				}
			}
			foreach($records as $v){
				$hosts[] = $v;
			}
		}
		if(!$hosts){
			$out['status'] = 'fail';
			$out['msgs'] = '没有获取到域名记录';
		}else{
			$out['status'] = 'success';
			$out['hosts'] = $hosts;
		}
		echo json_encode($out);
		break;
}
?>