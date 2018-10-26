<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!');
$node_id = $db->result_first("SELECT id FROM {$tpf}nodes WHERE ip LIKE '%$onlineip%'");
// echo 'aa';
switch($act){
	case'heart':
		$data = gpc('data','P','');
		$hostname = $data['hostname'];
		$load_avg = $data['loadAvg'];
		// $ng_status = base64_decode(trim(gpc('ng_status','P','')));
		// $ng_configtest = base64_decode(trim(gpc('ng_configtest','P','')));
		
		$connections = $data['connections'];
		$mem_total = $data['memTotal'];
		$mem_free = $data['memFree'];
		$netCard = (array)$data['netCard'];
		$rx_result = 0;
		$tx_result = 0;
		
		foreach($netCard as $name=>$nic){
			if($name!='lo'){
				$rx_result += $nic['inspeed'];
				$tx_result += $nic['outspeed'];
			}
		}
		// $rx_result = (int)gpc('rx_result','P','');
		// $tx_result = (int)gpc('tx_result','P','');
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
		$db->query_unbuffered("UPDATE {$tpf}nodes SET".$db->sql_array($ins)." WHERE ip LIKE '%$onlineip%'");
		$check_time = $timestamp-120;
		$db->query_unbuffered("UPDATE {$tpf}nodes SET status='fail' WHERE check_time<$check_time");
		
		$out['status'] = 'success';
		$out['ip'] = $onlineip;
		echo json_encode($out);
		break;
	case'dotask':
		// echo bin2hex(chr(27));
		// error_reporting(1);
		// $task = $db->fetch_one_array("SELECT t.*,n.ip,n.port,n.password FROM {$tpf}tasks t,{$tpf}nodes n WHERE n.id=t.node_id and t.task_id='$task_id'");
		// donodetask($task);
		// echo '执行完成';
		break;
	case'update':
		$q = $db->query("SELECT * FROM {$tpf}nodes");
		// $rs = array();
		while($rs=$db->fetch_array($q)){
			$ins = array(
				'node_id'=>$rs['id'],
				'task'=>'getversion',
				'data'=>serialize(array()),
				'status'=>'pendding',
				'response'=>'',
				'in_time'=>$timestamp,
			);
			$db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		}
		// $site = $db->fetch_one_array("SELECT settings.id as set_id,settings.*,sites.* FROM {$tpf}site_settings settings,{$tpf}mysites sites WHERE sites.id=settings.site_id and sites.status='success' and settings.id=8");
		
		// $site['records'] = get_records($site['site_id'],$site['cdn_type'],$site['host']);
		// $site['mid_point'] = '35.189.185.242';
		// $site['waf_point'] = '172.104.70.82';
		
		// $rs['site'] = $site;
		// $rs['set_id'] = 23;
		// $rs['file'] = '/data/attachment/forum/201707/22/112756vbabs10707bgbpsg.jpg';
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'buildsite',
			// 'data'=>serialize($rs),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'buildsite',
			// 'data'=>serialize($rs),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'buildsite',
			// 'data'=>serialize($rs),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'buildsite',
			// 'data'=>serialize($rs),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'buildsite',
			// 'data'=>serialize($rs),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'rebuild',
			// 'data'=>serialize(array()),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'rebuild',
			// 'data'=>serialize(array()),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'rebuild',
			// 'data'=>serialize(array()),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'rebuild',
			// 'data'=>serialize(array()),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'rebuild',
			// 'data'=>serialize(array()),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		// $ins = array(
			// 'node_id'=>23,
			// 'task'=>'rebuild',
			// 'data'=>serialize(array()),
			// 'status'=>'pendding',
			// 'response'=>'',
			// 'in_time'=>$timestamp,
		// );
		// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
		break;
	case'getallsite':
		// $node_id = 23;
		$q = $db->query("SELECT settings.id as set_id,settings.*,sites.* FROM {$tpf}site_settings settings,{$tpf}mysites sites WHERE sites.id=settings.site_id and sites.status='success' and settings.nodes REGEXP '^($node_id),|,($node_id),|,($node_id)$'");
		while($rs=$db->fetch_array($q)){
			$rs['records'] = get_records($rs['site_id'],$rs['cdn_type'],$rs['host']);
			$rs['mid_point'] = '35.189.185.242';
			$rs['waf_point'] = '172.104.70.82';
			if($rs['https_switch']){
				$keychain = $db->fetch_one_array("SELECT * FROM {$tpf}keychains WHERE id={$rs['keychains']}");
				$rs['key'] = $keychain['key'];
				$rs['cert'] = $keychain['cert'];
			}
			$sites[] = $rs;
		}
		$out['status'] = 'success';
		$out['configs'] = $sites;
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
}

function donodetask($task){
	global $db,$tpf;
	$db->query_unbuffered("UPDATE {$tpf}tasks SET status='running' WHERE task_id={$task['task_id']}");
	$status = false;
	$try = 0;
	$password = md5($task['password']);
	$url = "http://{$task['ip']}:{$task['port']}/{$task['task']}";
	$data = http_build_query(unserialize($task['data']));
	do {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("password:$password"));
		curl_setopt($curl,CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl,CURLOPT_POST,true); // post传输数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);// post传输数据
		curl_setopt($curl,CURLOPT_TIMEOUT_MS, 10000); //设置超时时间
		$rs = curl_exec($curl);
		curl_close($curl);
		$arr = json_decode($rs,true);
		$status = $arr['status'];
		$try ++;
		$str = date('Y-m-d H:i:s').':'.$rs.LF;
		$error_log = APP_ROOT.'sys/node_api_access_'.date('Ymd').'.php';
		write_file($error_log,$str,'ab');
	} while ($status<>'success' && $try<1 );
	if($status<>'success'){
		$status = 'failed';
		$str = date('Y-m-d H:i:s').': API Failed |'.$rs.LF;
		$error_log = APP_ROOT.'sys/node_api_failed_'.date('Ymd').'.php';
		write_file($error_log,$str,'ab');
	}
	$rs = addslashes($rs);
	$db->query_unbuffered("UPDATE {$tpf}tasks SET status='$status',response='{$rs}' WHERE task_id={$task['task_id']}");
	return;
}