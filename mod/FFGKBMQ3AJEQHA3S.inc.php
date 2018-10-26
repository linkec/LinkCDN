<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!');
// echo 'as';
$node_id = $db->result_first("SELECT id FROM {$tpf}nodes WHERE ip='$onlineip'");
switch($act){
	case'get_version':
		$out['status'] = 'success';
		$out['version'] = '1.72';
		echo json_encode($out);
		break;
	case'get_dns_records':
		$domain = $hiconsole_req[3];
		if($domain){
			$q = $db->query("SELECT * FROM {$tpf}dns_records WHERE zone='$domain'");
		}else{
			$q = $db->query("SELECT * FROM {$tpf}dns_records");
		}
		while($rs=$db->fetch_array($q)){
			if($rs['type']=='MX'){
				$rs2['data'] = array(
					'priority' => $rs['mx_priority'],
					'target' => $rs['data']
					);
			}elseif($rs['type']=='SOA'){
				// data, resp_person, serial, refresh, retry, expire,minimum
				$rs2['data'] = "{$rs['data']} tech.isuike.com {$rs['serial']} {$rs['refresh']} {$rs['retry']} {$rs['expire']} {$rs['minimum']}";
			}else{
				$rs2['data'] = $rs['data'];
			}
			$rs2['ttl']  = $rs['ttl'];
			$rs2['view']  = $rs['view'];
			$rs2['weight']  = $rs['weight'];
			$data[$rs['zone']][$rs['host']][$rs['type']][] = $rs2;
			unset($rs2);
		}
		echo json_encode($data);
		break;
	case'smartDns':
		$data = gpc('data','P','');
		$view = gpc('view','P','');
		foreach($data as $v){
			if($v['pkg_lost']>5 || $v['diff']>150){
				$nodes[] = $v;
			}
		}
		//恢复权重
		$q = $db->query("SELECT * FROM {$tpf}nodes WHERE weight != oweight and views LIKE '%{$view}%'");
		while($rs=$db->fetch_array($q)){
			// $db->query_unbuffered("UPDATE {$tpf}nodes SET weight=oweight WHERE id='{$rs['id']}'");
			// $db->query_unbuffered("UPDATE {$tpf}dns_records SET weight='{$rs['oweight']}' WHERE data='{$rs['ip']}'");
		}
		//修改权重
		if($nodes){
			foreach($nodes as $v){
				// $db->query_unbuffered("UPDATE {$tpf}nodes SET weight=0 WHERE ip='{$v['ip']}'");
				// $db->query_unbuffered("UPDATE {$tpf}dns_records SET weight=0 WHERE data='{$v['ip']}'");
			}
			// $q = $db->query("SELECT * FROM {$tpf}nodes WHERE area = 6");
			// while($rs=$db->fetch_array($q)){
				// $ins = array(
					// 'node_id'=>$rs['id'],
					// 'task'=>'reloaddns',
					// 'data'=>serialize(array('domain'=>$domain)),
					// 'status'=>'pendding',
					// 'response'=>'',
					// 'in_time'=>$timestamp,
				// );
				// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
			// }
		}
		$out['status'] = 'success';
		$out['nodes'] = $nodes;
		echo json_encode($out);
		break;
	case'get_dns_records_md5':
		$q = $db->query("SELECT * FROM {$tpf}dns_records");
		while($rs=$db->fetch_array($q)){
			if($rs['type']=='MX'){
				$rs2['data'] = array(
					'priority' => $rs['mx_priority'],
					'target' => $rs['data']
					);
			}else{
				$rs2['data'] = $rs['data'];
			}
			$rs2['ttl']  = $rs['ttl'];
			$rs2['view']  = $rs['view'];
			$rs2['weight']  = $rs['weight'];
			$data[$rs['zone']][$rs['host']][$rs['type']][] = $rs2;
			unset($rs2);
		}
		echo md5(json_encode($data));
		break;
	case'pay_ok':
		$sign = MD5($_POST['status'].$_POST['trade_order_no'].'c89491ef6f8395cd67a666767397a7bc');
		if($sign==$_POST['sign']){
			$verify_result = true;
			$trade_status = $_POST['status'];
			$order_num = $_POST['trade_order_no'];
		}else{
			$verify_result = false;
		}
		if($verify_result) {
			if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
				//业务支付成功后的流程
				$order = $db->fetch_one_array("SELECT * FROM {$tpf}orders WHERE trade_num='$order_num'");
				if($order['status']=='pendding'){
					switch($order['type']){
						case'topup':
							$db->query_unbuffered("UPDATE {$tpf}users SET wealth=wealth+{$order['money']} WHERE userid='{$order['userid']}'");
							$db->query_unbuffered("UPDATE {$tpf}orders SET status='success' WHERE id='{$order['id']}'");
							break;
					}
				}
			}
			echo "success";     //API返回接口必须返回success，否则将会导致重复回调。
		}
		 
		break;
	case'cdn_pay_batch':
		$price[1] = 0.2;
		$price[2] = 0.6;
		$price[3] = 1.2;
		$price[4] = 0.4;
		$price[5] = 0.15;
		
		$list = gpc('list','P','');
		if($list){
			$q = $db->query("SELECT * FROM {$tpf}nodes");
			while($rs=$db->fetch_array($q)){
				$nodes[$rs['hostname']] = $rs;
			}
			$paid_ids = array();
			foreach($list as $v){
				$site_id = (int)$v['site_id'];
				$host = trim($v['host']);
				$node_id = trim($v['node_id']);
				$year = (int)$v['year'];
				$month = (int)$v['month'];
				$day = (int)$v['day'];
				$hour = (int)$v['hour'];
				$data = (int)$v['data'];
				if($nodes[$node_id]['area']<=5){
					if($nodes[$node_id]){
						$fee_per_g = $price[$nodes[$node_id]['area']];
					}else{
						$nodes[$node_id]['area'] = 1;
						$fee_per_g = $price[1];
					}
					
					$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE id='{$site_id}'");
					if($site){
						$userid = $site['userid'];
						$payment_id = $db->result_first("SELECT id FROM {$tpf}payment WHERE site_id='{$site_id}' and year='$year' and month='$month' and day='$day' and hour='$hour'");
						if(!$payment_id){
							$ins = array(
								'site_id'=>$site_id,
								'userid'=>$userid,
								'year'=>$year,
								'month'=>$month,
								'day'=>$day,
								'hour'=>$hour,
								'dateline'=>$timestamp,
							);
							$db->query_unbuffered("INSERT INTO {$tpf}payment SET ".$db->sql_array($ins));
							$payment_id = $db->insert_id();
						}
						// echo "payment_id:$payment_id".LF;
						$payment_day_id = $db->result_first("SELECT id FROM {$tpf}payment_day WHERE site_id='{$site_id}' and year='$year' and month='$month' and day='$day'");
						if(!$payment_day_id){
							$ins = array(
								'site_id'=>$site_id,
								'userid'=>$userid,
								'year'=>$year,
								'month'=>$month,
								'day'=>$day,
								'dateline'=>$timestamp,
							);
							$db->query_unbuffered("INSERT INTO {$tpf}payment_day SET ".$db->sql_array($ins));
							$payment_day_id = $db->insert_id();
						}
						$data = $data/1024/1024/1024;
						$fee = round($data*$fee_per_g,4);
						$db->query_unbuffered("UPDATE {$tpf}payment SET fee_{$nodes[$node_id]['area']}=fee_{$nodes[$node_id]['area']}+$fee WHERE id='{$payment_id}'");
						$db->query_unbuffered("UPDATE {$tpf}payment_day SET fee_{$nodes[$node_id]['area']}=fee_{$nodes[$node_id]['area']}+$fee WHERE id='{$payment_day_id}'");
						$db->query_unbuffered("UPDATE {$tpf}users SET wealth=wealth-$fee WHERE userid='{$userid}'");
						$limit = $db->fetch_one_array("SELECT * FROM {$tpf}limit_rules WHERE domain='{$site['domain']}' and (host='$host' or host='*')");
						if($limit){
							if($limit['end_time']<$timestamp){
								if($limit['unit']=='day'){
									$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400";
								}elseif($limit['unit']=='week'){
									$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400*7";
								}elseif($limit['unit']=='month'){
									$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400*30";
								}
								node_tasks($site_id,$host);
								$db->query_unbuffered("UPDATE {$tpf}limit_rules SET used_fee=0{$do_sql} WHERE id='{$limit['id']}'");
							}else{
								$db->query_unbuffered("UPDATE {$tpf}limit_rules SET used_fee=used_fee+$fee WHERE id='{$limit['id']}'");
								$rule = $db->fetch_one_array("SELECT * FROM {$tpf}limit_rules WHERE id='{$limit['id']}'");
								if($rule['used_fee']>=$rule['fee']){
									do_bypass($site_id,$host);
								}
							}
						}
						$user_wealth = $db->result_first("SELECT wealth FROM {$tpf}users WHERE userid='{$userid}'");
						if($user_wealth<0){
							$q = $db->query("SELECT * FROM {$tpf}site_settings s,{$tpf}mysites m WHERE m.id=s.site_id and m.userid={$userid}");
							while($rs=$db->fetch_array($q)){
								// if($rs['cdn_type']=='ns'){
									// $rs['cdn_status'] = $db->result_first("SELECT cdn_status FROM {$tpf}site_dns WHERE id=");
								// }else{
									
								// }
								do_bypass($rs['site_id'],$rs['host']);
							}
						}
					}
					
					unset($site_id);
					unset($host);
					unset($node_id);
					unset($year);
					unset($month);
					unset($day);
					unset($hour);
					unset($data);
					unset($fee_per_g);
					unset($site);
					unset($userid);
					unset($payment_id);
					unset($payment_day_id);
					unset($fee);
					unset($limit);
					unset($user_wealth);
				}
				$paid_ids[] = $v['id'];
			}
		}
		$out['status'] = 'success';
		$out['paid_ids'] = implode(',',$paid_ids);
		echo json_encode($out);
		break;
	case'cdn_pay':
		$price[1] = 0.2;
		$price[2] = 0.6;
		$price[3] = 1.2;
		$price[4] = 0.4;
		$price[5] = 0.15;
		
		// $d_year = date('Y');
		// $d_month = date('m');
		// $d_day = date('d');
		// $d_hour = date('H');
		
		$site_id = (int)gpc('site_id','P','');
		$host = trim(gpc('host','P',''));
		$node_id = trim(gpc('node_id','P',''));
		$year = (int)gpc('year','P','');
		$month = (int)gpc('month','P','');
		$day = (int)gpc('day','P','');
		$hour = (int)gpc('hour','P','');
		$data = (int)gpc('data','P','');
		// var_dump($_POST);
		$q = $db->query("SELECT * FROM {$tpf}nodes");
		while($rs=$db->fetch_array($q)){
			$nodes[$rs['hostname']] = $rs;
		}
		if($nodes[$node_id]){
			$fee_per_g = $price[$nodes[$node_id]['area']];
		}else{
			$nodes[$node_id]['area'] = 1;
			$fee_per_g = $price[1];
		}
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE id='{$site_id}'");
		if($site){
			$userid = $site['userid'];
			$payment_id = $db->result_first("SELECT id FROM {$tpf}payment WHERE site_id='{$site_id}' and year='$year' and month='$month' and day='$day' and hour='$hour'");
			if(!$payment_id){
				$ins = array(
					'site_id'=>$site_id,
					'userid'=>$userid,
					'year'=>$year,
					'month'=>$month,
					'day'=>$day,
					'hour'=>$hour,
					'dateline'=>$timestamp,
				);
				$db->query_unbuffered("INSERT INTO {$tpf}payment SET ".$db->sql_array($ins));
				$payment_id = $db->insert_id();
			}
			// echo "payment_id:$payment_id".LF;
			$payment_day_id = $db->result_first("SELECT id FROM {$tpf}payment_day WHERE site_id='{$site_id}' and year='$year' and month='$month' and day='$day'");
			if(!$payment_day_id){
				$ins = array(
					'site_id'=>$site_id,
					'userid'=>$userid,
					'year'=>$year,
					'month'=>$month,
					'day'=>$day,
					'dateline'=>$timestamp,
				);
				$db->query_unbuffered("INSERT INTO {$tpf}payment_day SET ".$db->sql_array($ins));
				$payment_day_id = $db->insert_id();
			}
			$data = $data/1024/1024/1024;
			$fee = round($data*$fee_per_g,4);
			$db->query_unbuffered("UPDATE {$tpf}payment SET fee_{$nodes[$node_id]['area']}=fee_{$nodes[$node_id]['area']}+$fee WHERE id='{$payment_id}'");
			$db->query_unbuffered("UPDATE {$tpf}payment_day SET fee_{$nodes[$node_id]['area']}=fee_{$nodes[$node_id]['area']}+$fee WHERE id='{$payment_day_id}'");
			$db->query_unbuffered("UPDATE {$tpf}users SET wealth=wealth-$fee WHERE userid='{$userid}'");
			$limit = $db->fetch_one_array("SELECT * FROM {$tpf}limit_rules WHERE domain='{$site['domain']}' and (host='$host' or host='*')");
			if($limit){
				if($limit['end_time']<$timestamp){
					if($limit['unit']=='day'){
						$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400";
					}elseif($limit['unit']=='week'){
						$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400*7";
					}elseif($limit['unit']=='month'){
						$do_sql = ",start_time=$timestamp,end_time=$timestamp+86400*30";
					}
					node_tasks($site_id,$host);
					$db->query_unbuffered("UPDATE {$tpf}limit_rules SET used_fee=0{$do_sql} WHERE id='{$limit['id']}'");
				}else{
					$db->query_unbuffered("UPDATE {$tpf}limit_rules SET used_fee=used_fee+$fee WHERE id='{$limit['id']}'");
					$rule = $db->fetch_one_array("SELECT * FROM {$tpf}limit_rules WHERE id='{$limit['id']}'");
					if($rule['used_fee']>=$rule['fee']){
						do_bypass($site_id,$host);
					}
				}
			}
			$user_wealth = $db->result_first("SELECT wealth FROM {$tpf}users WHERE userid='{$userid}'");
			if($user_wealth<0){
				$q = $db->query("SELECT * FROM {$tpf}site_settings s,{$tpf}mysites m WHERE m.id=s.site_id and m.userid={$userid}");
				while($rs=$db->fetch_array($q)){
					// if($rs['cdn_type']=='ns'){
						// $rs['cdn_status'] = $db->result_first("SELECT cdn_status FROM {$tpf}site_dns WHERE id=");
					// }else{
						
					// }
					do_bypass($rs['site_id'],$rs['host']);
				}
			}
		}
		$out['status'] = 'success';
		$out['fee'] = $fee;
		$out['node_id'] = $node_id;
		$out['fee_per_g'] = $fee_per_g;
		$out['data'] = round($data,4);
		echo json_encode($out);
		break;
	case'heart':
		$hostname = trim(gpc('hostname','P',''));
		$load_avg = trim(gpc('load_avg','P',''));
		$ng_status = base64_decode(trim(gpc('ng_status','P','')));
		$ng_configtest = base64_decode(trim(gpc('ng_configtest','P','')));
		$connections = (int)gpc('connections','P','');
		$mem_total = (int)gpc('mem_total','P','');
		$mem_free = (int)gpc('mem_free','P','');
		$rx_result = (int)gpc('rx_result','P','');
		$tx_result = (int)gpc('tx_result','P','');
		$ins = array(
			'status'=>'success',
			'hostname'=>$hostname,
			'load_avg'=>$load_avg,
			'ng_status'=>$ng_status,
			'ng_configtest'=>$ng_configtest,
			'connections'=>$connections,
			'mem_total'=>$mem_total,
			'mem_free'=>$mem_free,
			'rx_result'=>$rx_result,
			'tx_result'=>$tx_result,
			'check_time'=>$timestamp,
		);
		$db->query_unbuffered("UPDATE {$tpf}nodes SET".$db->sql_array($ins)." WHERE ip='$onlineip'");
		$check_time = $timestamp-120;
		$db->query_unbuffered("UPDATE {$tpf}nodes SET status='fail' WHERE check_time<$check_time");
		
		$out['status'] = 'success';
		echo json_encode($out);
		// echo $ng_configtest;
		break;
	case'get_tasks':
		// $node_id = (int)gpc('node_id','GP','');
		$q = $db->query("SELECT * FROM {$tpf}node_sites WHERE task_status='pendding' and id='$node_id'");
		while($rs=$db->fetch_array($q)){
			$db->query_unbuffered("UPDATE {$tpf}node_sites SET task_status='waiting' WHERE id='$node_id' and set_id='{$rs['set_id']}'");
			$tasks[] = $rs;
		}
		$out['status'] = 'success';
		$out['tasks'] = $tasks;
		echo json_encode($out);
		break;
	case'get_tasks2':
		// $node_id = (int)gpc('node_id','GP','');
		$q = $db->query("SELECT * FROM {$tpf}node_tasks WHERE status='pendding' and node_id='$node_id'");
		while($rs=$db->fetch_array($q)){
			$db->query_unbuffered("UPDATE {$tpf}node_tasks SET status='waiting' WHERE id='{$rs['id']}'");
			$tasks[] = $rs;
		}
		$out['status'] = 'success';
		$out['tasks'] = $tasks;
		echo json_encode($out);
		break;
	case'get_configs':
		$ids = trim(gpc('ids','GP',''));
		// $node_id = (int)gpc('node_id','GP','');
		$q = $db->query("SELECT settings.id as set_id,settings.*,sites.* FROM {$tpf}site_settings settings,{$tpf}mysites sites WHERE sites.id=settings.site_id and sites.status='success' and settings.id IN ($ids)");
		while($rs=$db->fetch_array($q)){
			$rs['records'] = get_records($rs['site_id'],$rs['cdn_type'],$rs['host']);
			$rs['mid_point'] = '35.189.185.242';
			$rs['waf_point'] = '172.104.70.82';
			$sites[] = $rs;
		}
		$out['status'] = 'success';
		$out['configs'] = $sites;
		echo json_encode($out);
		break;
	case'getallsite':
		$ids = trim(gpc('ids','GP',''));
		// $node_id = 23;
		$q = $db->query("SELECT settings.id as set_id,settings.*,sites.* FROM {$tpf}site_settings settings,{$tpf}mysites sites WHERE sites.id=settings.site_id and sites.status='success' and nodes!=''");
		while($rs=$db->fetch_array($q)){
			$node_ids = explode(',',$rs['nodes']);
			if(in_array($node_id,$node_ids)){
				$rs['records'] = get_records($rs['site_id'],$rs['cdn_type'],$rs['host']);
				$rs['mid_point'] = '35.189.185.242';
				$rs['waf_point'] = '172.104.70.82';
				$sites[] = $rs;
			}
		}
		$out['status'] = 'success';
		$out['configs'] = $sites;
		echo json_encode($out);
		break;
	case'get_nodes':
		$q = $db->query("SELECT * FROM {$tpf}nodes");
		while($rs=$db->fetch_array($q)){
			$nodes[$rs['hostname']] = $rs;
		}
		$out['status'] = 'success';
		$out['nodes'] = $nodes;
		echo json_encode($out);
		break;
	case'get_ssl':
		$id = (int)gpc('id','GP','');
		$rs = $db->fetch_one_array("SELECT * FROM {$tpf}keychains WHERE id=$id");
		$out['status'] = 'success';
		$out['cert'] = $rs['cert'];
		$out['key'] = $rs['key'];
		echo json_encode($out);
		break;
	case'task_act':
		// $node_id = (int)gpc('node_id','GP','');
		$act = trim(gpc('act','GP',''));
		switch($act){
			case'done':
				//修改dns或者cname解析
				$db->query_unbuffered("UPDATE {$tpf}node_sites SET task_status='success' WHERE task_status='waiting' and id='$node_id'");
				$out['status'] = 'success';
				break;
		}
		echo json_encode($out);
		break;
}
?>