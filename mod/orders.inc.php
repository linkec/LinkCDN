<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
switch($act){
	case'topup':
		$q = $db->query("SELECT * FROM {$tpf}orders WHERE userid='$app_uid'");
		$orders = array();
		while($rs=$db->fetch_array($q)){
			$rs['in_time'] = date('Y-m-d H:i:s',$rs['in_time']);
			switch($rs['type']){
				case'topup':
					$rs['type'] = '账户充值 ' .$rs['money']. ' 元';
					break;
			}
			switch($rs['status']){
				case'pendding':
					$rs['status'] = '<div class="label label-table label-warning">等待付款</div>';
					break;
				case'success':
					$rs['status'] = '<div class="label label-table label-success">充值成功</div>';
					break;
			}
			$rs['money'] = '<span class="text-success text-semibold">+ '.number_format($rs['money'],2).'</span>';
			$orders[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'money_log':
		$q = $db->query("SELECT * FROM {$tpf}money_log WHERE userid='$app_uid'");
		$logs = array();
		while($rs=$db->fetch_array($q)){
			if($rs['money']<0){
				$rs['money'] = '<span class="text-danger text-semibold">- '.number_format($rs['money']*-1,2).'</span>';
			}else{
				$rs['money'] = '<span class="text-success text-semibold">+ '.number_format($rs['money'],2).'</span>';
			}
			$rs['in_time'] = date('Y-m-d H:i:s',$rs['in_time']);
			$tmp = explode('|',$rs['act']);
			switch($tmp[0]){
				case'antiddos_pro':
					$rs['act'] = $tmp[1].' 高级版抗D防护 AnTi-DDoS Pro';
					break;
				case'waf_pro':
					$rs['act'] = $tmp[1].' 高级版网站防护 Waf Pro ';
					break;
			}
			$logs[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'pay_log':
		$start_num = ($pg - 1)*$perpage;
		$total = $db->result_first("SELECT COUNT(*) FROM {$tpf}payment_day WHERE userid='$app_uid'");
		$q = $db->query("SELECT p.*,m.domain FROM {$tpf}payment_day p,{$tpf}mysites m WHERE p.userid='$app_uid' and p.site_id=m.id ORDER BY p.id DESC LIMIT $start_num,$perpage");
		$logs = array();
		while($rs=$db->fetch_array($q)){
			$logs[] = $rs;
		}
		$multipage = omulti($total, $perpage, $pg, "/orders/pay_log/?pg={pg}");
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'pay_log_details':
		$site_id = (int)$hiconsole_req[3];
		// $pg = (int)$hiconsole_req[4];
		$start_num = ($pg - 1)*$perpage;
		$total = $db->result_first("SELECT COUNT(*) FROM {$tpf}payment WHERE userid='$app_uid' and site_id='$site_id'");
		$q = $db->query("SELECT p.*,m.domain FROM {$tpf}payment p,{$tpf}mysites m WHERE p.userid='$app_uid' and p.site_id=m.id and p.site_id='$site_id' ORDER BY p.id DESC LIMIT $start_num,$perpage");
		$logs = array();
		while($rs=$db->fetch_array($q)){
			$logs[] = $rs;
		}
		$multipage = omulti($total, $perpage, $pg, "/orders/pay_log_details/{$site_id}/?pg={pg}");
		require_once template_echo("frame",$user_tpl_dir);
		break;
}
?>