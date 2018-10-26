<?php 
/*
#	Project: LinkCDN - The World Best CDN Manage System
#
#	$Id: inc/function/cache.func.php 2018-10-26 12:41:29 Linkec $
#
#	Copyright (C) 2009-2019 SuikeTech.Inc. All Rights Reserved.
#
*/
if(!defined('IN_APP')) {
	exit('[XDDrive] Access Denied');
}
// 模板显示函数
function template_echo($tpl,$tpl_dir,$is_admin_tpl=0){

	$tpl_cache_dir = $tpl_cache_dir_tmp = APP_ROOT.'_tmp/'.$tpl_dir;
	$tpl_src_dir = APP_ROOT.$tpl_dir;
	$tpl_default_dir = APP_ROOT.'tpl/default/';
	$admin_tpl_dir = APP_ROOT.'tpl/admin/';
	
	if(strpos($tpl,'/')!==false){
		$tpl_cache_dir_tmp = $tpl_cache_dir_tmp.substr($tpl,0,strlen($tpl)-strlen(strrchr($tpl,'/'))).'/';
	}
	make_dir($tpl_cache_dir_tmp);
	make_dir($tpl_cache_dir);

	$tpl_cache_file = $tpl_cache_dir.$tpl.'.tpl.php';
	$tpl_src_file = $tpl_src_dir.$tpl.'.tpl.php';
	
	if($is_admin_tpl && !file_exists($tpl_src_file)){
		$tpl_src_file = $admin_tpl_dir.$tpl.'.tpl.php';
	}elseif(!file_exists($tpl_src_file)){
		$tpl_src_file = $tpl_default_dir.$tpl.'.tpl.php';
	}
	
	if(@filemtime($tpl_cache_file) < @filemtime($tpl_src_file)){
		write_file($tpl_cache_file,template_parse($tpl_src_file));
		return $tpl_cache_file;
	}
	if(file_exists($tpl_cache_file)){
		return $tpl_cache_file;
	}else{
		write_file($tpl_src_file,"<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>");
		$str = strrchr($tpl_cache_file,'/');
		$str = substr($str,1,strlen($str));
		die("MYAPP Template: <b>$tpl_dir$tpl_cache_file</b> not Exists!");
	}

}
// 模板解析函数
function template_parse($tpl){
	global $user_tpl_dir;
	if(!file_exists($tpl)){
		exit('Template ['.$tpl.'] not exists!');
	}
	$str = read_file($tpl);
	$str = preg_replace("/\<\!\-\-\#include (.+?)\#\-\-\>/si","<?php require_once template_echo('\\1','$user_tpl_dir'); ?>", $str);
	$str = preg_replace("/\<\!\-\-\#(.+?)\#\-\-\>/si","<?php \\1 ?>", $str);
	$str = preg_replace("/\{([A-Z_]+)\}/","<?=\\1?>",$str);
	$str = preg_replace("/\{(\\\$[a-z0-9_\'\"\[\]]+)\}/si", "<?=\\1?>", $str);
	$str = preg_replace("/\{\<\?\=(\\\$[a-z0-9_\'\"\[\]]+)\?\>\}/si","{\\1}",$str);
	$str = preg_replace("/\{\#(.+?)\#\}/si","<?=\\1?>", $str);
	$str = preg_replace("/\{sql\[(.+)\]\[(.+)\]\}/si","<? foreach(get_sql(\"\\1\") as \\2){ ?>",$str);
	$str = str_replace("{/sql}","<? } ?>",$str);
	$str = str_replace('@@','{$tpf}',$str); // fix sql tag!

	$prefix = "<?php ".LF;
	$prefix .= "// This is auto-generated file. Do NOT modify.".LF.LF;
	$prefix .= "// Cache Time:".date('Y-m-d H:i:s').LF.LF;
	$prefix .= "!defined('IN_APP') && exit('[MYAPP] Access Denied');".LF.LF;
	$prefix .= "?>".LF;

	return $prefix.$str;
}

function get_ip(){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineip = addslashes($onlineip);
	@preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	unset($onlineipmatches);
	return $onlineip;
}
function updatedns($domain){
	global $db,$tpf,$timestamp;
	$domain = trim($domain,'.');
	//更新dns服务器记录
	$q = $db->query("SELECT * FROM {$tpf}nodes WHERE area = 6");
	while($rs=$db->fetch_array($q)){
		$ins = array(
			'node_id'=>$rs['id'],
			'task'=>'updatedns',
			'data'=>serialize(array('domain'=>$domain)),
			'status'=>'pendding',
			'response'=>'',
			'in_time'=>$timestamp,
		);
		$db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
	}
}
function deletedns($domain){
	global $db,$tpf,$timestamp;
	//更新dns服务器记录
	$q = $db->query("SELECT * FROM {$tpf}nodes WHERE area = 6");
	while($rs=$db->fetch_array($q)){
		$ins = array(
			'node_id'=>$rs['id'],
			'task'=>'deletedns',
			'data'=>serialize(array('domain'=>$domain)),
			'status'=>'pendding',
			'response'=>'',
			'in_time'=>$timestamp,
		);
		$db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
	}
}
function get_records($id,$type,$host){
	global $db,$tpf;
	if($type=='ns'){
		$q = $db->query("SELECT * FROM {$tpf}site_dns WHERE site_id='$id' and dns_host LIKE '$host' and (dns_type LIKE 'A' or dns_type LIKE 'CNAME')");
	}elseif($type=='cn'){
		$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE site_id='$id' and dns_host LIKE '$host' and (dns_type LIKE 'A' or dns_type LIKE 'CNAME')");
	}
	$records = array();
	while($rs=$db->fetch_array($q)){
		if(in_array($rs['dns_type'],array('A','CNAME'))){
			$records[] = $rs;
		}
	}
	return $records;
}
function get_humannum($num){
	// if($num>100000){
		// $num = round($num/10000,1) .' 万';
	// }else{
		// $num = (int)$num;
	// }
	return $num;
}
function suike_pay($title, $fee, $order_id,$payment_type='',$app_id='35',$app_key='c89491ef6f8395cd67a666767397a7bc') {
	
    $sHtml = "<form id='suipaysubmit' name='suipaysubmit' action='https://mapi.pay.isuike.com/gateway.php' method='post'>";
    $pay['app_id'] = $app_id;
    $pay['pay_type'] = $payment_type;
    $pay['title'] = $title;
    $pay['fee'] = $fee;
    $pay['trade_num'] = $order_id;
    $pay['hash'] = md5($app_key.$pay['app_id'].$pay['title'].$pay['fee'].$pay['trade_num']);
     
    while (list ($key, $val) = each ($pay)) {
        $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
    }
     
    $sHtml = $sHtml."<input type='submit' value='正在跳转至支付接口'></form>";
    $sHtml = $sHtml."<script>document.forms['suipaysubmit'].submit();</script>";
    return $sHtml;
}
 
function update_nodes($set_id){
	global $db,$tpf;
	$sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE id='$set_id'");
	foreach(explode(',',$sets['nodes']) as $node){
		$sqls .= "('{$node}','{$set_id}','update','pendding'),";
	}
	if($sqls){
		$sqls = substr($sqls,0,-1);
		$db->query("replace into {$tpf}node_sites (id,set_id,status,task_status) values $sqls;");
		unset($sqls);
	}
}
function node_tasks($site_id,$host){
	global $db,$tpf,$timestamp;
	$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE id='$site_id'");
	$ori_sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='$host' and site_id='$site_id'");
	if($ori_sets){
		$q = $db->query("SELECT * FROM {$tpf}nodes WHERE area IN ({$ori_sets['node_group']})");
		while($rs=$db->fetch_array($q)){
			if(!($site['is_risk']==1 && $rs['is_risk']==1)){
				$node_ids[] = $rs['id'];
				$nodes[] = $rs;
			}
		}
		$rs['site'] = array_merge($ori_sets,$site);
		$rs['site']['set_id'] = $ori_sets['id'];
		$rs['site']['records'] = get_records($rs['site']['site_id'],$rs['site']['cdn_type'],$rs['site']['host']);
		$rs['site']['mid_point'] = '35.189.185.242';
		$rs['site']['waf_point'] = '172.104.70.82';
		
		$ori_sets['nodes'] = implode(',',$node_ids);
		$db->query_unbuffered("UPDATE {$tpf}site_settings SET nodes='{$ori_sets['nodes']}' WHERE id='{$ori_sets['id']}'");
		if($site['cdn_type']=='cn'){
			do_cname_ns($host,$site['domain'],$site['ns_data'],$nodes,$site_id);
		}else{
			do_ns_ns($host,$site['domain'],$site['ns_data'],$nodes,$site_id);
		}
		
		if($site['status']=='success'){
			foreach($nodes as $node){
				$ins = array(
					'node_id'=>$node['id'],
					'task'=>'buildsite',
					'data'=>serialize($rs),
					'status'=>'pendding',
					'response'=>'',
					'in_time'=>$timestamp,
				);
				$db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
			}
		}else{
			do_bypass($site_id,$host);
		}
	}
}
function get_dnsview($line){
	switch($line){
		case'电信':
			return 'country_CN_DianXin';
			break;
		case'联通':
			return 'country_CN_LianTong';
			break;
		case'教育网':
			return 'country_CN_JiaoYuWang';
			break;
		case'鹏博士':
			return 'country_CN_PengBoShi';
			break;
		case'移动':
			return 'country_CN_YiDong';
			break;
		case'铁通':
			return 'country_CN_TieTong';
			break;
		case'国内':
			return 'country_CN';
			break;
		case'国外':
			return 'country_NOTCN';
			break;
		case'港澳台':
			return 'HK_TW_AM';
			break;
		default:
			return 'any';
			break;
	}
}
function do_bypass($site_id,$host){
	global $db,$tpf;
	$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE id='$site_id'");
	if($site['cdn_type']=='ns'){
		$zone = $site['domain'];
		$ns_data = explode(',',$site['ns_data']);
		$ns_host = $host;
		$is_mix = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_dns WHERE dns_host='$host' and (dns_type='A' or dns_type='CNAME') and site_id='$site_id' and cdn_status='on'");
		$q = $db->query("SELECT * FROM {$tpf}site_dns WHERE dns_host='$host' and (dns_type='A' or dns_type='CNAME') and site_id='$site_id'");
	}else{
		$zone = substr($site['ns_data'],0,-1);
		$domain = $host =='@' ? $site['domain'] : $host.'.'.$site['domain'];
		$ns_host = $domain;
		$ns_data[] = 'ns1.fbidns.com.';
		$ns_data[] = 'ns2.fbidns.com.';
		$is_mix = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE dns_host='$host' and (dns_type='A' or dns_type='CNAME') and site_id='$site_id' and cdn_status='on'");
		$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE dns_host='$host' and (dns_type='A' or dns_type='CNAME') and site_id='$site_id'");
	}
	
	// echo "DELETE FROM {$tpf}dns_records WHERE zone='{$zone}' and host='$ns_host' and (type='A' or type='CNAME')";
	$db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE zone='{$zone}' and host='$ns_host' and (type='A' or type='CNAME')");
	// $db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE zone='{$zone}' and host='$ns_host' and (type='A' or type='CNAME')");
	if($is_mix){
		// echo 'mix';
		// node_tasks($site_id,$host);
		while($rs=$db->fetch_array($q)){
			$ins = array(
				'zone'=>$zone,
				'host'=>$ns_host,
				'type'=>$rs['dns_type'],
				'data'=>$rs['dns_value'],
				'ttl'=>$rs['dns_ttl'],
				'mx_priority'=>$rs['dns_mx'],
				'view'=>get_dnsview($rs['dns_line']),
				'serial'=>rand(),
				'primary_ns'=>$ns_data[0],
				'second_ns'=>$ns_data[1],
				'site_id'=>$site_id,
			);
			$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
		}
	}else{
		// echo 'notmix';
		while($rs=$db->fetch_array($q)){
			$ins = array(
				'zone'=>$zone,
				'host'=>$ns_host,
				'type'=>$rs['dns_type'],
				'data'=>$rs['dns_value'],
				'ttl'=>$rs['dns_ttl'],
				'mx_priority'=>$rs['dns_mx'],
				'view'=>get_dnsview($rs['dns_line']),
				'serial'=>rand(),
				'primary_ns'=>$ns_data[0],
				'second_ns'=>$ns_data[1],
				'site_id'=>$site_id,
			);
			$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
		}
	}
}
function do_ns_ns($host,$domain,$ns,$nodes,$site_id){
	global $db,$tpf;
	// $postfix = substr($postfix,0,-1);
	// $host = $host.'.'.$domain;
	$ns_data = explode(',',$ns);
	//删除旧的配置
	$db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE zone='$domain' and host='$host' and (type='A' or type='CNAME')");
	//添加新的配置
	// foreach($nodes as $node){
		// if($node['view']){
			// $ins = array(
				// 'zone'=>$domain,
				// 'host'=>$host,
				// 'type'=>'A',
				// 'data'=>$node['ip'],
				// 'ttl'=>10,
				// 'mx_priority'=>null,
				// 'view'=>$node['view'],
				// 'serial'=>0,
				// 'primary_ns'=>$ns_data[0],
				// 'second_ns'=>$ns_data[1],
			// );
			// $db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
		// }
	// }
	foreach($nodes as $node){
		foreach(explode(',',$node['views']) as $view_data){
			$view_data = explode('|',$view_data);
			$ip = explode(',',$node['ip']);
			if($view_data[0]){
				$rs['view']=$view_data[0];
				$rs['pr']=$view_data[1];
				$rs['ip']=$ip[0];
				$rs['weight']=$node['weight'];
				$resolve[] = $rs;
				unset($rs);
			}
		}
	}
	// var_dump($resolve);
	foreach($resolve as $v){
		//先按view分组
		if($v['view']!='waf' && $v['view']!='mid' ){
			$resolve2[$v['view']][] = $v;
		}
	}
	foreach($resolve2 as $v){
		foreach($v as $id=>$v2){
			foreach($v as $id3=>$v3){
				if($v3['pr']<$v2['pr']){
					$resolve2[$v2['view']][$id3]='';
				}
			}
		}
	}
	// var_dump($resolve2);
	// exit;
	foreach($resolve2 as $v){
		foreach($v as $v2){
			if(count($v2)==4){
				$resolve3[]=$v2;
			}
		}
	}
	// var_dump($resolve3);
	// exit;
	foreach($resolve3 as $v){
		$ins = array(
			'zone'=>$domain,
			'host'=>$host,
			'type'=>'A',
			'data'=>$v['ip'],
			'ttl'=>10,
			'mx_priority'=>null,
			'view'=>$v['view'],
			'serial'=>0,
			'primary_ns'=>$ns_data[0],
			'second_ns'=>$ns_data[1],
			'site_id'=>$site_id,
			'weight'=>$v['weight'],
		);
		$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
	}
}
function do_cname_ns($host,$domain,$postfix,$nodes,$site_id){
	global $db,$tpf;
	$postfix = substr($postfix,0,-1);
	// $host = $host.'.'.$domain;
	$host = $host =='@' ? $domain : $host.'.'.$domain;
	//删除旧的配置
	$db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE zone='$postfix' and host='$host'");
	//添加新的配置
	foreach($nodes as $node){
		foreach(explode(',',$node['views']) as $view_data){
			$view_data = explode('|',$view_data);
			$ip = explode(',',$node['ip']);
			if($view_data[0]){
				$rs['view']=$view_data[0];
				$rs['pr']=$view_data[1];
				$rs['ip']=$ip[0];
				$rs['weight']=$node['weight'];
				$resolve[] = $rs;
				unset($rs);
			}
		}
	}
	// var_dump($resolve);
	foreach($resolve as $v){
		//先按view分组
		if($v['view']!='waf' && $v['view']!='mid' ){
			$resolve2[$v['view']][] = $v;
		}
	}
	// var_dump($resolve2);
	foreach($resolve2 as $v){
		foreach($v as $id=>$v2){
			foreach($v as $id3=>$v3){
				if($v3['pr']<$v2['pr']){
					$resolve2[$v2['view']][$id3]='';
				}
			}
		}
	}
	
	foreach($resolve2 as $v){
		foreach($v as $v2){
			if(count($v2)==4){
				$resolve3[]=$v2;
			}
		}
	}
	// foreach($resolve2 as $v){
		// foreach($v as $id=>$v2){
			// if(count($v2)){
				// $resolve3 = $v2;
			// }
		// }
	// }
	// $resolve3 = array_unique($resolve3);
	// var_dump($resolve3);
	// echo 'a';
	foreach($resolve3 as $v){
		$ins = array(
			'zone'=>$postfix,
			'host'=>$host,
			'type'=>'A',
			'data'=>$v['ip'],
			'ttl'=>10,
			'mx_priority'=>null,
			'view'=>$v['view'],
			'serial'=>0,
			'primary_ns'=>'ns1.fbidns.com.',
			'second_ns'=>'ns2.fbidns.com.',
			'site_id'=>$site_id,
			'weight'=>$v['weight'],
		);
		$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
	}
}
function get_stats($site_id,$host,$d_year,$d_month,$d_day,$period){
	global $memcache,$timestamp;
	$key = $period.'_'.$site_id.'_'.$host.'_'.$d_year.'_'.$d_month.'_'.$d_day;
	$data = $memcache->get($key);
	if(!$data){
		$data = callapi("http://158.69.54.69/api.php?a=get_stats&site_id={$site_id}&host={$host}&d_year={$d_year}&d_month={$d_month}&d_day={$d_day}&period={$period}");
		// var_dump($data);
		$memcache->set($key,$data,MEMCACHE_COMPRESSED,10);
	}
	
	if($data['data']){
		$day_stats['stat_req'] = $data['data']['req'];
		$day_stats['stat_ip'] = $data['data']['ip'];
		$day_stats['stat_uv'] = $data['data']['uv'];
		$day_stats['stat_bdata'] = $data['data']['bypass_data'];
		$day_stats['stat_hdata'] = $data['data']['hit_data'];
		$day_stats['stat_breq'] = $data['data']['bypass_req'];
		$day_stats['stat_hreq'] = $data['data']['hit_req'];
		$rs = explode(' ',get_size($data['data']['data'],'B',2));
		$day_stats['stat_data'] = $rs[0];
		$day_stats['stat_data_unit'] = $rs[1];
	}else{
		$day_stats['stat_req'] = 0;
		$day_stats['stat_ip'] = 0;
		$day_stats['stat_uv'] = 0;
		$day_stats['stat_data'] = 0;
		$day_stats['stat_bdata'] = 0;
		$day_stats['stat_hdata'] = 0;
		$day_stats['stat_breq'] = 0;
		$day_stats['stat_hreq'] = 0;
		$day_stats['stat_data_unit'] = 'B';
	}
	switch($period){
		case'today':
			$time_start = mktime(0,0,0,$d_month,$d_day,$d_year);
			$days = 1;
			break;
		case'yesterday':
			$time_start = mktime(0,0,0,$d_month,$d_day-1,$d_year);
			$days = 1;
			break;
		case'7days':
			$time_start = mktime(0,0,0,$d_month,$d_day-7,$d_year);
			$days = 7;
			break;
		case'30days':
			$time_start = mktime(0,0,0,$d_month,$d_day-30,$d_year);
			$days = 30;
			break;
	}
	$time_end = $time_start+86400*$days;
	for($i=$time_start;$i<=$time_end;$i+=300){
		if($data['5m'][$i]){
			$day_stats['5m'][] = array(
				'timeline'=>$i*1000,
				'bypass_data'=>round($data['5m'][$i]['bypass_data']/1024/1024,2),
				'hit_data'=>round($data['5m'][$i]['hit_data']/1024/1024,2),
			);
		}else{
			$day_stats['5m'][] = array(
				'timeline'=>$i*1000,
				'bypass_data'=>0,
				'hit_data'=>0,
			);
		}
	}
	return $day_stats;
}
function getHttpResponseGET($url) {
	$curl = curl_init($url);
	curl_setopt($curl,CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl,CURLOPT_TIMEOUT_MS, 10000); //设置超时时间
	$responseText = curl_exec($curl);
	// var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);
	return $responseText;
}
function getHttpResponsePOST($url,$data,$host='') {
	$curl = curl_init($url);
	if($host){
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: '.$host));
	}
	curl_setopt($curl,CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl,CURLOPT_POST,true); // post传输数据
	curl_setopt($curl,CURLOPT_POSTFIELDS,$data);// post传输数据
	curl_setopt($curl,CURLOPT_TIMEOUT_MS, 10000); //设置超时时间
	$responseText = curl_exec($curl);
	// var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);
	
	return $responseText;
}
function callapi($apiurl,$param=array()){
	$status = false;
	$try = 0;
	do {
	  $rs = getHttpResponsePOST($apiurl,$param);
	  // echo $apiurl.$param.$remotehostname;
	  // var_dump($rs);
	  $arr = json_decode($rs,true);
	  $status = $arr['status'];
	  $try ++;
		$str = date('Y-m-d H:i:s').':'.$rs.LF;
		$error_log = APP_ROOT.'sys/api_access_'.date('Ymd').'.php';
		write_file($error_log,$str,'ab');
	} while ($status<>'success' && $try<4 );
	if($status<>'success'){
		$str = date('Y-m-d H:i:s').': API Time out|'.$rs.LF;
		$error_log = APP_ROOT.'sys/api_timeoutlog_'.date('Ymd').'.php';
		write_file($error_log,$str,'ab');
	}
	return $arr;
}
function init_stats_db(){
	global $db2;
	if(!$db2){
		$db2 = new cls_mysql();
		$db2->connect('192.99.216.245','root','linke-.-123','ray_statistics');
	}
	return $db2;
}
function show_error($msg){
	global $user_tpl_dir,$static_url;
	$act = 'show_error';
	require_once template_echo("frame",$user_tpl_dir);
	exit;
}
function money_act($userid,$money,$act){
	global $db,$tpf,$timestamp;
	$wealth = get_profile($userid,'wealth');
	$least_wealth = -10;
	if($wealth+$money<=$least_wealth){
		return false;
	}else{
		$db->query("UPDATE {$tpf}users set wealth=wealth+'$money' WHERE userid='$userid'");
	}
	
	$ins = array(
	'userid' => $userid,
	'money' => $money,
	'act' => $act,
	'in_time' => $timestamp,
	);
	$db->query_unbuffered("insert into {$tpf}money_log set ".$db->sql_array($ins).";");
	return true;
}
function form_auth($p_formhash,$formhash){
	if($p_formhash != $formhash){
		exit(__('system_error'));
	}
}
function convert_str($in,$out,$str){
	global $db;
	$str = $db->escape($str);
	if(function_exists("iconv")){
		$str = iconv($in,$out,$str);
	}elseif(function_exists("mb_convert_encoding")){
		$str = mb_convert_encoding($str,$out,$in);
	}
	return $db->escape($str);
}
function is_utf8(){
	global $charset;
	return (strtolower($charset) == 'utf-8') ? true : false;
}
function is_windows(){
	return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 1 : 0;
}

function wipe_replace($str) {
	return str_replace(array( "\n", "\r", '..'), array('', '', ''), $str);
}

function word_style($i,$type ='font'){
	switch($i){
		case 500:
			$f_px = "25px";
			$c_col = '#ff6600';
			break;
		case 300:
			$f_px = "20px";
			$c_col = '#ff5500';
			break;
		case 100:
			$f_px = "18px";
			$c_col = '#003366';
			break;
		case 50:
			$f_px = "14px";
			$c_col = '#003366';
			break;
		default:
			$f_px = "12px";
			$c_col = '#666666';
	}
	return ($type =='font') ? $f_px : $c_col;
}
function page_end_time(){
	global $ps_time,$db,$C;
	return 'Processed in '.get_runtime('start','end').' second(s), '.$db->querycount.' queries, Gzip '.$C['gz']['status'];
}

function replace_inject_str($str){
	$bad_chars = array("\\","'",'"','/','*',',','<','>',"\r","\t","\n",'$','(',')','%','?',';','^','#',':','&');
	return str_replace($bad_chars,'',$str);
}
function redirect_top_page(){
	global $_SERVER;
	$tmp = strrchr($_SERVER['HTTP_REFERER'],'/');
	$arr = explode('.',$tmp);
	$php_ref = substr($arr[0],1);
	if($php_ref =='admincp' || $php_ref =='mydisk'){
		return true;
	}else{
		return false;
	}
}
function checklength($str,$min,$max){
	if(!$str || strlen($str) > $max || strlen($str) < $min){
		return true;
	}
}
function ifselected($int1,$int2,$type = 'int'){
	if($type == 'int'){
		if(intval($int1) == intval($int2)){
			return ' selected';
		}
	}elseif($type == 'str'){
		if(strval($int1) == strval($int2)){
			return ' selected';
		}
	}
}
function ifchecked($int1,$int2,$type = 'int'){
	if($type == 'int'){
		if(intval($int1) == intval($int2)){
			return ' checked';
		}
	}elseif($type == 'str'){
		if(strval($int1) == strval($int2)){
			return ' checked';
		}
	}
}
function multi_selected($id,$str){
	if(strpos($str,',')){
		$a2 = explode(',',$str);
		$rtn = in_array($id,$a2) ? ' selected' : '';
	}else{
		$rtn = $id==$str ? ' selected' : '';
	}
	return $rtn;
}

function replace_js($str){
	return preg_replace("'<script[^>]*?>(.*?)</script>'si","[script]\\1[/script]",$str);
}
function custom_time($format, $time){
	global $timestamp;
	$s = $timestamp - $time;
	if($s < 0){
		return __('custom_time_0');
	}
	if($s < 60){
		return $s.__('custom_time_1');
	}
	$m = $s / 60;
	if($m < 60){
		return floor($m).__('custom_time_2');
	}
	$h = $m / 60;
	if($h < 24){
		return floor($h).__('custom_time_3');
	}
	$d = $h / 24;
	if($d < 2){
		return __('custom_time_4').date("H:i", $time);
	}
	if($d <3){
		return __('custom_time_5').date("H:i", $time);
	}
	if($d <= 30){
		return floor($d).__('custom_time_6');
	}
	return date($format, $time);
}
function get_byte_value($v){
	$v = trim($v);
	$l = strtolower($v[strlen($v) - 1]);
	switch($l){
		case 'g':
			$v *= 1024;

		case 'm':
			$v *= 1024;

		case 'k':
			$v *= 1024;
	}
	return $v;
}

function redirect($url,$str,$timeout = 2000,$target = ''){
	global $user_tpl_dir;

	if($timeout ==0){
		header("Location:$url");
		exit;
	}else{
		$msg = '';
		if(is_array($str)){
			for($i=0;$i<count($str);$i++){
				$msg .= "<li>".$str[$i]."</li>".LF;
			}
		}else{
			$msg = $str;
		}
		$go_url = $url=='back' ? $url = 'javascript:history.back();' : $url;
		require_once template_echo('information',$user_tpl_dir);
		$rtn = "<script>".LF;
		$rtn .= "<!--".LF;
		$rtn .= "function redirect() {".LF;
		if($target =='top'){
			$rtn .= "	self.parent.location.href = '$url';".LF;
		}else{
			$rtn .= "	document.location.href = '$go_url';".LF;
		}
		$rtn .= "}".LF;
		$rtn .= "setTimeout('redirect();', $timeout);".LF;
		$rtn .= "-->".LF;
		$rtn .= "</script>".LF;
		echo $rtn;
	}
}
function tb_redirect($url,$str,$timeout=2000){
	if(is_array($str)){
		for($i=0;$i<count($str);$i++){
			$msg .= "<li>·".$str[$i]."</li>".LF;
		}
	}else{
		$msg = $str;
	}
	$go_url = $url=='back' ? $url='javascript:history.back();' : $url;
	$rtn = '<div class="tb_box_msg"><img src="images/light.gif" border="0" align="absmiddle">&nbsp;<ul>'.$msg.'</ul></div>';
	$rtn .= "<script>".LF;
	$rtn .= "<!--".LF;
	$rtn .= "function redirect() {".LF;
	$rtn .= $url=='back' ? '' : "self.parent.$,jBox.close(true);".LF;
	$rtn .= "	self.parent.document.location.href = '$go_url';".LF;
	$rtn .= "}".LF;
	$rtn .= "setTimeout('redirect();', $timeout);".LF;
	$rtn .= "-->".LF;
	$rtn .= "</script>".LF;
	echo $rtn;
}
function is_bad_chars($str){
	$bad_chars = array("\\",' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'$','(',')','%','+','?',';','^','#',':','　','`','=','|','-');
	foreach($bad_chars as $value){
		if (strpos($str,$value) !== false){
			return true;
		}
	}
}
function get_extension($name){
	return strtolower(trim(strrchr($name, '.'), '.'));
}
function formhash(){
	global $pd_uid,$pd_pwd;
	return substr(md5(substr(time(), 0, -7).$pd_uid.$pd_pwd), 8, 8);
}
function encode_pwd($str){
	global $settings;
	$len = trim($str) ? strlen($str) : 6;
	if($settings['online_demo']){
		$rtn = str_repeat('*',$len);
	}else{
		if($len <=4){
			$rtn = str_repeat('*',$len);
		}elseif($len <=10){
			$rtn = str_repeat('*',$len-4);
			$rtn .= substr($str,-4);
		}else{
			$rtn = str_repeat('*',$len-6);
			$rtn .= substr($str,-6);
		}
	}
	return $rtn;
}
function random($length){
	$seed = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	while(strlen($str) < $length){
		$str .= substr($seed,(mt_rand() % strlen($seed)),1);
	}
	return $str;
}

function addslashes_array(&$array) {
	if(is_array($array)){
		foreach($array as $k => $v) {
			$array[$k] = addslashes_array($v);
		}
	}elseif(is_string($array)){
		$array = addslashes($array);
	}
	return $array;
}

function get_size($s,$u='B',$p=1){
	$us = array('B'=>'K','K'=>'M','M'=>'G','G'=>'T');
	return (($u!=='B')&&(!isset($us[$u]))||($s<1024))?(number_format($s,$p)." $u"):(get_size($s/1024,$us[$u],$p));
}

function get_sizeint($s){
	// $us = array('B'=>'K','K'=>'M','M'=>'G','G'=>'T');
	$us = array('K'=>1024,'M'=>1048576,'G'=>1073741824,'T'=>1099511627776);
	foreach($us as $k=>$v){
		if(stristr($s,$k)){
			$int_ = explode($k,$s);
			$int = $int_[0];
			return $int*$v;
		}
	}
	// if(in_array($s,$us)){
		
	// }
	// return (($u!=='B')&&(!isset($us[$u]))||($s<1024))?(number_format($s,$p)." $u"):(get_size($s/1024,$us[$u],$p));
}

function checkemail($email) {
	if((strlen($email) > 6) && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)){
		return true;
	}else{
		return false;
	}
}

function defend_xss($val){
	return is_array($val) ? $val : htmlspecialchars($val);
}

function gpc($name,$w = 'GPC',$default = '',$d_xss=1){
	global $curr_script;
	if($curr_script==ADMINCP){
		$d_xss = 0;
	}
	$i = 0;
	for($i = 0; $i < strlen($w); $i++) {
		if($w[$i] == 'G' && isset($_GET[$name])) return $d_xss ? defend_xss($_GET[$name]) : $_GET[$name];
		if($w[$i] == 'P' && isset($_POST[$name])) return $d_xss ? defend_xss($_POST[$name]) : $_POST[$name];
		if($w[$i] == 'C' && isset($_COOKIE[$name])) return $d_xss ? defend_xss($_COOKIE[$name]) : $_COOKIE[$name];
	}
	return $default;
}
function app_setcookie($var, $value, $expires = 0,$cookiepath = '/', $cookiedomain = '') {
	global $timestamp;
	setcookie($var, $value,$expires ? ($timestamp + $expires) : 0,$cookiepath,$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function app_encode($string, $operation = 'ENCODE',$key = ''){
	global $settings;
	$ckey_length = 4;
	$key = md5($key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d',0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$arr = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $arr[$i] + $rndkey[$i]) % 256;
		$tmp = $arr[$i];
		$arr[$i] = $arr[$j];
		$arr[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $arr[$a]) % 256;
		$tmp = $arr[$a];
		$arr[$a] = $arr[$j];
		$arr[$j] = $tmp;

		$result .= chr(ord($string[$i]) ^ ($arr[($arr[$a] + $arr[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {

		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
function encrypt_key($txt, $key) {
	$md5_key = md5($key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($md5_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $md5_key[$ctr++];
	}
	return $tmp;
}

function cutstr($string, $length, $dot = '...',$charset='utf-8') {
	if(strlen($string) <= $length) {
		return $string;
	}
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	$strcut = '';
	if(strtolower($charset) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length - strlen($dot) - 1; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	return $strcut.$dot;
}

function multi2($total, $perpage, $curpage, $mpurl) {
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	if($total > $perpage) {
		$pg = 10;
		$offset = 5;
		$pgs = @ceil($total / $perpage);
		if($pg > $pgs) {
			$from = 1;
			$to = $pgs;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $pg - $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$to = $pg;
				}
			} elseif($to > $pgs) {
				$from = $curpage - $pgs + $to;
				$to = $pgs;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$from = $pgs - $pg + 1;
				}
			}
		}

		$multipage = ($curpage - $offset > 1 && $pgs > $pg ? '<a href="'.$mpurl.'pg=1" class="p_redirect">&laquo;</a>' : '').($curpage > 1 ? '<a href="'.$mpurl.'pg='.($curpage - 1).'" class="p_redirect">&#8249;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<span class="p_curpage">'.$i.'</span>' : '<a href="'.$mpurl.'pg='.$i.'" class="p_num">'.$i.'</a>';
		}
		$multipage .= ($curpage < $pgs ? '<a href="'.$mpurl.'pg='.($curpage + 1).'" class="p_redirect">&#8250;</a>' : '').($to < $pgs ? '<a href="'.$mpurl.'pg='.$pgs.'" class="p_redirect">&raquo;</a>' : '');
		$multipage = $multipage ? '<div class="p_bar"><span class="p_info">Total:&nbsp;<b>'.$total.'</b>&nbsp;</span>'.$multipage.'</div>' : '';
	}
	return $multipage;
}
function multi($total, $perpage, $curpage, $mpurl) {
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	if($total > $perpage) {
		$pg = 10;
		$offset = 5;
		$pgs = @ceil($total / $perpage);
		if($pg > $pgs) {
			$from = 1;
			$to = $pgs;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $pg - $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$to = $pg;
				}
			} elseif($to > $pgs) {
				$from = $curpage - $pgs + $to;
				$to = $pgs;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$from = $pgs - $pg + 1;
				}
			}
		}
		$multipage = ($curpage - $offset > 1 && $pgs > $pg ? '<li><a href="javascript:loadfd(fid,1)"><i class="fa fa-angle-double-left"></i></a></li>' : '').($curpage > 1 ? '<li><a href="javascript:loadfd(fid,'.($curpage - 1).')" ><i class="fa fa-angle-double-left"></i></a></li>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<li class="active"><a href="javascript:void(0);">'.$i.'</a></li>' : '<li><a href="javascript:loadfd(fid,'.$i.')">'.$i.'</a></li>';
		}
		$multipage .= ($curpage < $pgs ? '<li><a href="javascript:loadfd(fid,'.($curpage + 1).')"><i class="fa fa-angle-double-right"></i></a></li>' : '').($to < $pgs ? '<li><a href="javascript:loadfd(fid,'.$pgs.')" ><i class="fa fa-angle-double-left"></i></a></li>' : '');
		$multipage = $multipage ? '<ul class="pagination">'.$multipage.'</ul>' : '';
		return $multipage;
	}
}
function omulti($total, $perpage, $curpage, $mpurl) {
	$multipage = '';
	if($total > $perpage) {
		$pg = 10;
		$offset = 5;
		$pgs = @ceil($total / $perpage);
		if($pg > $pgs) {
			$from = 1;
			$to = $pgs;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $pg - $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$to = $pg;
				}
			} elseif($to > $pgs) {
				$from = $curpage - $pgs + $to;
				$to = $pgs;
				if(($to - $from) < $pg && ($to - $from) < $pgs) {
					$from = $pgs - $pg + 1;
				}
			}
		}
		// str_ireplace('{pg}','1',$mpurl);
		$multipage = ($curpage - $offset > 1 && $pgs > $pg ? '<li><a href="'.str_ireplace('{pg}','1',$mpurl).'"><i class="fa fa-angle-double-left"></i></a></li>' : '').($curpage > 1 ? '<li><a href="'.str_ireplace('{pg}',($curpage - 1),$mpurl).'" ><i class="fa fa-angle-left"></i></a></li>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<li class="active"><a href="javascript:void(0);">'.$i.'</a></li>' : '<li><a href="'.str_ireplace('{pg}',$i,$mpurl).'">'.$i.'</a></li>';
		}
		$multipage .= ($curpage < $pgs ? '<li><a href="'.str_ireplace('{pg}',$curpage+1,$mpurl).'"><i class="fa fa-angle-right"></i></a></li>' : '').($to < $pgs ? '<li><a href="'.str_ireplace('{pg}',$pgs,$mpurl).'" ><i class="fa fa-angle-double-right"></i></a></li>' : '');
		$multipage = $multipage ? '<ul class="pagination">'.$multipage.'</ul>' : '';
		return $multipage;
	}
}
function is_today($time){
	return (date('Ymd') == date('Ymd',$time)) ? 1 : 0;
}
function get_ids_arr($arr,$msg,$str_in_db=0){
	$error = 0;
	if(!count($arr)){
		$error = 1;
		$strs = $msg;
	}else{
		for($i=0;$i<count($arr);$i++){
			if(is_numeric($arr[$i])){
				$strs .= $str_in_db ? (int)$arr[$i]."," : "'".(int)$arr[$i]."',";
			}
		}
		$strs = substr($strs,0,-1);
	}
	return array($error,$strs);
}
function get_last_upload_server($pd_uid,$group_set){
	$arr_ids = strpos($group_set,',') ? explode(',',$group_set) : $group_set;
	if(is_array($arr_ids)){
		$server_oid = (int)$arr_ids[mt_rand(0,count($arr_ids))];
		$server_oid = $server_oid ? $server_oid : (int)$arr_ids[0];
	}else{
		$server_oid = $arr_ids;
	}
	return $server_oid;
}

function syn_folder_size(){
	global $db,$tpf,$pd_uid;

	$q = $db->query("select * from {$tpf}folders where userid='$pd_uid' and in_recycle=0 order by folder_id asc");
	while($rs = $db->fetch_array($q)){
		if($rs['folder_id']){
			$total_size = @$db->result_first("select sum(file_size) from {$tpf}files where folder_id='{$rs['folder_id']}' and in_recycle=0 and userid='$pd_uid'");
			$db->query_unbuffered("update {$tpf}folders set folder_size='$total_size' where folder_id='{$rs['folder_id']}' and in_recycle=0 and  userid='$pd_uid'");
		}
	}
	$db->free($q);
	unset($rs);
	return true;
}
function short_date($str){
	return date($str);
}
function create_store_dir($y='',$m='',$d=''){
	global $settings;
	$store_dir = APP_ROOT.$settings['file_path'].'/'.date('Y/m/d/');
	make_dir($store_dir);
	return true;
}
function get_real_ext($file_extension){
	global $settings;
	$file_extension = trim($file_extension);
	if($file_extension){
		$exts = explode(',',$settings['filter_extension']);
		if(in_array($file_extension,$exts)){
			$file_ext = '.'.$file_extension.'.txt';
		}else{
			$file_ext = '.'.$file_extension;
		}
	}else{
		$file_ext = '.txt';
	}
	return $file_ext;
}
function get_file_name($file_name,$file_ext){
	$tmp_ext = $file_ext ? '.'.$file_ext: '';
	return $file_name.$tmp_ext;
}

function can_true_link($file_extension){
	global $settings;
	$arr = explode(',',$settings['true_link_extension']);
	return ($settings['true_link'] && in_array($file_extension,$arr)) ? true : false;
}
function filter_tag($str){
	return str_replace(array('"','/','(',')','*'),'',$str);
}
function make_tags($tags,$tag_arr,$file_id){
	global $db,$tpf,$timestamp,$pd_uid;
	if($tags){
		$tags = filter_tag($tags);
		$tags_str = '';
		for($i=0;$i<count($tag_arr);$i++){
			if($tag_arr[$i]){
				$tags_str .= "'".filter_tag($tag_arr[$i])."',";
				$rs = $db->fetch_one_array("select count(*) as total from {$tpf}file2tag where tag_name='{$tag_arr[$i]}' and file_id='{$file_id}'");
				if(!$rs['total']){
					$ins = array(
					'tag_name' => $tag_arr[$i],
					'file_id' => $file_id,
					);
					$db->query_unbuffered("insert into {$tpf}file2tag set ".$db->sql_array($ins).";");
				}
				unset($rs);
			}
		}
		$tags_str = (substr($tags_str,-1) ==',') ? substr($tags_str,0,-1) : $tags_str;
		$db->query_unbuffered("update {$tpf}tags set tag_count=tag_count-1 where tag_name in (select tag_name from {$tpf}file2tag where file_id='$file_id')");
		$db->query_unbuffered("delete from {$tpf}file2tag where file_id='$file_id' and tag_name not in ($tags_str)");

		$tagdb = explode(',',$tags);
		for($i=0; $i<count($tagdb); $i++){
			$tagdb[$i] = trim($tagdb[$i]);
			if($tagdb[$i]){
				$rs = $db->fetch_one_array("select count(*) as total from {$tpf}tags where tag_name='{$tagdb[$i]}'");
				if(!$rs['total']){
					$ins = array(
					'tag_name' => $tagdb[$i],
					'tag_count' => 1,
					);
					$db->query_unbuffered("insert into {$tpf}tags set ".$db->sql_array($ins).";");
				}
				unset($rs);
				$db->query_unbuffered("update {$tpf}tags set tag_count=(select count(*) from {$tpf}file2tag where tag_name='{$tagdb[$i]}') where tag_name='{$tagdb[$i]}'");
			}
		}

	}else{
		$q = $db->query("select * from {$tpf}file2tag where file_id='$file_id'");
		while($rs = $db->fetch_array($q)){
			$tags_str .= "'{$rs['tag_name']}',";
		}
		$db->free($q);
		unset($rs);
		$tags_str = (substr($tags_str,-1) ==',') ? substr($tags_str,0,-1) : $tags_str;
		$tags_str = filter_tag($tags_str);
		if($tags_str){
			$db->query_unbuffered("update {$tpf}tags set tag_count=tag_count-1 where tag_name in ($tags_str)");
			$db->query_unbuffered("delete from {$tpf}file2tag where file_id='$file_id'");
		}
	}
}
function flashget_encode($t_url,$uid){
	$prefix = "Flashget://";
	$FlashgetURL = $prefix.base64_encode("[FLASHGET]".$t_url."[FLASHGET]")."&".$uid;
	return $FlashgetURL;
}

function thunder_encode($url){
	$thunderPrefix = "AA";
	$thunderPosix = "ZZ";
	$thunderTitle = "thunder://";
	$thunderUrl = $thunderTitle.base64_encode($thunderPrefix.$url.$thunderPosix);
	return $thunderUrl;
}
function file_icon($ext,$fd = 'filetype',$align='absmiddle'){
	$icon = APP_ROOT."images/{$fd}/".$ext.".gif";
	if(file_exists($icon)){
		$img = "<img src='images/{$fd}/{$ext}.gif' align='{$align}' border='0' />";
	}else{
		$img = "<img src='images/{$fd}/file.gif' align='{$align}' border='0' />";
	}
	return $img;
}
function mime_type( $ext ){
	$mime = array(
	'avi'  => 'video/x-msvideo',
	'bmp'  => 'image/bmp',
	'css'  => 'text/css',
	'js'   => 'application/x-javascript js',
	'doc'  => 'application/msword',
	'gif'  => 'image/gif',
	'htm'  => 'text/html',
	'html' => 'text/html',
	'jpg'  => 'image/jpeg',
	'jpeg' => 'image/jpeg',
	'mov'  => 'video/quicktime',
	'mpeg' => 'video/mpeg',
	'mp3'  => 'audio/mpeg mpga mp2 mp3',
	'pdf'  => 'application/pdf',
	'php'  => 'text/html',
	'png'  => 'image/png',
	'qt'   => 'video/quicktime',
	'rar'  => 'application/x-rar',
	'swf'  => 'application/x-shockwave-flash swf',
	'txt'  => 'text/plain',
	'wmv'  => 'video/x-ms-wmv',
	'xml'  => 'text/xml',
	'xsl'  => 'text/xml',
	'xls'  => 'application/msexcel x-excel',
	'zip'  => 'application/zip x-zip',
	'torrent' => 'application/x-bittorrent',

	);
	return isset($mime[$ext]) ? $mime[$ext] : 'application/octet-stream';
}

function get_my_nav($nav_arr=array()){
	global $db,$tpf,$pd_uid,$pd_gid,$group_settings;
	$rs = $db->fetch_one_array("select user_store_space from {$tpf}users where userid='$pd_uid'");
	if($rs['user_store_space'] ==0){
		$arr['max_storage'] = $group_settings[$pd_gid]['max_storage']==0 ? __('no_limit') : $group_settings[$pd_gid]['max_storage'];
	}else{
		$arr['max_storage'] = $rs['user_store_space'];
	}
	unset($rs);

	$file_size_total = $db->result_first("select sum(file_size) from {$tpf}files where userid='$pd_uid'");
	$arr['now_space'] = get_size($file_size_total);
	$file_size_total = ($file_size_total > get_byte_value($arr['max_storage'])) ? get_byte_value($arr['max_storage']) : $file_size_total;
	$arr['disk_fill'] = $arr['max_storage'] ? @round($file_size_total/get_byte_value($arr['max_storage']),1)*120 : 0;
	$arr['disk_percent'] = $arr['max_storage'] ? @round($file_size_total/get_byte_value($arr['max_storage']),3)*100 : 0;
	$arr['disk_remain'] = 100-$arr['disk_percent'];
	$arr['disk_space'] = $arr['max_storage'] ? get_size(get_byte_value($arr['max_storage'])-$file_size_total) : __('no_limit');
	if(is_array($nav_arr)){
		$arr = array_merge($arr,$nav_arr);
	}
	return $arr;
}
function get_rank($rank){
	if($rank){
		$sun = floor($rank/16);
		$moon = floor(($rank-16*$sun)/4);
		$star = $rank-16*$sun-4*$moon;
		$rtn = str_repeat('<img src="images/lv_sun.gif" align="absmiddle" border="0">',$sun);
		$rtn .= str_repeat('<img src="images/lv_moon.gif" align="absmiddle" border="0">',$moon);
		$rtn .= str_repeat('<img src="images/lv_star.gif" align="absmiddle" border="0">',$star);
	}else{
		$rtn = '<span class="f10">N/A</span>';
	}
	return $rtn;
}
function update_rank(){
	global $pd_uid,$db,$tpf,$settings;
	$rs = $db->fetch_one_array("select rank,exp from {$tpf}users where userid='$pd_uid' limit 1");
	if($rs){
		if(($rs['rank']+1)*$settings['exp_const']<=$rs['exp']){
			$rank = $rs['rank']+1;
			$exp = $rs['exp']-($rs['rank']+1)*$settings['exp_const'];
			$db->query_unbuffered("update {$tpf}users set rank='$rank',exp='$exp' where userid='$pd_uid' limit 1");
		}
	}
	unset($rs);
}
function preview_file($file,$autostart = 0){
	global $settings;
	$v_width = 500;
	$v_height = 310;
	$a_width = 500;
	$a_height = 50;
	if(is_array($file) && $settings['open_file_preview'] && $file['is_checked']){

		if($file['file_extension'] =='swf'){
			$rtn = '<script type="text/javascript" reload="1">document.write(AC_FL_RunContent(\'width\', \''.$v_width.'\', \'height\', \''.$v_height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', \''.$file['preview_link'].'\', \'quality\', \'high\', \'bgcolor\', \'#ffffff\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\'));</script>';

		}elseif($file['is_image']){
			$rtn = '<img src="'.$file['file_thumb'].'" id="file_thumb" onload="resize_img(\'file_thumb\',400,300);" border="0">';

		}elseif($file['file_extension'] =='mp3'){
			$rtn = '<script type="text/javascript" src="includes/js/audio-player.js"></script>';
			$rtn .= '<script type="text/javascript">  ';
			$rtn .= '	AudioPlayer.setup("includes/js/audio-player.swf", {   ';
			$rtn .= '		width: '.$a_width.',';
			$rtn .= '		transparentpagebg: "yes"      ';
			$rtn .= '	});   ';
			$rtn .= '</script>  ';
			$rtn .= '<p id="audioplayer_1">audioplayer online</p>  ';
			$rtn .= '<script type="text/javascript">  ';
			$rtn .= 'AudioPlayer.embed("audioplayer_1", {soundFile: "'.$file['preview_link'].'",titles: "'.$file['file_name'].'"});';
			$rtn .= '</script>';

		}elseif(in_array($file['file_extension'],array('wma','mid','wav'))){
			$rtn = '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="'.$a_width.'" height="64"><param name="invokeURLs" value="0"><param name="autostart" value="'.$autostart.'" /><param name="url" value="'.$file['preview_link'].'" /><embed src="'.$file['preview_link'].'" autostart="'.$autostart.'" type="application/x-mplayer2" width="'.$a_width.'" height="64"></embed></object>';

		}elseif(in_array($file['file_extension'],array('ra','rm','ram'))){
			$mediaid = 'media_'.random(3);
			$rtn = '<object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" width="'.$a_width.'" height="32"><param name="autostart" value="'.$autostart.'" /><param name="src" value="'.$file['preview_link'].'" /><param name="controls" value="controlpanel" /><param name="console" value="'.$mediaid.'_" /><embed src="'.$file['preview_link'].'" type="audio/x-pn-realaudio-plugin" controls="ControlPanel" console="'.$mediaid.'_" width="'.$a_width.'" height="32"></embed></object>';
		}elseif(in_array($file['file_extension'],array('asf','asx','wmv','mms','avi','mpg','mpeg'))){
			$rtn = '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="'.$v_width.'" height="'.$v_height.'"><param name="invokeURLs" value="0"><param name="autostart" value="'.$autostart.'" /><param name="url" value="'.$file['preview_link'].'" /><embed src="'.$file['preview_link'].'" autostart="'.$autostart.'" type="application/x-mplayer2" width="'.$v_width.'" height="'.$v_height.'"></embed></object>';
		}elseif($file['file_extension'] =='mov'){
			$rtn = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="'.$v_width.'" height="'.$v_height.'"><param name="autostart" value="'.($autostart ? '' : 'false').'" /><param name="src" value="'.$file['preview_link'].'" /><embed src="'.$file['preview_link'].'" autostart="'.($autostart ? 'true' : 'false').'" type="video/quicktime" controller="true" width="'.$v_width.'" height="'.$v_height.'"></embed></object>';
		}elseif(in_array($file['file_extension'],array('rm','rmvb','rtsp'))){
			$mediaid = 'media_'.random(3);
			$rtn = '<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'.$v_width.'" height="'.$v_height.'"><param name="autostart" value="'.$autostart.'" /><param name="src" value="'.$file['preview_link'].'" /><param name="controls" value="imagewindow" /><param name="console" value="'.$mediaid.'_" /><embed src="'.$file['preview_link'].'" type="audio/x-pn-realaudio-plugin" controls="imagewindow" console="'.$mediaid.'_" width="'.$v_width.'" height="'.$v_height.'"></embed></object><br /><object classid="clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA" width="'.$v_width.'" height="32"><param name="src" value="'.$file['preview_link'].'" /><param name="controls" value="controlpanel" /><param name="console" value="'.$mediaid.'_" /><embed src="'.$file['preview_link'].'" type="audio/x-pn-realaudio-plugin" controls="controlpanel" console="'.$mediaid.'_" width="'.$v_width.'" height="32"'.($autostart ? ' autostart="true"' : '').'></embed></object>';
		}
	}

	return 	$rtn ? '<div class="file_item">'.__('file_preview').':<br />'.$rtn.'</div><br />' : '';

}
function get_sql($sql){
	global $db,$tpf;
	$q = $db->query($sql);
	$arr = array();
	while ($rs = $db->fetch_array($q)) {
		$arr[] = $rs;
	}
	$db->free($q);
	unset($rs);
	return $arr;
}

function urr($str,$vars){
	global $settings;
	if($settings['open_rewrite']){
		switch($str){
			case 'viewfile':
				parse_str($vars);
				return "file-{$file_id}.html";
				break;

				/*case 'downfile':
				$arr = explode('&',trim($vars));
				parse_str($vars);

				if(count($arr)){
				return "downfile-{$file_id}.html";
				}
				break;*/

			case 'space':
				parse_str($vars);
				return 'space-'.rawurlencode($username).'.html';
				break;
			case 'public':
				$arr = explode('&',trim($vars));
				parse_str($vars);
				if(count($arr)==2){
					return "public-$pid-$cate_id.html";
				}else{
					return "public.html";
				}
				break;

			default:
				return $vars ? $str.'.php?'.$vars : $str.'.php';
		}

	}else{
		return $vars ? $str.'.php?'.$vars : $str.'.php';
	}
}
function get_pay_status($str){
	if($str =='success'){
		$rtn = '<span class="txtgreen">'.__('success').'</span>';
	}elseif($str =='fail'){
		$rtn = '<span class="txtred">'.__('fail').'</span>';
	}elseif($str =='pendding'){
		$rtn = '<span class="txtblue">'.__('pendding').'</span>';
	}else{
		$rtn = 'Unknown';
	}
	return $rtn;
}
function make_dir($path){
	if(!is_dir($path)){
		$str = dirname($path);
		if($str){
			make_dir($str.'/');
			@mkdir($path,0777);
			chmod($path,0777);
			write_file($path.'index.htm',' ');
		}
	}
}
function read_file($f) {
	if (file_exists($f)) {
		if (PHP_VERSION >= "4.3.0") return file_get_contents($f);
		$fp = fopen($f,"rb");
		$fsize = filesize($f);
		$c = fread($fp, $fsize);
		fclose($fp);
		return $c;
	} else{
		exit("<b>$f</b> does not exist!");
	}
}
function write_file($f,$str,$mode = 'wb') {
	$fp = fopen($f,$mode);
	if (!$fp) {
		exit("Can not open file <b>$f</b> .code:1");
	}
	if(is_writable($f)){
		if(!fwrite($fp,$str)){
			exit("Can not write file <b>$f</b> .code:2");
		}
	}else{
		exit("Can not write file <b>$f</b> .code:3");
	}
	fclose($fp);
}
function upload_file($source, $target) {
	if (function_exists('move_uploaded_file') && @move_uploaded_file($source, $target)) {
		@chmod($target, 0666);
		return $target;
	} elseif (@copy($source, $target)) {
		@chmod($target, 0666);
		return $target;
	} elseif (@is_readable($source)) {
		if ($fp = @fopen($source,'rb')) {
			@flock($fp,2);
			$filedata = @fread($fp,@filesize($source));
			@fclose($fp);
		}
		if ($fp = @fopen($target, 'wb')) {
			@flock($fp, 2);
			@fwrite($fp, $filedata);
			@fclose($fp);
			@chmod ($target, 0666);
			return $target;
		} else {
			return false;
		}
	}
}
function aheader($url){
	header("Location: ".$url);
	exit;
}

function ip_encode($ip){
	global $pd_gid;
	if($pd_gid==1){
		return $ip;
	}else{
		$arr = explode('.',$ip);
		for($i=0;$i<count($arr)-1;$i++){
			return $arr[0].'.'.$arr[1].'.*.*';
		}
	}
}
function get_profile($uid,$col='*'){
	global $db,$tpf,$pd_uid;
	$uid = $uid ? (int)$uid : $pd_uid;
	if($col=='*'){
		return $db->fetch_one_array("select * from {$tpf}users where userid='$uid' limit 1");
	}else{
		return @$db->result_first("select $col from {$tpf}users where userid='$uid' limit 1");
	}
}
function reseller_log($username,$vip_gid,$days,$price){
	global $db,$tpf,$timestamp,$app_uid;
	$ins = array(
	'userid' => $app_uid,
	'username' => $username,
	'type' => $vip_gid,
	'days' => $days,
	'fee' => $price,
	'in_time' => $timestamp,
	);
	$db->query_unbuffered("insert into {$tpf}reseller_log set ".$db->sql_array($ins).";");
}
function get_ids($str){
	$str2 = '';
	if($str){
		$arr = explode(',',$str);
		for($i=0;$i<count($arr);$i++){
			if(is_numeric($arr[$i])){
				$str2 .= (int)$arr[$i].',';
			}
		}
		return $str2 ? substr($str2,0,-1) : '';
	}else{
		return $str2;
	}
}
function nav_path($folder_id,$uid,$only_txt=0){
	global $db,$tpf,$auth;
	$username = $db->result_first("select username from {$tpf}users where userid='$uid' limit 1");
	$rs = $db->fetch_one_array("select parent_id,folder_name,folder_id from {$tpf}folders where folder_id='$folder_id' and userid='$uid'");
	$str = '';
	if($rs['parent_id']!=0){
		$str .= nav_path($rs['parent_id'],$uid,$only_txt);
	}
	if($only_txt){
		$str .= $rs['folder_name'] ? ' '.$rs['folder_name'].' /' : '';
	}else{
		if($auth[core]!='pt'){
			$str .= $rs['folder_name'] ? '<a href="'.urr("space","username={$username}&folder_id={$rs['folder_id']}").'">'.$rs['folder_name'].'</a>&raquo; ' : '';
		}else{
			$str .= $rs['folder_name'] ? '<a href="'.urr("space","username={$username}&folder_id={$rs['folder_id']}").'">'.$rs['folder_name'].'</a><span class="divider"><i class="icon-angle-right"></i></span>' : '';
		}
	}
	unset($rs);
	return $str;
}
function dirs_num($folder_id,$uid){
	global $db,$tpf;
	$str = dir_num($folder_id,$uid,1);
	$arr = explode('/',$str);
	$count = count($arr);
	return $count;
}
function dir_num($folder_id,$uid){
	global $db,$tpf;
	$rs = $db->fetch_one_array("select parent_id,folder_name,folder_id from {$tpf}folders where folder_id='$folder_id' and userid='$uid'");
	$str = '';
	if($rs['parent_id']!=0){
		$str .= dir_num($rs['parent_id'],$uid,$only_txt);
	}
	$str .= $rs['folder_name'] ? '/' : '';
	return $str;
}
function clear_html($str,$len=50){
	return str_replace("\r\n",' ',cutstr(preg_replace("/<.+?>/i","",$str),$len));
}
function filter_word($str){
	global $settings;
	if(!empty($settings['filter_word'])){
		$arr = explode(',', $settings['filter_word']);
		foreach($arr as $k=>$v){
			$str = str_ireplace($v, '*', $str);
		}
	}
	return $str;
}
function is_vip($uid){
	global $timestamp;
	$myinfo = get_profile($uid);
	if($myinfo['vip_id']>0 && $myinfo['vip_end_time']>$timestamp){
		return true;
	}else{
		return false;
	}
}
function vip_promote($uid,$file_key,$fee){
	global $db,$tpf;
		$gid = get_profile($uid,'gid');
		if($uid && $gid!=3){
			//通过文件分享分成
			$d_year = (int)date('Y');
			$d_month = (int)date('n');
			$d_day = (int)date('j');
			$temperature = get_profile($uid,'temperature');
			$today_vip_promoted = @(int)$db->result_first("SELECT vip_orders FROM {$tpf}ustats_day WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day'");
			if($temperature>=100){
				if($today_vip_promoted<2){
					$money = 0.4*$fee;
				}elseif($today_vip_promoted<11){
					$money = 0.5*$fee;
				}elseif($today_vip_promoted<26){
					$money = 0.5*$fee;
				}elseif($today_vip_promoted<46){
					$money = 0.5*$fee;
				}else{
					$money = 0.5*$fee;
				}
			}else{
				if($today_vip_promoted<2){
					$money = 0.4*$fee;
				}elseif($today_vip_promoted<11){
					$money = 0.25*$fee;
				}elseif($today_vip_promoted<26){
					$money = 0.3*$fee;
				}elseif($today_vip_promoted<46){
					$money = 0.35*$fee;
				}else{
					$money = 0.4*$fee;
				}
			}
			
			//写入用户统计表
			money_act($uid,$money,2,'promote_vip');
			update_ustats_day($uid,'vip_orders','vip_earns',$money);
			update_ustats_hour($uid,'vip_orders','vip_earns',$money);
		}
}
function update_ustats_day($uid,$type_count,$type_earn,$fee){
	global $db,$tpf;
	$d_year = (int)date('Y');
	$d_month = (int)date('n');
	$d_day = (int)date('j');
	$fee = (int)$fee;
	$rs = (int)$db->result_first("SELECT count(*) FROM {$tpf}ustats_day WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day'");
	if(!$rs){
		$ins = array(
		'd_year'=>$d_year,
		'd_month'=>$d_month,
		'd_day'=>$d_day,
		'userid'=>$uid,
		);
		$db->query_unbuffered("insert into  {$tpf}ustats_day set ".$db->sql_array($ins)."");
	}
	
	if($uid && $type_count && $type_earn){
		$db->query_unbuffered("UPDATE {$tpf}ustats_day SET total_earns=total_earns+$fee,$type_count=$type_count+1,$type_earn=$type_earn+$fee WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day'");
	}
}
function update_ustats_hour($uid,$type_count,$type_earn,$fee){
	global $db,$tpf;
	$d_year = (int)date('Y');
	$d_month = (int)date('n');
	$d_day = (int)date('j');
	$d_hour = (int)date('G');
	$fee = (int)$fee;
	
	$rs = (int)$db->result_first("SELECT count(*) FROM {$tpf}ustats_hour WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day' and d_hour='$d_hour'");
	if(!$rs){
		$ins = array(
		'd_year'=>$d_year,
		'd_month'=>$d_month,
		'd_day'=>$d_day,
		'd_hour'=>$d_hour,
		'userid'=>$uid,
		);
		$db->query_unbuffered("insert into  {$tpf}ustats_hour set ".$db->sql_array($ins)."");
	}
	if($uid && $type_count && $type_earn && $fee){
		$db->query_unbuffered("UPDATE {$tpf}ustats_hour SET total_earns=total_earns+$fee,$type_count=$type_count+1,$type_earn=$type_earn+$fee WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day' and d_hour='$d_hour'");
	}
}
function update_ustats_count_hour($uid,$action){
	global $db,$tpf;
	$d_year = (int)date('Y');
	$d_month = (int)date('n');
	$d_day = (int)date('j');
	$d_hour = (int)date('G');
	
	$rs = (int)$db->result_first("SELECT count(*) FROM {$tpf}ustats_hour WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day' and d_hour='$d_hour'");
	if(!$rs){
		$ins = array(
		'd_year'=>$d_year,
		'd_month'=>$d_month,
		'd_day'=>$d_day,
		'd_hour'=>$d_hour,
		'userid'=>$uid,
		);
		$db->query_unbuffered("insert into  {$tpf}ustats_hour set ".$db->sql_array($ins)."");
	}
	$db->query_unbuffered("UPDATE {$tpf}ustats_hour SET $action=$action+1 WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day' and d_hour='$d_hour'");
}
function update_ustats_count_day($uid,$action){
	global $db,$tpf;
	$d_year = (int)date('Y');
	$d_month = (int)date('n');
	$d_day = (int)date('j');
	
	$rs = (int)$db->result_first("SELECT count(*) FROM {$tpf}ustats_day WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day'");
	if(!$rs){
		$ins = array(
		'd_year'=>$d_year,
		'd_month'=>$d_month,
		'd_day'=>$d_day,
		'userid'=>$uid,
		);
		$db->query_unbuffered("insert into  {$tpf}ustats_day set ".$db->sql_array($ins)."");
	}
	$db->query_unbuffered("UPDATE {$tpf}ustats_day SET $action=$action+1 WHERE userid='$uid' and d_year='$d_year' and d_month='$d_month' and d_day='$d_day'");
}
function get_email_tpl($tpl,$email_content_body,$email_content_url){
	global $settings;
	switch($tpl){
		case's':
			break;
		default:
$email_body = '
<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#454545" align="center">
    <tbody><tr>
        <td>
            <table width="600" cellspacing="0" cellpadding="0" border="0" align="center">
                <tbody>
                    <tr>
                        <td width="24" bgcolor="#ffffff">&nbsp;</td>
                        <td width="552" bgcolor="#ffffff">
                            <table width="552" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center" style="padding: 10px;">
                                <tbody>
                                    <tr>
                                        <td width="352" bgcolor="#ffffff">
                                            <img width="157" height="64" src="'.$settings['website_url'].'static/img/logo_dark.png">
                                        </td>
                                        <td width="200" valign="top" bgcolor="#ffffff">
                                            <p style="color:#999; font:normal 12px/22px Arial, Helvetica, sans-serif; margin:0; padding:0;"></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" width="552" bgcolor="#ffffff">
                                            <div style="height:1px;border-bottom:1px dashed #C5C5C5; font:normal 12px/22px Arial, Helvetica, sans-serif; margin:12px 0; padding:0;"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="552" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
                                <tbody>
                                    <tr>
                                        <td width="552" bgcolor="#ffffff">
                                            <h3 style="font:normal 700 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0;">尊敬的用户，您好!</h3>
                                        </td>
                                    </tr>
                                                                        <tr>
                                        <td height="12" bgcolor="#ffffff">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="552" bgcolor="#ffffff">
                                            <p style="font:normal 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0; text-indent:2em;">'.$email_content_body.'</p>
                                        </td>
                                    </tr>
                                                                        <tr>
                                        <td height="12" bgcolor="#ffffff">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="552" bgcolor="#ffffff">
                                            <p style="text-indent:28px; font:normal 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0; text-indent:2em;"><a href="'.$email_content_url.'" target="_blank">'.$email_content_url.'</a><span style="color:#336699; font:normal 700 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0;"></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="552" bgcolor="#ffffff">
                                            <p style="font:normal 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0; text-indent:2em; color:#999;">(如链接无法点击，请复制链接到浏览器地址栏打开页面)</p>
                                        </td>
                                    </tr>
                                                                        <tr>
                                        <td height="12" bgcolor="#ffffff">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="552" align="right" bgcolor="#ffffff">
                                            <p style=" font:normal 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0;">'.$settings['site_title'].' - '.$settings['site_subtitle'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="552" align="right" bgcolor="#ffffff">
                                            <p style=" font:normal 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0;"><span style="border-bottom:1px dashed #ccc;" t="5" times="">'.date('Y-m-d',$timestamp).'</span></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="552" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
                              <tbody>
                                    <tr>
                                        <td colspan="2" width="552" bgcolor="#ffffff">
                                            <div style="height:1px;border-bottom:1px dashed #C5C5C5; font:normal 12px/22px Arial, Helvetica, sans-serif; margin:12px 0; padding:0;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="552" bgcolor="#ffffff">
                                            <p style="color:#999; font:normal 14px/24px Arial, Helvetica, sans-serif; margin:0; padding:0; font-size:12px; text-indent:2em;">这是一封自动生成的邮件，请勿直接回复本邮件。验证有效期为1天，请在有效期内修改您的密码！</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="12" bgcolor="#ffffff">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="552" align="center" bgcolor="#ffffff">
                                            <img width="100" height="38" src="'.$settings['website_url'].'static/img/logo_dark.png">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="24" bgcolor="#ffffff">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</tbody></table>';
			break;
	}
	return $email_body;
}
function send_email($to,$subject,$body,$from='',$fromname='',$stmp = true, $sender='',$host='',$port='',$ssl='',$username='',$password=''){
	$mail = new phpmailer;

	if (!$stmp) {
		$mail->IsMail();
	} else {
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		$mail->Host       = $host;
		if ($ssl) {
			$mail->SMTPSecure = "ssl";
		}
		if ($port!='') {
			$mail->Port       = $port;
		}

		$mail->Username   = $username;
		$mail->Password   = $password;

	}

	$mail->IsHTML(true);
	$mail->Sender       = $sender;
	$mail->FromEmail  = $from;
	$mail->FromName   = $fromname;

	$mail->Subject    = $subject;
	$mail->Body       = $body;
	$mail->CharSet		= 'utf-8';
	$mail->WordWrap   = 50;

	if (is_array($to)) {
		foreach($to as $email) {
			$mail->AddAddress($email,"");
		}
	} else {
		$mail->AddAddress($to,"");
	}
	if ($fromname!='') $mail->AddReplyTo($from,$fromname);
	$mail->IsHTML(true);

	if(!$mail->Send()) {
		return "Mailer Error: " . $mail->ErrorInfo;
	} else {
		return true;
	}
}
function show_adv($id){
	global $db,$tpf,$app_uid;
	$rs = $db->result_first("SELECT code FROM {$tpf}advertisements WHERE advid='$id'");
	if(is_vip($app_uid)){
		return '';
	}
	return $rs;
}
function hi_err($str){
	header('HTTP/1.1 404 Not Found');
	echo "<h1>$str</h1>";
	// header('location:/');
	exit;
}
?>