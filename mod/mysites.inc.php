<?php 
// error_reporting(1);
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
	
	// A记录：地址记录，用来指定域名的IPv4地址（如：8.8.8.8），如果需要将域名指向一个IP地址，就需要添加A记录。
	// CNAME： 如果需要将域名指向另一个域名，再由另一个域名提供ip地址，就需要添加CNAME记录。
	// TXT：在这里可以填写任何东西，长度限制255。绝大多数的TXT记录是用来做SPF记录（反垃圾邮件）。
	// NS：域名服务器记录，如果需要把子域名交给其他DNS服务商解析，就需要添加NS记录。
	// AAAA：用来指定主机名（或域名）对应的IPv6地址（例如：ff06:0:0:0:0:0:0:c3）记录。
	// MX：如果需要设置邮箱，让邮箱能收到邮件，就需要添加MX记录。
	// 显性URL：从一个地址301重定向到另一个地址的时候，就需要添加显性URL记录（注：DNSPod目前只支持301重定向）。
	// 隐性URL：类似于显性URL，区别在于隐性URL不会改变地址栏中的域名。
	// SRV：
$_dns_type = array('A','CNAME','TXT','NS','AAAA','MX');
$_dns_line = array('默认','电信','联通','移动','教育网','鹏博士','铁通','国内','国外','港澳台');
$_dns_ttl = array('5 分钟','2 小时','1 天');
$custom_ttl[86400] = '1 天';
$custom_ttl[7200] = '2 小时';
$custom_ttl[300] = '5 分钟';

function isDomain($domain)
{
    return !empty($domain) && strpos($domain, '--') === false &&
    // preg_match('/^([a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?\.)?[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.us|\.top|\.vip|\.tv|\.org\.cn|\.org|\.be|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.co|\.cn|\.cc|\.biz)$/i', $domain) ? true : false;
    preg_match('/^[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.party|\.tech|\.photo|\.blog|\.world|\.tk|\.ag|\.pw|\.bid|\.so|\.men|\.one|\.win|\.xyz|\.club|\.online|\.bz|\.us|\.top|\.cm|\.vip|\.tv|\.org\.cn|\.org|\.be|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.ws|\.co|\.cn|\.cc|\.biz)$/i', $domain) ? true : false;
}

switch($act){
	case 'delete_record':
		$site_id = (int)gpc('site_id','P','');
		$record_id = (int)gpc('record_id','P','');
		
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE id='$site_id' and userid='$app_uid'");
		if(!$site){
			$error = true;
			$msg[] = '该站点不属于你，无法操作';
		}
		if($record_id){
			if($site['cdn_type']=='ns'){
				$record = $db->fetch_one_array("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and id='$record_id' and userid='$app_uid'");
			}else{
				$record = $db->fetch_one_array("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and id='$record_id' and userid='$app_uid'");
			}
			if(!$record){
				$error = true;
				$msg[] = '该记录不属于你，无法操作';
			}
		}else{
			$error = true;
			$msg[] = '该站点不属于你，无法操作';
		}
		if(!$error){
			if($site['cdn_type']=='ns'){
				$db->query_unbuffered("DELETE FROM {$tpf}site_dns WHERE id='$record_id'");
				// $db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE zone='{$site['domain']}' and host='{$record['dns_host']}' and type='{$record['dns_type']}'");
				$balancer = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_dns WHERE dns_type='{$record['dns_type']}' and dns_host='{$record['dns_host']}' and site_id='$site_id'");
			}else{
				$db->query_unbuffered("DELETE FROM {$tpf}site_cname WHERE id='$record_id'");
				// $cname_host = $record['dns_host'] == '@' ? $site['domain'] : $record['dns_host'].'.'.$site['domain'];
				// $db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE site_id='{$site_id}' and host='{$cname_host}' and type='{$record['dns_type']}'");
				$balancer = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE dns_type='{$record['dns_type']}' and dns_host='{$record['dns_host']}' and site_id='$site_id'");
			}
			$db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE id='{$record['record_id']}'");
			$site_settings = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='{$record['dns_host']}' and site_id='$site_id'");
			if($site_settings){
				if(!$balancer){
					foreach(explode(',',$site_settings['nodes']) as $node){
						$ins = array(
							'node_id'=>$node,
							'task'=>'deletehost',
							'data'=>serialize(array('host'=>$record['dns_host'],'domain'=>$site['domain'])),
							'status'=>'pendding',
							'response'=>'',
							'in_time'=>$timestamp,
						);
						$db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
					}
					$db->query_unbuffered("DELETE FROM {$tpf}site_settings WHERE host='{$record['dns_host']}' and site_id='$site_id'");
				}else{
					node_tasks($site_id,$record['dns_host']);
				}
			}
			$out['status'] = 'success';
		}else{
			$out['status'] = 'fail';
			$out['msg'] = implode('<br>',$msg);
		}
		echo json_encode($out);
		break;
	case 'save_record':
		
		$site_id = (int)gpc('site_id','P','');
		$record_id = (int)gpc('record_id','P','');
		
		$dns_type = trim(gpc('dns_type','P',''));
		$dns_host = trim(gpc('dns_host','P',''));
		$dns_line = trim(gpc('dns_line','P',''));
		$dns_value = trim(gpc('dns_value','P',''));
		$dns_ttl = trim(gpc('dns_ttl','P',''));
		$dns_mx = trim(gpc('dns_mx','P',''));
		$cdn_status = trim(gpc('cdn_status','P',''));
		
		echo json_encode(save_record($site_id,$record_id,$dns_type,$dns_host,$dns_line,$dns_value,$dns_ttl,$dns_mx,$cdn_status));
		break;
	case 'go_step3':
		$site_id = (int)$hiconsole_req[3];
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			$error = true;
			$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
		}
		if(!$error){
			//执行检查事务
			if($site['cdn_type']=='ns'){
				$rs = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_dns WHERE userid='$app_uid' and site_id='$site_id'");
			}elseif($site['cdn_type']=='cn'){
				$rs = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE userid='$app_uid' and site_id='$site_id'");
			}
			if($rs>0){
				$out['status'] = 'success';
			}else{
				$out['status'] = 'fail';
				$msg[] = '请添加记录';
			}
		}else{
			$out['status'] = 'fail';
		}
		$out['msgs'] = implode('<br>',$msg);
		echo json_encode($out);
		break;
	case 'save_settings':
		$site_id = (int)gpc('site_id','P',0);
		$https_switch = (int)gpc('https_switch','P',0);
		$https_only = (int)gpc('https_only','P',0);
		$http2_switch = (int)gpc('http2_switch','P',0);
		$keychains = (int)gpc('keychains','P',0);
		
		$pagespeed_js = (int)gpc('pagespeed_js','P',0);
		$pagespeed_css = (int)gpc('pagespeed_css','P',0);
		$pagespeed_image = (int)gpc('pagespeed_image','P',0);
		$pagespeed_fly = (int)gpc('pagespeed_fly','P',0);
		$pagespeed_compress = (int)gpc('pagespeed_compress','P',0);
		$pagespeed_mode = (int)gpc('pagespeed_mode','P',1);
		
		$browser_cache_switch = (int)gpc('browser_cache_switch','P',0);
		$statics_cache_switch = (int)gpc('statics_cache_switch','P',0);
		$html_cache_switch = (int)gpc('html_cache_switch','P',0);
		$index_cache_switch = (int)gpc('index_cache_switch','P',0);
		$dir_cache_switch = (int)gpc('dir_cache_switch','P',0);
		$all_cache_switch = (int)gpc('all_cache_switch','P',0);
		
		$index_cache_time = (int)gpc('index_cache_time','P',0);
		$browser_cache_time = (int)gpc('browser_cache_time','P',0);
		$statics_cache_time = (int)gpc('statics_cache_time','P',0);
		$html_cache_time = (int)gpc('html_cache_time','P',0);
		$dir_cache_time = (int)gpc('dir_cache_time','P',0);
		$all_cache_time = (int)gpc('all_cache_time','P',0);
		
		$browser_cache_unit = trim(gpc('browser_cache_unit','P','s'));
		$statics_cache_unit = trim(gpc('statics_cache_unit','P','s'));
		$html_cache_unit = trim(gpc('html_cache_unit','P','s'));
		$index_cache_unit = trim(gpc('index_cache_unit','P','s'));
		$dir_cache_unit = trim(gpc('dir_cache_unit','P','s'));
		$all_cache_unit = trim(gpc('all_cache_unit','P','s'));
		
		$index_cache_anyway = (int)gpc('index_cache_anyway','P',0);
		$statics_cache_anyway = (int)gpc('statics_cache_anyway','P',0);
		$html_cache_anyway = (int)gpc('html_cache_anyway','P',0);
		$dir_cache_anyway = (int)gpc('dir_cache_anyway','P',0);
		$all_cache_anyway = (int)gpc('all_cache_anyway','P',0);
		
		$index_cache_ignore = (int)gpc('index_cache_ignore','P',0);
		$statics_cache_ignore = (int)gpc('statics_cache_ignore','P',0);
		$html_cache_ignore = (int)gpc('html_cache_ignore','P',0);
		$dir_cache_ignore = (int)gpc('dir_cache_ignore','P',0);
		$all_cache_ignore = (int)gpc('all_cache_ignore','P',0);
		
		$static_cachefly = (int)gpc('static_cachefly','P',0);
		$static_compress_gzip = (int)gpc('static_compress_gzip','P',0);
		$static_compress_brotli = (int)gpc('static_compress_brotli','P',0);
		
		$waf_pro = (int)gpc('waf_pro','P',0);
		$antiddos_pro = (int)gpc('antiddos_pro','P',0);
		$anti_cc = (int)gpc('anti_cc','P',0);
		$safe_header = (int)gpc('safe_header','P',0);
		
		$host = trim(gpc('host','P','@'));
		$source_protocol = trim(gpc('source_protocol','P','http'));
		$host = $host ? $host : '@';
		$source = gpc('source','P',array());
		$node_group = gpc('node_group','P',array());
		
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			$error = true;
			$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
		}
		
		if($myinfo['wealth']<0){
			$error = true;
			$msg[] = '您的帐号已欠费，请充值后再试。';
		}
		if($source){
			$main_detected = false;
			foreach($source as $record_id=>$record){
				if($record['cdn_port']>65535 || $record['cdn_port']<1){
					$error = true;
					$msg[] = '取源端口设置有误[1-65535]';
				}
				if($record['cdn_weight']>10 || $record['cdn_port']<1){
					$error = true;
					$msg[] = '取源权重设置有误[1-10]';
				}
				if($record['cdn_fails']>10 || $record['cdn_fails']<1){
					$error = true;
					$msg[] = '取源最大失败数设置有误[1-10]';
				}
				if($record['cdn_wait']>300 || $record['cdn_wait']<30){
					$error = true;
					$msg[] = '取源静默时间设置有误[30-300]';
				}
				if(!in_array($record['cdn_type'],array('main','backup','down'))){
					$error = true;
					$msg[] = '线路类型设置有误';
				}
				if($record['cdn_type']=='main'){
					$main_detected = true;
				}
			}
			if(!$main_detected){
				$error = true;
				$msg[] = '必须设置至少一个主线路取源配置';
			}
		}else{
			$error = true;
			$msg[] = '取源配置设置有误';
		}
		//https 选项卡判断
		if($https_switch){
			$https_switch = 1;
			if($keychains){
				$rs = $db->result_first("SELECT count(*) FROM {$tpf}keychains WHERE userid='$app_uid' and id='{$keychains}'");
				if(!$rs){
					$error = true;
					$msg[] = '没有找到 HTTPS 密钥';
				}
			}else{
				$error = true;
				$msg[] = '您还没有选择 HTTPS 密钥';
			}
		}
		if($https_only){
			$https_only = 1;
		}
		
		if(!in_array($source_protocol,array('https','http'))){
			$error = true;
			$msg[] = '取源协议设置有误';
		}
		
		//缓存规则选项卡判断
		$browser_cache_switch = $browser_cache_switch ? 1 : 0;
		$statics_cache_switch = $statics_cache_switch ? 1 : 0;
		$html_cache_switch = $html_cache_switch ? 1 : 0;
		$index_cache_switch = $index_cache_switch ? 1 : 0;
		$dir_cache_switch = $dir_cache_switch ? 1 : 0;
		$all_cache_switch = $all_cache_switch ? 1 : 0;
		
		$index_cache_ignore = $index_cache_ignore ? 1 : 0;
		$statics_cache_ignore = $statics_cache_ignore ? 1 : 0;
		$html_cache_ignore = $html_cache_ignore ? 1 : 0;
		$dir_cache_ignore = $dir_cache_ignore ? 1 : 0;
		$all_cache_ignore = $all_cache_ignore ? 1 : 0;
		
		if(!in_array($browser_cache_unit,array('s','m','h','d'))){
			$error = true;
			$msg[] = '浏览器缓存时间单位应在秒，分，时，天中';
		}
		if(!in_array($statics_cache_unit,array('s','m','h','d'))){
			$error = true;
			$msg[] = '静态文件缓存时间单位应在秒，分，时，天中';
		}
		if(!in_array($html_cache_unit,array('s','m','h','d'))){
			$error = true;
			$msg[] = '静态HTML缓存时间单位应在秒，分，时，天中';
		}
		if(!in_array($index_cache_unit,array('s','m','h','d'))){
			$error = true;
			$msg[] = '首页缓存时间单位应在秒，分，时，天中';
		}
		if(!in_array($dir_cache_unit,array('s','m','h','d'))){
			$error = true;
			$msg[] = '目录缓存时间单位应在秒，分，时，天中';
		}
		if(!in_array($all_cache_unit,array('s','m','h','d'))){
			$error = true;
			$msg[] = '全站缓存时间单位应在秒，分，时，天中';
		}
		switch($browser_cache_unit){
			case's':
				if($browser_cache_time<1 ||$browser_cache_time>86400){
					$error = true;
					$msg[] = '浏览器缓存时间值应在 1-86400 秒 之间';
				}
				break;
			case'm':
				if($browser_cache_time<1 ||$browser_cache_time>1440){
					$error = true;
					$msg[] = '浏览器缓存时间值应在 1-1440 分钟 之间';
				}
				break;
			case'h':
				if($browser_cache_time<1 ||$browser_cache_time>72){
					$error = true;
					$msg[] = '浏览器缓存时间值应在 1-72 小时 之间';
				}
				break;
			case'd':
				if($browser_cache_time<1 ||$browser_cache_time>365){
					$error = true;
					$msg[] = '浏览器缓存时间值应在 1-365 天 之间';
				}
				break;
		}
		switch($statics_cache_unit){
			case's':
				if($statics_cache_time<1 ||$statics_cache_time>86400){
					$error = true;
					$msg[] = '静态文件缓存时间值应在 1-86400 秒 之间';
				}
				break;
			case'm':
				if($statics_cache_time<1 ||$statics_cache_time>1440){
					$error = true;
					$msg[] = '静态文件缓存时间值应在 1-1440 分钟 之间';
				}
				break;
			case'h':
				if($statics_cache_time<1 ||$statics_cache_time>72){
					$error = true;
					$msg[] = '静态文件缓存时间值应在 1-72 小时 之间';
				}
				break;
			case'd':
				if($statics_cache_time<1 ||$statics_cache_time>365){
					$error = true;
					$msg[] = '静态文件缓存时间值应在 1-365 天 之间';
				}
				break;
		}
		switch($html_cache_unit){
			case's':
				if($html_cache_time<1 ||$html_cache_time>86400){
					$error = true;
					$msg[] = 'HTML缓存时间值应在 1-86400 秒 之间';
				}
				break;
			case'm':
				if($html_cache_time<1 ||$html_cache_time>1440){
					$error = true;
					$msg[] = 'HTML缓存时间值应在 1-1440 分钟 之间';
				}
				break;
			case'h':
				if($html_cache_time<1 ||$html_cache_time>72){
					$error = true;
					$msg[] = 'HTML缓存时间值应在 1-72 小时 之间';
				}
				break;
			case'd':
				if($html_cache_time<1 ||$html_cache_time>365){
					$error = true;
					$msg[] = 'HTML缓存时间值应在 1-365 天 之间';
				}
				break;
		}
		switch($index_cache_unit){
			case's':
				if($index_cache_time<1 ||$index_cache_time>86400){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-86400 秒 之间';
				}
				break;
			case'm':
				if($index_cache_time<1 ||$index_cache_time>1440){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-1440 分钟 之间';
				}
				break;
			case'h':
				if($index_cache_time<1 ||$index_cache_time>72){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-72 小时 之间';
				}
				break;
			case'd':
				if($index_cache_time<1 ||$index_cache_time>365){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-365 天 之间';
				}
				break;
		}
		switch($dir_cache_unit){
			case's':
				if($dir_cache_time<1 ||$dir_cache_time>86400){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-86400 秒 之间';
				}
				break;
			case'm':
				if($dir_cache_time<1 ||$dir_cache_time>1440){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-1440 分钟 之间';
				}
				break;
			case'h':
				if($dir_cache_time<1 ||$dir_cache_time>72){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-72 小时 之间';
				}
				break;
			case'd':
				if($dir_cache_time<1 ||$dir_cache_time>365){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-365 天 之间';
				}
				break;
		}
		switch($all_cache_unit){
			case's':
				if($all_cache_time<1 ||$all_cache_time>86400){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-86400 秒 之间';
				}
				break;
			case'm':
				if($all_cache_time<1 ||$all_cache_time>1440){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-1440 分钟 之间';
				}
				break;
			case'h':
				if($all_cache_time<1 ||$all_cache_time>72){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-72 小时 之间';
				}
				break;
			case'd':
				if($all_cache_time<1 ||$all_cache_time>365){
					$error = true;
					$msg[] = '首页缓存时间值应在 1-365 天 之间';
				}
				break;
		}
		
		//加速设置选项卡
		
		$static_cachefly = $static_cachefly ? 1 : 0;
		$static_compress_gzip = $static_compress_gzip ? 1 : 0;
		$static_compress_brotli = $static_compress_brotli ? 1 : 0;
		
		//安全选项卡
		$waf_pro = $waf_pro ? 1 : 0;
		$antiddos_pro = $antiddos_pro ? 1 : 0;
		if($antiddos_pro && !in_array(4,$node_group)){
			$node_group[] = 4;
		}
		if($node_group){
			foreach($node_group as $v){
				if(!in_array($v,array(0,1,2,3,4))){
					$error = true;
					$msg[] = '节点信息有误';
				}
			}
			$node_group[] = 5;
		}else{
			$error = true;
			$msg[] = '请至少选择一个节点组别';
		}
		if(!$error){
			//写入数据库
			//写入记录表
			if($source){
				foreach($source as $record_id=>$record){
					$record['cdn_status'] = $record['cdn_type']!='down' ? 'on' : 'off';
					if($site['cdn_type']=='ns'){
						$db->query_unbuffered("UPDATE {$tpf}site_dns SET cdn_port='{$record['cdn_port']}',cdn_type='{$record['cdn_type']}',cdn_status='{$record['cdn_status']}',cdn_weight='{$record['cdn_weight']}',cdn_fails='{$record['cdn_fails']}',cdn_wait='{$record['cdn_wait']}' WHERE id='$record_id'");
					}else{
						$db->query_unbuffered("UPDATE {$tpf}site_cname SET cdn_port='{$record['cdn_port']}',cdn_type='{$record['cdn_type']}',cdn_status='{$record['cdn_status']}',cdn_weight='{$record['cdn_weight']}',cdn_fails='{$record['cdn_fails']}',cdn_wait='{$record['cdn_wait']}' WHERE id='$record_id'");
					}
				}
			}
			//写入配置表
			$ins = array(
				'source_protocol'=>$source_protocol,
				'https_switch'=>$https_switch,
				'https_only'=>$https_only,
				'http2_switch'=>$http2_switch,
				'keychains'=>$keychains,
				'browser_cache_switch'=>$browser_cache_switch,
				'statics_cache_switch'=>$statics_cache_switch,
				'html_cache_switch'=>$html_cache_switch,
				'index_cache_switch'=>$index_cache_switch,
				'dir_cache_switch'=>$dir_cache_switch,
				'all_cache_switch'=>$all_cache_switch,
				'index_cache_time'=>$index_cache_time,
				'browser_cache_time'=>$browser_cache_time,
				'statics_cache_time'=>$statics_cache_time,
				'html_cache_time'=>$html_cache_time,
				'dir_cache_time'=>$dir_cache_time,
				'all_cache_time'=>$all_cache_time,
				'browser_cache_unit'=>$browser_cache_unit,
				'statics_cache_unit'=>$statics_cache_unit,
				'html_cache_unit'=>$html_cache_unit,
				'index_cache_unit'=>$index_cache_unit,
				'dir_cache_unit'=>$dir_cache_unit,
				'all_cache_unit'=>$all_cache_unit,
				'statics_cache_anyway'=>$statics_cache_anyway,
				'html_cache_anyway'=>$html_cache_anyway,
				'index_cache_anyway'=>$index_cache_anyway,
				'dir_cache_anyway'=>$dir_cache_anyway,
				'all_cache_anyway'=>$all_cache_anyway,
				'index_cache_ignore' => $index_cache_ignore,
				'statics_cache_ignore' => $statics_cache_ignore,
				'html_cache_ignore' => $html_cache_ignore,
				'dir_cache_ignore' => $dir_cache_ignore,
				'all_cache_ignore' => $all_cache_ignore,
				'static_cachefly'=>$static_cachefly,
				'static_compress_gzip'=>$static_compress_gzip,
				'static_compress_brotli'=>$static_compress_brotli,
				'waf_pro'=>$waf_pro,
				'antiddos_pro'=>$antiddos_pro,
				'anti_cc'=>$anti_cc,
				'safe_header'=>$safe_header,
				'node_group'=>implode(',',$node_group),
				'pagespeed_js'=>$pagespeed_js,
				'pagespeed_css'=>$pagespeed_css,
				'pagespeed_image'=>$pagespeed_image,
				'pagespeed_mode'=>$pagespeed_mode,
			);
			$ori_sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='$host' and site_id='$site_id'");
			if($ori_sets){
				$set_id = $ori_sets['id'];
				$db->query_unbuffered("UPDATE {$tpf}site_settings SET ".$db->sql_array($ins)." WHERE id='{$set_id}'");
			}else{
				$db->query_unbuffered("INSERT INTO {$tpf}site_settings SET host='$host',site_id='$site_id',".$db->sql_array($ins));
				$set_id = $db->insert_id();
			}
			node_tasks($site_id,$host);
			if($site['cdn_type']=='ns'){
				//NS记录更新根域名记录
				updatedns($site['domain']);
			}else{
				//CNAME记录更新CNAME域名记录
				updatedns($site['ns_data']);
			}
			$out['status'] = 'success';
			$out['msgs'] = '配置已经更新，请等待片刻生效。';
		}else{
			$out['status'] = 'fail';
			$out['msgs'] = implode('<br>',$msg);
		}
		echo json_encode($out);
		break;
	case 'purge_cache':
		if($task!='submit'){
			$site_id = (int)$hiconsole_req[3];
			$host = trim($hiconsole_req[4]);
			$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
			if(!$site){
				$error = true;
				$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
			}
			if($site['cdn_type']=='ns'){
				$host_data = $db->fetch_one_array("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid' and dns_host='$host'");
			}else{
				$host_data = $db->fetch_one_array("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid' and dns_host='$host'");
			}
			if(!$host_data){
				$error = true;
				$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
			}
			require_once template_echo("$mod/$act",$user_tpl_dir);
		}else{
			$site_id = (int)gpc('site_id','P','');
			$host = trim(gpc('host','P',''));
			$url = trim(gpc('url','P',''));
			$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
			if(!$site){
				$error = true;
				$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
			}
			if($site['cdn_type']=='ns'){
				$host_data = $db->fetch_one_array("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid' and dns_host='$host'");
			}else{
				$host_data = $db->fetch_one_array("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid' and dns_host='$host'");
			}
			if(!$host_data){
				$error = true;
				$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
			}
			if(!$error){
				$set_data = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE site_id='$site_id' and host='$host'");
				if($set_data['nodes'] && $url){
					foreach(explode(',',$set_data['nodes']) as $v){
						$ins = array(
							'node_id'=>$v,
							'task'=>'purge',
							'data'=>serialize(array('set_id'=>$set_data['id'],'file'=>$url)),
							'status'=>'pendding',
							'response'=>'',
							'in_time'=>$timestamp,
						);
						$db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
						
						// $ins = array(
							// 'node_id'=>$v,
							// 'task'=>'purge',
							// 'data'=>$host.','.$site['domain'].','.$url,
						// );
						// $db->query_unbuffered("INSERT INTO {$tpf}node_tasks SET ".$db->sql_array($ins));
					}
				}
				$out['status'] = 'success';
				$out['msgs'] = '缓存清理任务已提交';
				
			}else{
				$out['status'] = 'fail';
				$out['msgs'] = implode('<br>',$msg);
			}
			echo json_encode($out);
		}
		break;
	case 'manage_keychains':
		$q = $db->query("SELECT * FROM {$tpf}keychains WHERE userid='$app_uid'");
		$certs = array();
		while($rs=$db->fetch_array($q)){
			$rs['certinfo'] = openssl_x509_parse($rs['cert']);
			$certs[] = $rs;
		}
		require_once template_echo("$mod/$act",$user_tpl_dir);
		break;
	case 'update_keychains':
		$q = $db->query("SELECT id,name FROM {$tpf}keychains WHERE userid='$app_uid'");
		$certs = array();
		while($rs=$db->fetch_array($q)){
			$certs[] = $rs;
		}
		echo json_encode($certs);
		break;
	case 'delete_keychains':
		$id = (int)gpc('id','G',0);
		$has = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_settings WHERE keychains=$id");
		if($has){
			$out['status'] = 'error';
			$out['msgs'] = '密钥使用中，无法删除';
		}else{
			$db->query_unbuffered("DELETE FROM {$tpf}keychains WHERE userid='$app_uid' and id='$id'");
			$out['status'] = 'success';
			$out['msgs'] = '密钥已删除';
			$out['script'] = "\$.get('/mysites/manage_keychains',function(data){\$('#remoteModal div.modal-content').html(data)});update_keychains()";
		}
		echo json_encode($out);
		break;
	case 'add_keychains':
		if($task=='add'){
			$out = array();
			$name = trim(gpc('name','P',''));
			$cert = trim(gpc('cert','P',''));
			$key = trim(gpc('key','P',''));
			
			$certinfo = openssl_x509_parse($cert);
			if(!openssl_x509_check_private_key($cert,$key)){
				$error = true;
				$msg[] = '公钥私钥不匹配';
			}
			if(!$error){
				if($certinfo['validTo_time_t']<=$timestamp){
					$error = true;
					$msg[] = '证书已到期';
				}
			}
			if(!$name){
				$error = true;
				$msg[] = '请输入实例名';
			}
			if(!$error){
				$ins = array(
					'userid'=>$app_uid,
					'name'=>$name,
					'cert'=>$cert,
					'key'=>$key,
				);
				$db->query_unbuffered("INSERT INTO {$tpf}keychains SET ".$db->sql_array($ins));
				
				$out['status'] = 'success';
				$out['msgs'] = '密钥库已更新';
				$out['script'] = 'update_keychains()';
			}else{
				$out['status'] = 'fail';
				$out['msgs'] = implode('<br>',$msg);
			}
			echo json_encode($out);
		}else{
			require_once template_echo("$mod/$act",$user_tpl_dir);
		}
		break;
	case 'delete_site':
		$site_id = (int)$hiconsole_req[3];
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			$error = true;
			$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
		}
		$error = true;
		$msg[] = '当前暂时无法删除域名，请联系客户服务';
		
		if(!$error){
			//执行删除事务
			if($site['cdn_type']=='ns'){
				$db->query_unbuffered("DELETE FROM {$tpf}site_dns WHERE userid='$app_uid' and site_id='$site_id'");
				$db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE zone='{$site['domain']}'");
			}elseif($site['cdn_type']=='cn'){
				$db->query_unbuffered("DELETE FROM {$tpf}site_cname WHERE userid='$app_uid' and site_id='$site_id'");
				$db->query_unbuffered("DELETE FROM {$tpf}dns_records WHERE site_id='{$site['id']}'");
			}
			$db->query_unbuffered("DELETE FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
			
			$nodes = array();
			$q = $db->query("SELECT * FROM {$tpf}nodes WHERE area BETWEEN 1 and 5");
			while($rs=$db->fetch_array($q)){
				$nodes[] = $rs;
			}
			
			//更新dns服务器记录
			deletedns($site['domain']);
			
			// $q = $db->query("SELECT * FROM {$tpf}site_settings WHERE site_id='$site_id'");
			// while($rs=$db->fetch_array($q)){
				foreach($nodes as $v){
					$ins = array(
						'node_id'=>$v['id'],
						'task'=>'deletesite',
						'data'=>serialize(array('domain'=>$site['domain'])),
						'status'=>'pendding',
						'response'=>'',
						'in_time'=>$timestamp,
					);
					$db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
				}
			// }
			// $site_settings = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE site_id='$site_id'");
			// if($site_settings){
				// $q = $db->query("SELECT * FROM {$tpf}nodes");
				// while($rs=$db->fetch_array($q)){
					// $ins = array(
						// 'node_id'=>$rs['id'],
						// 'task'=>'delete2',
						// 'data'=>$site_id,
					// );
					// $db->query_unbuffered("INSERT INTO {$tpf}node_tasks SET ".$db->sql_array($ins));
					
					// $ins = array(
						// 'node_id'=>$rs['id'],
						// 'task'=>'purge',
						// 'data'=>serialize(array('set_id'=>$set_data['id'],'file'=>$url)),
						// 'status'=>'pendding',
						// 'response'=>'',
						// 'in_time'=>$timestamp,
					// );
					// $db->query_unbuffered("INSERT INTO {$tpf}tasks SET ".$db->sql_array($ins));
				// }
				// $db->query_unbuffered("DELETE FROM {$tpf}site_settings WHERE site_id='$site_id'");
			// }
			$out['status'] = 'success';
		}else{
			$out['status'] = 'fail';
		}
		$out['msgs'] = implode('<br>',$msg);
		echo json_encode($out);
		break;
	case 'verify_dns':
		$site_id = (int)gpc('site_id','P','');
		$out['status'] = 'fail';
		
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			$error = true;
			$msg[] = '无法操作此域名，如有疑问，请联系客户服务';
		}
		if($site['cdn_type']=='ns'){
			//ns处理方式
			
			$get_dns = dns_get_record($site['domain'],DNS_NS);
			$custom_dns = explode(',',$site['ns_data']);
			// var_dump($get_dns);
			// var_dump($custom_dns);
			if($get_dns){
				foreach($get_dns as $v){
					// echo $v['target'];
					if(!in_array($v['target'].'.',$custom_dns)){
						$error = true;
						$msg[] = 'DNS记录不正确，或未生效';
						break;
					}
				}
			}else{
				$error = true;
				$msg[] = 'DNS记录不正确，或未生效。';
			}
			if(!$error){
				if($site['status']=='wait_verify'){
					$db->query_unbuffered("UPDATE {$tpf}mysites SET status='success' WHERE id='$site_id'");
				}
				$out['status'] = 'success';
			}
		}elseif($site['cdn_type']=='cn'){
			//cname处理方式
			/*
			$get_dns = dns_get_record($site['domain'],DNS_TXT);
			$error = true;
			foreach($get_dns as $v){
				if(strtolower($v['txt'])==md5($site['id'].$site['domain'])){
					$error = false;
				}
			}
			*/
			$error = true;
			exec("dig -t TXT {$site['domain']} @8.8.8.8",$output);
			foreach($output as $line){
				if(stristr($line,md5($site['id'].$site['domain']))){
					$error = false;
				}
			}
			if(!$error){
				if($site['status']=='wait_verify'){
					$db->query_unbuffered("UPDATE {$tpf}mysites SET status='success' WHERE id='$site_id'");
				}
				$out['status'] = 'success';
			}else{
				$msg[] = 'DNS记录不正确，或未生效。';
			}
		}
		// if(!$error){
			// $q = $db->query("SELECT id FROM {$tpf}site_settings WHERE site_id='$site_id'");
			// while($rs=$db->fetch_array($q)){
				// $db->query_unbuffered("UPDATE {$tpf}node_sites SET task_status='pendding' WHERE set_id='{$rs['id']}' ");
			// }
		// }
		$out['msgs'] = implode('<br>',$msg);
		$out['debug'] = implode(LF,$get_dns);
		echo json_encode($out);
		if($out['status']=='success'){
			if($site['cdn_type']=='ns'){
				// 执行NS类型站点的操作
				$user_ns[] = 'ns1.fbidns.com.';
				$user_ns[] = 'ns2.fbidns.com.';
				$site['has_soa'] = $db->result_first("SELECT COUNT(*) FROM {$tpf}dns_records WHERE type='SOA' and host='@' and zone='{$site['domain']}'");
				if(!$site['has_soa']){
					//NS模式：1.先添加SOA记录
					$ins = array(
						'zone'=>$site['domain'],
						'host'=>'@',
						'type'=>'SOA',
						'data'=>$user_ns[0],
						'ttl'=>86400,
						'view'=>'any',
						'serial'=>0,
						'primary_ns'=>$user_ns[0],
						'second_ns'=>$user_ns[1],
						'site_id'=>$site['id'],
					);
					$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
				}
				$site['has_ns'] = $db->result_first("SELECT COUNT(*) FROM {$tpf}dns_records WHERE type='NS' and host='@' and zone='{$site['domain']}'");
				if(!$site['has_ns']){
					//添加2个NS记录
					$ins = array(
						'zone'=>$site['domain'],
						'host'=>'@',
						'type'=>'NS',
						'data'=>$user_ns[0],
						'ttl'=>86400,
						'view'=>'any',
						'serial'=>0,
						'primary_ns'=>$user_ns[0],
						'second_ns'=>$user_ns[1],
						'site_id'=>$site['id'],
					);
					$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
					$ins = array(
						'zone'=>$site['domain'],
						'host'=>'@',
						'type'=>'NS',
						'data'=>$user_ns[1],
						'ttl'=>86400,
						'view'=>'any',
						'serial'=>0,
						'primary_ns'=>$user_ns[0],
						'second_ns'=>$user_ns[1],
						'site_id'=>$site['id'],
					);
					$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
				}
				$q2 = $db->query("SELECT * FROM `{$tpf}site_dns` WHERE site_id='{$site['id']}'");
				while($rs2=$db->fetch_array($q2)){
					if($rs2['cdn_status']=='on'){
						$ori_sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='{$rs2['dns_host']}' and site_id='{$rs2['site_id']}'");
						if($ori_sets){
							$q3 = $db->query("SELECT * FROM {$tpf}nodes WHERE area IN ({$ori_sets['node_group']})");
							while($rs3=$db->fetch_array($q3)){
								$node_ids[] = $rs3['id'];
								$nodes[] = $rs3;
							}
							$ori_sets['nodes'] = implode(',',$node_ids);
							$db->query_unbuffered("UPDATE {$tpf}site_settings SET nodes='{$ori_sets['nodes']}' WHERE id='{$ori_sets['id']}'");
							node_tasks($site['id'],$rs2['dns_host']);
							do_ns_ns($rs2['dns_host'],$site['domain'],$site['ns_data'],$nodes,$site['id']);
							unset($node_ids);
							unset($nodes);
						}
					}
				}
				updatedns($site['domain']);
			}else{
				//执行CN类型站点的操作
				$q2 = $db->query("SELECT * FROM `{$tpf}site_cname` WHERE site_id='{$site['id']}'");
				while($rs2=$db->fetch_array($q2)){
					if($rs2['cdn_status']=='on'){
						$ori_sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='{$rs2['dns_host']}' and site_id='{$site['id']}'");
						if($ori_sets){
							$q3 = $db->query("SELECT * FROM {$tpf}nodes WHERE area IN ({$ori_sets['node_group']})");
							while($rs3=$db->fetch_array($q3)){
								$node_ids[] = $rs3['id'];
								$nodes[] = $rs3;
							}
							$ori_sets['nodes'] = implode(',',$node_ids);
							$db->query_unbuffered("UPDATE {$tpf}site_settings SET nodes='{$ori_sets['nodes']}' WHERE id='{$ori_sets['id']}'");
							node_tasks($site['id'],$rs2['dns_host']);
							do_cname_ns($rs2['dns_host'],$site['domain'],$site['ns_data'],$nodes,$site['id']);
							unset($node_ids);
							unset($nodes);
						}
					}
				}
				updatedns($site['ns_data']);
			}
		}
		break;
	
	case'add':
		switch($hiconsole_req[3]){
			case'post':
				$domain = trim(gpc('domain','P',''));
				$cdn_type = trim(gpc('cdn_type','P',''));
				if(!$domain){
					$error = true;
					$msg[] = '该域名暂时无法被添加，请联系客户服务。';
				}
				$count = $db->result_first("SELECT count(*) FROM {$tpf}mysites WHERE domain='$domain'");
				if($count){
					$error = true;
					$msg[] = '该域名暂时无法被添加，请联系客户服务。';
				}
				if(!in_array($cdn_type,array('cn','ns'))){
					$error = true;
					$msg[] = '接入类型选择错误，请重试';
				}
				if(!isDomain($domain)){
					$error = true;
					$msg[] = '请输入正确的域名';
				}
				if($cdn_type=='ns'){
					$user_ns[] = 'ns1.fbidns.com.';
					$user_ns[] = 'ns2.fbidns.com.';
					$ns_data = implode(',',$user_ns);
				}elseif($cdn_type=='cn'){
					$ns_data = 'fbicdn.com.';
				}
				if(!$error){
					$ins = array(
						'userid'=>$app_uid,
						'domain'=>$domain,
						'cdn_type'=>$cdn_type,
						'status'=>'pendding',
						'ns_data'=>$ns_data,
						'in_time'=>$timestamp,
					);
					$db->query_unbuffered("INSERT INTO {$tpf}mysites SET ".$db->sql_array($ins));
					$out['status'] = 'success';
					$out['id'] = $db->insert_id();
					$out['msgs'] = '添加成功，请按指示进行下一步操作。';
					//dns内部操作
					if($cdn_type=='ns'){
						//NS模式：1.先添加SOA记录
						$ins = array(
							'zone'=>$domain,
							'host'=>'@',
							'type'=>'SOA',
							'data'=>$user_ns[0],
							'ttl'=>86400,
							'view'=>'any',
							'serial'=>0,
							'primary_ns'=>$user_ns[0],
							'second_ns'=>$user_ns[1],
							'site_id'=>$out['id'],
						);
						$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
						//添加2个NS记录
						$ins = array(
							'zone'=>$domain,
							'host'=>'@',
							'type'=>'NS',
							'data'=>$user_ns[0],
							'ttl'=>86400,
							'view'=>'any',
							'serial'=>0,
							'primary_ns'=>$user_ns[0],
							'second_ns'=>$user_ns[1],
							'site_id'=>$out['id'],
						);
						$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
						$ins = array(
							'zone'=>$domain,
							'host'=>'@',
							'type'=>'NS',
							'data'=>$user_ns[1],
							'ttl'=>86400,
							'view'=>'any',
							'serial'=>0,
							'primary_ns'=>$user_ns[0],
							'second_ns'=>$user_ns[1],
							'site_id'=>$out['id'],
						);
						$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
						//更新dns服务器记录
						updatedns($domain);
					}
				}else{
					$out['status'] = 'fail';
					foreach($msg as $v){
						$msg_text .= $v.'<br>';
					}
					$out['msgs'] = $msg_text;
				}
				echo json_encode($out);
				break;
			default:
				require_once template_echo("frame",$user_tpl_dir);
				break;
		}
		break;
	
	case 'add_2':
		$site_id = (int)$hiconsole_req[3];
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			show_error('无法操作此域名，如有疑问，请联系客户服务');
		}
		if($site['status']=='success'){
			header("location:/mysites/add_3/".$site_id);
		}else{
			if($site['cdn_type']=='ns'){
				$q = $db->query("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid'");
				$records = array();
				while($rs=$db->fetch_array($q)){
					$rs['dns_mx'] = $rs['dns_mx'] ? $rs['dns_mx'] : '-';
					$records[] = $rs;
				}
			}elseif($site['cdn_type']=='cn'){
				$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid'");
				$records = array();
				while($rs=$db->fetch_array($q)){
					$rs['dns_cname'] = $rs['dns_host']=='@' ? $site['domain'].'.'.$site['ns_data'] : $rs['dns_host'].'.'.$site['domain'].'.'.$site['ns_data'];
					$records[] = $rs;
				}
			}
			require_once template_echo("frame",$user_tpl_dir);
		}
		break;
	case 'add_3':
		$site_id = (int)$hiconsole_req[3];
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			show_error('无法操作此域名，如有疑问，请联系客户服务');
		}elseif($site['status']=='pendding'){
			$db->query_unbuffered("UPDATE {$tpf}mysites SET status='wait_verify' WHERE id='$site_id'");
		}
		if($site['status']!='success'){
			if($site['cdn_type']=='ns'){
				$get_dns = dns_get_record($site['domain'],DNS_NS);
			}
		}
		if($site['cdn_type']=='cn'){
			$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid' GROUP BY dns_host");
			$records = array();
			$records[] = array(
				'host'=>$site['domain'],
				'type'=>'TXT',
				'cname'=>md5($site['id'].$site['domain']),
			);
			while($rs=$db->fetch_array($q)){
				$rs['cname'] = $rs['dns_host']=='@' ? $site['domain'].'.'.$site['ns_data'] : $rs['dns_host'].'.'.$site['domain'].'.'.$site['ns_data'];
				$rs['type'] = 'CNAME';
				$rs['host'] = $rs['dns_host']=='@' ? $site['domain'] : $rs['dns_host'].'.'.$site['domain'];
				$records[] = $rs;
			}
		}else{
			$custom_dns = explode(',',$site['ns_data']);
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	
	case 'records':
		$site_id = (int)$hiconsole_req[3];
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			show_error('无法操作此域名，如有疑问，请联系客户服务');
		}
		
		if($site['cdn_type']=='ns'){
			$q = $db->query("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid'");
			$records = array();
			$custom_dns = explode(',',$site['ns_data']);
			$records[] = array(
				'dns_type'=>'NS',
				'dns_host'=>'@',
				'dns_line'=>'默认',
				'dns_value'=>$custom_dns[0],
				'dns_ttl'=>86400,
				'id'=>-1,
				'change_status'=>'unchangeable',
			);
			$records[] = array(
				'dns_type'=>'NS',
				'dns_host'=>'@',
				'dns_line'=>'默认',
				'dns_value'=>$custom_dns[1],
				'dns_ttl'=>86400,
				'id'=>-1,
				'change_status'=>'unchangeable',
			);
			while($rs=$db->fetch_array($q)){
				$rs['change_status'] = 'changeable';
				$rs['dns_mx'] = $rs['dns_mx'] ? $rs['dns_mx'] : '-';
				$records[] = $rs;
			}
		}elseif($site['cdn_type']=='cn'){
			$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid'");
			$records = array();
			while($rs=$db->fetch_array($q)){
				$rs['dns_cname'] = $rs['dns_host'] == '@' ? $site['domain'].'.'.$site['ns_data'] : $rs['dns_host'].'.'.$site['domain'].'.'.$site['ns_data'];
				$rs['change_status'] = 'changeable';
				$records[] = $rs;
			}
		}
		// var_dump($site_id);
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case 'check_addon':
		$site_id = (int)$hiconsole_req[3];
		$param = trim($hiconsole_req[4]);
		$out['status']='success';
		$addon = explode(',',$param);
		if(in_array('antiddos_pro',$addon)){
			// $rs = $db->fetch_one_array("SELECT * FROM {$tpf}addon WHERE userid='$app_uid' and addon='antiddos_pro' and end_time>$timestamp ORDER BY end_time DESC");
			$out['antiddos_pro'] = $myinfo['antiddos_end_time']*1000;
		}
		if(in_array('waf_pro',$addon)){
			// $rs = $db->fetch_one_array("SELECT * FROM {$tpf}addon WHERE userid='$app_uid' and addon='waf_pro' and end_time>$timestamp ORDER BY end_time DESC");
			$out['waf_pro'] = $myinfo['waf_end_time']*1000;
		}
		echo json_encode($out);
		break;
	case 'pay_addon':
		$task = trim(gpc('task','P',''));
		$days = (int)gpc('days','P',30);
		$site_id = (int)gpc('site_id','P',30);
		// $site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		// if(!$site){
			// $error = true;
			// $sysmsg[] = '无法操作此域名，如有疑问，请联系客户服务';
		// }
		if(!in_array($days,array(30,365))){
			$error = true;
			$sysmsg[] = '购买参数不正确，请重试。';
		}else{
			if($task=='waf_pro'){
				$price[30] = 50;
				$price[365] = 500;
				$ziduan = 'waf_end_time';
			}else{
				$price[30] = 999;
				$price[365] = 9999;
				$ziduan = 'antiddos_end_time';
			}
		}
		if(!$error){
			if(!money_act($app_uid,(0-$price[$days]),$task."|$site[domain]")){
				$error = true;
				$sysmsg[] = '账户余额不足，请充值后再操作。';
			}
		}
		if(!$error){
			// $ins = array(
				// 'addon' => $task,
				// 'userid' => $app_uid,
				// 'site_id' => $site_id,
				// 'in_time' => $timestamp,
				// 'end_time' => $timestamp+86400*$days,
			// );
			// $db->query_unbuffered("insert into {$tpf}addon set ".$db->sql_array($ins).";");
			
			if($myinfo[$ziduan]>$timestamp){
				$db->query_unbuffered("UPDATE {$tpf}users set $ziduan=$ziduan+86400*$days WHERE userid='$app_uid';");
			}else{
				$db->query_unbuffered("UPDATE {$tpf}users set $ziduan=$timestamp+86400*$days WHERE userid='$app_uid';");
			}
			
			$out['status']='success';
			$sysmsg[] = '购买成功后，请保存设置后才可以生效。';
			$out['msgs']=implode('<br>',$sysmsg);
			$out['script']="$('#$task').prop('checked',true);";
			if($task=='antiddos_pro'){
				$out['script'].="$('#g_anti').prop('checked',true);";
			}
		}else{
			$out['status']='fail';
			$out['msgs']=implode('<br>',$sysmsg);
		}
		echo json_encode($out);
		break;
	case 'wafstatus':
		$url = 'http://172.104.70.82:2345/getwafstatus';
		$password = '9bb51f5b479c03aee05947e3ccce8739';
		$headers = array(
			"password:$password",
		);
		$curl = curl_init($url);
		curl_setopt($curl,CURLOPT_HEADER, 0 );
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl,CURLOPT_TIMEOUT_MS, 30000);
		curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
		$responseText = curl_exec($curl);
		curl_close($curl);
		// echo $responseText;
		$data = json_decode($responseText,true);
		$data = json_decode($data['data'],true);
		// var_dump($data);
		$last_update_time = $timestamp-mktime(date('H'),date('i')-date('i')%10,0,date('m'),date('d'),date('Y'));
		require_once template_echo("$mod/$act",$user_tpl_dir);
		break;
	case 'buy_addon':
		$site_id = (int)$hiconsole_req[3];
		$param = trim($hiconsole_req[4]);
		// $site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		// if(!$site){
			// show_error('无法操作此域名，如有疑问，请联系客户服务');
		// }
		if($param=='waf_pro'){
			require_once template_echo("$mod/$act"."_waf_pro",$user_tpl_dir);
		}else{
			require_once template_echo("$mod/$act"."_antiddos_pro",$user_tpl_dir);
		}
		break;
	case 'stats':
		$site_id = (int)$hiconsole_req[3];
		$host = trim($hiconsole_req[4]);
		$period = $hiconsole_req[5] ? trim($hiconsole_req[5]) : 'today';
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			show_error('无法操作此域名，如有疑问，请联系客户服务');
		}
		if($site['status']!='success'){
			show_error('请修改运营商设置后再访问此页');
		}
		//获取主机记录
		if($site['cdn_type']=='ns'){
			if(!$host){
				$host = $db->result_first("SELECT dns_host FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid' LIMIT 1");
				header('location:/mysites/stats/'.$site_id.'/'.$host);
				exit;
			}else{
				$rs = $db->result_first("SELECT count(*) FROM {$tpf}site_dns WHERE dns_host='$host' and site_id='$site_id' and userid='$app_uid'");
				if(!$rs){
					$host = $db->result_first("SELECT dns_host FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid' LIMIT 1");
					header('location:/mysites/stats/'.$site_id.'/'.$host);
					exit;
				}else{
					$q = $db->query("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid'");
				}
			}
		}elseif($site['cdn_type']=='cn'){
			if(!$host){
				$host = $db->result_first("SELECT dns_host FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid' LIMIT 1");
				header('location:/mysites/stats/'.$site_id.'/'.$host);
				exit;
			}else{
				$rs = $db->result_first("SELECT count(*) FROM {$tpf}site_cname WHERE dns_host='$host' and site_id='$site_id' and userid='$app_uid'");
				if(!$rs){
					$host = $db->result_first("SELECT dns_host FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid' LIMIT 1");
					header('location:/mysites/stats/'.$site_id.'/'.$host);
					exit;
				}else{
					$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid'");
				}
			}
		}
		$records = array();
		while($rs=$db->fetch_array($q)){
			$rs['cdn_type'] = $rs['cdn_type']==1 ? '默认' : '备用';
			if(in_array($rs['dns_type'],array('A','CNAME'))){
				$records[$rs['dns_host']][] = $rs;
			}
		}
		
		$q = $db->query("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid'");
		$domains = array();
		while($rs=$db->fetch_array($q)){
			$domains[] = $rs;
		}
		
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case 'stats_data':
		//请求远程数据
		header("Content-Type:application/javascript");
		$site_id = (int)$hiconsole_req[3];
		$host = trim($hiconsole_req[4]);
		$period = $hiconsole_req[5] ? trim($hiconsole_req[5]) : 'today';
		if(!in_array($period,array('today','yesterday','7days','30days'))){
			$period = 'today';
		}
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			exit("alert('数据错误')");
		}
		
		$d_year = date('Y');
		$d_month = date('m');
		$d_day = date('d');
		//初始化memcached
		$memcache = new Memcache;
		$memcache->connect('localhost', 11211) or die ("Could not connect");
		$day_stats = get_stats($site_id,$host,$d_year,$d_month,$d_day,$period);
		$_5m_data = json_encode($day_stats['5m']);
		echo "
			$('#stat_req').animateNumber({ number: {$day_stats['stat_req']} });
			$('#stat_ip').animateNumber({ number: {$day_stats['stat_ip']} });
			$('#stat_uv').animateNumber({ number: {$day_stats['stat_uv']} });
			$('#stat_data').animateNumber({
							  number: {$day_stats['stat_data']} * 100,

							  numberStep: function(now, tween) {
								var floored_number = Math.floor(now) / 100,
									target = $(tween.elem);

								if (2 > 0) {
								  // force decimal places even if they are 0
								  floored_number = floored_number.toFixed(2);

								  // replace '.' separator with ','
								  // floored_number = floored_number.toString().replace('.', ',');
								}

								target.text(floored_number);
							  }
							});
			$('#stat_data_unit').html('{$day_stats['stat_data_unit']}');
			$('#llqst').empty();
			var pie_1 = echarts.init(document.getElementById('pie1'));
			var pie_2 = echarts.init(document.getElementById('pie2'));

			// 指定图表的配置项和数据
			var option = {
				title : {
					text: '加速流量图',
					subtext: '流量单位为 Mbytes',
					x:'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: function (params, ticket, callback) {
									return params['name']+' : '+(params['value']/1024/1024).toFixed(2)+' M ('+params['percent']+'%)';
								}
				},
				legend: {
					orient: 'vertical',
					left: 'left',
					data: ['回源流量','加速流量']
				},
				series : [
					{
						name: '加速流量图',
						type: 'pie',
						radius : '55%',
						center: ['50%', '60%'],
						data:[
							{value:{$day_stats['stat_bdata']}, name:'回源流量'},
							{value:{$day_stats['stat_hdata']}, name:'加速流量'}
						],
						itemStyle: {
							emphasis: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}
				]
			};

			// 使用刚指定的配置项和数据显示图表。
			pie_1.setOption(option);
			
			// 指定图表的配置项和数据
			var option = {
				title : {
					text: '加速次数图',
					subtext: '单位为 次数',
					x:'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: '{b} : {c} ({d}%)'
				},
				legend: {
					orient: 'vertical',
					left: 'right',
					data: ['回源次数','加速次数']
				},
				series : [
					{
						name: '加速次数图',
						type: 'pie',
						radius : '55%',
						center: ['50%', '60%'],
						data:[
							{value:{$day_stats['stat_breq']}, name:'回源次数'},
							{value:{$day_stats['stat_hreq']}, name:'加速次数'}
						],
						itemStyle: {
							emphasis: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}
				]
			};

			// 使用刚指定的配置项和数据显示图表。
			pie_2.setOption(option);
			
			Morris.Area({
				element: 'llqst',
				data: {$_5m_data},
				gridEnabled: true,
				gridLineColor: '#e7ecf3',
				behaveLikeLine: true,
				xkey: 'timeline',
				ykeys: ['hit_data', 'bypass_data'],
				labels: ['加速流量', '回源流量'],
				lineColors: ['#045d97'],
				pointSize: 0,
				postUnits: ' m',
				pointStrokeColors : ['#045d97'],
				lineWidth: 0,
				resize:false,
				hideHover: 'auto',
				fillOpacity: 0.7,
				parseTime:true,
				dateFormat:function (x) { return new Date(x).Format('yyyy-MM-dd hh:mm') },
			});";
		break;
	case 'controll':
		$site_id = (int)$hiconsole_req[3];
		$host = trim($hiconsole_req[4]);
		$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid' and id='$site_id'");
		if(!$site){
			show_error('无法操作此域名，如有疑问，请联系客户服务');
		}
		if($site['status']!='success'){
			show_error('请修改运营商设置后再访问此页');
		}
		//获取主机记录
		if($site['cdn_type']=='ns'){
			if(!$host){
				$host = $db->result_first("SELECT dns_host FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid' and cdn_status='on' LIMIT 1");
				if(!$host){
					show_error('该域名下没有启用加速的子域名');
				}else{
					header('location:/mysites/controll/'.$site_id.'/'.$host);
					exit;
				}
			}else{
				$q = $db->query("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and userid='$app_uid'");
			}
		}elseif($site['cdn_type']=='cn'){
			if(!$host){
				$host = $db->result_first("SELECT dns_host FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid' and cdn_status='on' LIMIT 1");
				if(!$host){
					show_error('该域名下没有启用加速的子域名');
				}else{
					header('location:/mysites/controll/'.$site_id.'/'.$host);
					exit;
				}
			}else{
				$q = $db->query("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and userid='$app_uid'");
			}
		}
		$records = array();
		while($rs=$db->fetch_array($q)){
			$rs['cdn_type'] = $rs['cdn_type']==1 ? '默认' : '备用';
			if(in_array($rs['dns_type'],array('A','CNAME'))){
				$rs['cdn_type'] = $rs['cdn_status']=='off' ? 'down':$rs['cdn_type'];
				$records[$rs['dns_host']][] = $rs;
			}
		}
		$host_settings = get_host_settings($site_id,$host);
		//密钥库
		$q = $db->query("SELECT * FROM {$tpf}keychains WHERE userid='$app_uid'");
		$certs = array();
		while($rs=$db->fetch_array($q)){
			$certs[] = $rs;
		}
		//节点库
		$q = $db->query("SELECT * FROM {$tpf}nodes");
		$nodes[0] = array();
		$nodes[1] = array();
		$nodes[2] = array();
		while($rs=$db->fetch_array($q)){
			$nodes[$rs['area']][] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	default:
		$act = 'default';
		// $q = $db->query("SELECT site_id FROM {$tpf}addon WHERE addon='antiddos_pro' and userid='$app_uid'");
		// $antiddos_sites = array();
		// while($rs=$db->fetch_array($q)){
			// $antiddos_sites[] = $rs['site_id'];
		// }
		
		// $q = $db->query("SELECT site_id FROM {$tpf}addon WHERE addon='waf_pro' and userid='$app_uid'");
		// $waf_sites = array();
		// while($rs=$db->fetch_array($q)){
			// $waf_sites[] = $rs['site_id'];
		// }
		
		$q = $db->query("SELECT * FROM {$tpf}mysites WHERE userid='$app_uid'");
		$mysites = array();
		while($rs=$db->fetch_array($q)){
			// $rs['status'] = $site_status[$rs['status']];
			$rs['antiddos_switch'] = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_settings WHERE site_id='{$rs['id']}' and antiddos_pro=1");
			if($rs['antiddos_switch']){
				$antiddos_sites[] = $rs['id'];
			}
			$rs['waf_switch'] = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_settings WHERE site_id='{$rs['id']}' and waf_pro=1");
			if($rs['waf_switch']){
				$waf_sites[] = $rs['id'];
			}
			$mysites[] = $rs;
		}
		
		require_once template_echo("frame",$user_tpl_dir);
		break;
}
function get_host_settings($site_id,$host){
	global $db,$tpf;
	$rs = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='$host' and site_id='$site_id'");
	if(!$rs){
		$rs = array(
			'site_id'=>$site_id,
			'host'=>$host,
			'source_protocol'=>'http',
			'https_switch'=>0,
			'https_only'=>0,
			'http2_switch'=>0,
			'keychains'=>0,
			'browser_cache_switch'=>0,
			'browser_cache_time'=>3600,
			'browser_cache_unit'=>0,
			'statics_cache_switch'=>0,
			'statics_cache_time'=>3600,
			'statics_cache_unit'=>0,
			'html_cache_switch'=>0,
			'html_cache_time'=>3600,
			'html_cache_unit'=>0,
			'index_cache_switch'=>0,
			'index_cache_time'=>3600,
			'index_cache_unit'=>0,
			'static_cachefly'=>0,
			'static_compress_gzip'=>0,
			'static_compress_brotli'=>0,
			'waf_pro'=>0,
			'anti_cc'=>1,
			'antiddos_pro'=>0,
			'nodes'=>0,
			'pagespeed_js'=>0,
			'pagespeed_css'=>0,
			'pagespeed_image'=>0,
			'pagespeed_mode'=>0,
			'pagespeed_compress'=>0,
			'pagespeed_diy'=>0,
		);
	}
	return $rs;
}
function save_record($site_id,$record_id,$dns_type,$dns_host,$dns_line,$dns_value,$dns_ttl,$dns_mx,$cdn_status){
	global $db,$tpf,$_dns_line,$_dns_ttl,$_dns_type,$app_uid,$myinfo;
	if(!in_array($dns_type,$_dns_type)){
		$error = true;
		$msg[] = '记录类型错误';
	}
	if(!$dns_host){
		$error = true;
		$msg[] = '主机名记录错误';
	}
	if(!in_array($dns_line,$_dns_line)){
		$error = true;
		$msg[] = '线路记录错误';
	}
	if(!$dns_value){
		$error = true;
		$msg[] = '记录值错误';
	}elseif(!$error){
		switch($dns_type){
			case'A':
				if(!filter_var($dns_value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
					$error = true;
					$msg[] = '输入的不是合法的IP';
				}
				break;
			case'CNAME':
				// if(!$onlineipmatches){
					// $error = true;
					// $msg[] = '记录的值不正确';
				// }
				if(substr($dns_value,-1,1)!='.'){
					$dns_value .= '.';
				}
				break;
			case'MX':
				if(!$dns_mx){
					$error = true;
					$msg[] = 'MX优先级未设置';
				}
				break;
		}
	}
	if(!in_array($dns_ttl,$_dns_ttl)){
		$error = true;
		$msg[] = 'TTL错误';
	}else{
		switch($dns_ttl){
			case'5 分钟':
				$dns_ttl = 300;
				break;
			case'2 小时':
				$dns_ttl = 7200;
				break;
			case'1 天':
			default:
				$dns_ttl = 86400;
				break;
		}
	}
	if($cdn_status=='on'){
		$cdn_status = 'on';
	}else{
		$cdn_status = 'off';
	}
				// echo $cdn_status;
				// echo 'asdasd';
	if($dns_type!='MX'){
		$dns_mx = null;
	}
	$site = $db->fetch_one_array("SELECT * FROM {$tpf}mysites WHERE id='$site_id' and userid='$app_uid'");
	if(!$site){
		$error = true;
		$msg[] = '该站点不属于你，无法操作';
	}
	if($myinfo['wealth']<0){
		$error = true;
		$msg[] = '您的帐号已欠费，请充值后再试。';
	}
	if($site['cdn_type']=='ns'){
		if($record_id){
			$record = $db->fetch_one_array("SELECT * FROM {$tpf}site_dns WHERE site_id='$site_id' and id='$record_id' and userid='$app_uid'");
			if($record['admin_ban'] && $cdn_status=='on'){
				$cdn_status = 'off';
				$error = true;
				$msg[] = '管理员禁止了您的CDN接入，如有疑问请联系客服。';
			}
			if(!$record){
				$error = true;
				$msg[] = '该记录不属于你，无法操作';
			}
		}	
		// if($record['dns_type']==$dns_type && $record['dns_host']==$dns_host && $record['dns_line']==$dns_line && $record['dns_value']==$dns_value && $record['dns_ttl']==$dns_ttl && $record['dns_mx']==$dns_mx && $record['cdn_status']==$cdn_status){
			// $out['status'] = 'success';
			// return $out;
		// }
		if(!$error){
			if($dns_line!='默认'){
				$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_dns WHERE site_id='$site_id' and dns_line='默认' and dns_host='$dns_host' and userid='$app_uid'");
				if($record['dns_line']=='默认' && $dns_line!='默认'){
					$num--;
				}
				if($num<1){
					$error = true;
					$msg[] = '你需要至少有一条默认记录。';
				}
			}
			$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_dns WHERE site_id='$site_id' and dns_line='$dns_line' and dns_host='$dns_host' and dns_value='$dns_value' and userid='$app_uid'");
			if($record['dns_value']!=$dns_value && $num){
				$error = true;
				$msg[] = '记录冲突';
			}
		}
		if($dns_type=='A'){
			$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_dns WHERE site_id='$site_id' and dns_host='$dns_host' and dns_type='CNAME' and userid='$app_uid'");
			if($num){
				$error = true;
				$msg[] = 'CNAME 和 A 记录只能存在一个';
			}
		}elseif($dns_type=='CNAME'){
			$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_dns WHERE site_id='$site_id' and dns_host='$dns_host' and dns_type='A' and userid='$app_uid'");
			if($num){
				$error = true;
				$msg[] = 'CNAME 和 A 记录只能存在一个';
			}
		}
		if(!$error){
			$db->query_unbuffered("UPDATE {$tpf}mysites SET serials=serials+1 WHERE id='$site_id' and userid='$app_uid'");
			$newserial = $site['serials']+1;
			$db->query_unbuffered("UPDATE {$tpf}dns_records SET serial={$newserial} WHERE zone='{$site['domain']}'");
			$user_ns = explode(',',$site['ns_data']);
			if($record_id){
				//更新节点配置
				$ori_sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='$dns_host' and site_id='$site_id'");
				
				//更新dns记录
				$ins = array(
					'host'=>$dns_host,
					'type'=>$dns_type,
					'data'=>$dns_value,
					'ttl'=>$dns_ttl,
					'mx_priority'=>$dns_mx,
					'view'=>get_dnsview($dns_line),
					'serial'=>$newserial,
				);
				$db->query_unbuffered("UPDATE {$tpf}dns_records SET ".$db->sql_array($ins)." WHERE id='{$record['record_id']}'");
				// $cdn_type = $cdn_status
				$ins = array(
					'dns_type'=>$dns_type,
					'dns_host'=>$dns_host,
					'dns_line'=>$dns_line,
					'dns_value'=>$dns_value,
					'dns_ttl'=>$dns_ttl,
					'dns_mx'=>$dns_mx,
					'cdn_status'=>$cdn_status,
				);
				$db->query_unbuffered("UPDATE {$tpf}site_dns SET ".$db->sql_array($ins)."WHERE id='$record_id'");
				if($dns_type=='A' | $dns_type=='CNAME'){
					$db->query_unbuffered("UPDATE {$tpf}site_dns SET cdn_status='$cdn_status' WHERE dns_type='$dns_type' and dns_host='$dns_host' and site_id='$site_id'");
				}
			}else{
				//新建dns记录
				$ins = array(
					'zone'=>$site['domain'],
					'host'=>$dns_host,
					'type'=>$dns_type,
					'data'=>$dns_value,
					'ttl'=>$dns_ttl,
					'mx_priority'=>$dns_mx,
					'view'=>get_dnsview($dns_line),
					'serial'=>$newserial,
					'primary_ns'=>$user_ns[0],
					'second_ns'=>$user_ns[1],
					'site_id'=>$site_id,
				);
				$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
				$dns_record_id = $db->insert_id();
				
				$ins = array(
					'record_id'=>$dns_record_id,
					'userid'=>$app_uid,
					'site_id'=>$site_id,
					'dns_type'=>$dns_type,
					'dns_host'=>$dns_host,
					'dns_line'=>$dns_line,
					'dns_value'=>$dns_value,
					'dns_ttl'=>$dns_ttl,
					'dns_mx'=>$dns_mx,
					'cdn_status'=>$cdn_status,
				);
				$db->query_unbuffered("INSERT INTO {$tpf}site_dns SET ".$db->sql_array($ins));
				$out['id'] = $db->insert_id();
			}
			if(in_array($dns_type,array('A','CNAME'))){
				$ori_sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='$dns_host' and site_id='$site_id'");
				if(!$ori_sets){
					$ins = array(
						'source_protocol'=>'http',
						'https_switch'=>0,
						'https_only'=>0,
						'http2_switch'=>1,
						'keychains'=>0,
						'browser_cache_switch'=>1,
						'statics_cache_switch'=>1,
						'html_cache_switch'=>0,
						'index_cache_switch'=>0,
						'index_cache_time'=>3600,
						'browser_cache_time'=>3600,
						'statics_cache_time'=>3600,
						'html_cache_time'=>3600,
						'browser_cache_unit'=>'s',
						'statics_cache_unit'=>'s',
						'html_cache_unit'=>'s',
						'index_cache_unit'=>'s',
						'static_cachefly'=>0,
						'static_compress_gzip'=>1,
						'static_compress_brotli'=>0,
						'waf_pro'=>0,
						'antiddos_pro'=>0,
						'anti_cc'=>1,
						'node_group'=>'1,2,3,5',
						'pagespeed_js'=>0,
						'pagespeed_css'=>0,
						'pagespeed_image'=>0,
						'pagespeed_mode'=>0,
					);
					$db->query_unbuffered("INSERT INTO {$tpf}site_settings SET host='$dns_host',site_id='$site_id',".$db->sql_array($ins));
				}
				
				if($dns_type=='A' | $dns_type=='CNAME'){
					$db->query_unbuffered("UPDATE {$tpf}site_dns SET cdn_status='$cdn_status' WHERE dns_type='$dns_type' and dns_host='$dns_host' and site_id='$site_id'");
				}
				if($cdn_status=='on'){
					node_tasks($site_id,$dns_host);
				}else{
					do_bypass($site_id,$dns_host);
				}
			}
			updatedns($site['domain']);
			$out['status'] = 'success';
		}else{
			$out['status'] = 'fail';
			$out['msg'] = implode('<br>',$msg);
		}
	}else{
		//CNAME模式
		if($record_id){
			$record = $db->fetch_one_array("SELECT * FROM {$tpf}site_cname WHERE site_id='$site_id' and id='$record_id' and userid='$app_uid'");
			if(!$record){
				$error = true;
				$msg[] = '该记录不属于你，无法操作';
			}else{
				if($record['admin_ban'] && $cdn_status=='on'){
					$cdn_status = 'off';
					$error = true;
					$msg[] = '管理员禁止了您的CDN接入，如有疑问请联系客服。';
				}
			}
		}else{
			$rs = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_host='$dns_host' and dns_value='$dns_value' and userid='$app_uid'");
			if($rs){
				$error = true;
				$msg[] = '记录已存在，无法操作';
			}
		}
		// if($record['dns_type']==$dns_type && $record['dns_host']==$dns_host && $record['dns_line']==$dns_line && $record['dns_value']==$dns_value && $record['dns_ttl']==$dns_ttl && $record['cdn_status']==$cdn_status){
			// $out['status'] = 'success';
			// return $out;
		// }
		if($dns_type=='A'){
			$rs = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_host='$dns_host' and dns_type='CNAME' and userid='$app_uid'");
			if($rs){
				$error = true;
				$msg[] = '无法增加，子域名记录冲突';
			}
		}elseif($dns_type=='CNAME'){
			$rs = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_host='$dns_host' and dns_type='A' and userid='$app_uid'");
			if($rs){
				$error = true;
				$msg[] = '无法增加，子域名记录冲突';
			}
		}
		$rs = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_host='$dns_host' and dns_line='$dns_line' and userid='$app_uid' and id!='{$record['id']}'");
		if($rs>=3){
			$error = true;
			$msg[] = '负载均衡超过限制';
		}	
		if(!$error){
			if($dns_line!='默认'){
				$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_line='默认' and dns_host='$dns_host' and userid='$app_uid'");
				if($record['dns_line']=='默认' && $dns_line!='默认'){
					$num--;
				}
				if($num<1){
					$error = true;
					$msg[] = '你需要至少有一条默认记录。';
				}
			}
			$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_line='$dns_line' and dns_host='$dns_host' and dns_value='$dns_value' and userid='$app_uid'");
			if($record['dns_value']!=$dns_value && $num){
				$error = true;
				$msg[] = '记录冲突';
			}
		}
		if($dns_type=='A'){
			$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_host='$dns_host' and dns_type='CNAME' and userid='$app_uid'");
			if($num){
				$error = true;
				$msg[] = 'CNAME 和 A 记录只能存在一个';
			}
		}elseif($dns_type=='CNAME'){
			$num = $db->result_first("SELECT COUNT(*) FROM {$tpf}site_cname WHERE site_id='$site_id' and dns_host='$dns_host' and dns_type='A' and userid='$app_uid'");
			if($num){
				$error = true;
				$msg[] = 'CNAME 和 A 记录只能存在一个';
			}
		}
		if(!$error){
			if($record_id){
				$ins = array(
					'zone'=>'fbicdn.com',
					'host'=>$dns_host.'.'.$site['domain'],
					'type'=>$dns_type,
					'data'=>$dns_value,
					'ttl'=>10,
					'view'=>'any',
					'serial'=>0,
				);
				$db->query_unbuffered("UPDATE {$tpf}dns_records SET ".$db->sql_array($ins)." WHERE id='{$record['record_id']}'");
				
				$ins = array(
					'dns_type'=>$dns_type,
					'dns_host'=>$dns_host,
					'dns_line'=>$dns_line,
					'dns_value'=>$dns_value,
					'dns_ttl'=>$dns_ttl,
					'cdn_status'=>$cdn_status,
				);
				$db->query_unbuffered("UPDATE {$tpf}site_cname SET ".$db->sql_array($ins)."WHERE id='$record_id'");
			}else{
				
				//CNAME模式：1.先添加记录走源
				//固定的dns ciadns.com
				$dns_host2 = $dns_host=='@' ? $site['domain'] : $dns_host.'.'.$site['domain'];
				$ins = array(
					'zone'=>'fbicdn.com',
					'host'=>$dns_host2,
					'type'=>$dns_type,
					'data'=>$dns_value,
					'ttl'=>10,
					'view'=>'any',
					'serial'=>0,
					'primary_ns'=>'ns1.fbidns.com.',
					'second_ns'=>'ns2.fbidns.com.',
					'site_id'=>$site['id'],
				);
				$db->query_unbuffered("INSERT INTO {$tpf}dns_records SET ".$db->sql_array($ins));
				$dns_record_id = $db->insert_id();
				
				$ins = array(
					'record_id'=>$dns_record_id,
					'userid'=>$app_uid,
					'site_id'=>$site_id,
					'dns_type'=>$dns_type,
					'dns_host'=>$dns_host,
					'dns_line'=>$dns_line,
					'dns_value'=>$dns_value,
					'dns_ttl'=>$dns_ttl,
					'cdn_status'=>$cdn_status,
				);
				$db->query_unbuffered("INSERT INTO {$tpf}site_cname SET ".$db->sql_array($ins));
				$out['id'] = $db->insert_id();
				$out['cname'] = $dns_host2.'.'.$site['ns_data'];
				
			}
			
			//写入CDN配置表
			if(in_array($dns_type,array('A','CNAME'))){
				$ori_sets = $db->fetch_one_array("SELECT * FROM {$tpf}site_settings WHERE host='$dns_host' and site_id='$site_id'");
				if(!$ori_sets){
					$ins = array(
						'source_protocol'=>'http',
						'https_switch'=>0,
						'https_only'=>0,
						'http2_switch'=>1,
						'keychains'=>0,
						'browser_cache_switch'=>1,
						'statics_cache_switch'=>1,
						'html_cache_switch'=>0,
						'index_cache_switch'=>0,
						'index_cache_time'=>3600,
						'browser_cache_time'=>3600,
						'statics_cache_time'=>3600,
						'html_cache_time'=>3600,
						'browser_cache_unit'=>'s',
						'statics_cache_unit'=>'s',
						'html_cache_unit'=>'s',
						'index_cache_unit'=>'s',
						'static_cachefly'=>0,
						'static_compress_gzip'=>1,
						'static_compress_brotli'=>0,
						'waf_pro'=>0,
						'antiddos_pro'=>0,
						'anti_cc'=>1,
						'node_group'=>'1,2,3,5',
						'pagespeed_js'=>0,
						'pagespeed_css'=>0,
						'pagespeed_image'=>0,
						'pagespeed_mode'=>0,
					);
					$db->query_unbuffered("INSERT INTO {$tpf}site_settings SET host='$dns_host',site_id='$site_id',".$db->sql_array($ins));
				}
				if($cdn_status=='on'){
					node_tasks($site_id,$dns_host);
				}else{
					do_bypass($site_id,$dns_host);
				}
			}
			updatedns('fbicdn.com');
			$out['status'] = 'success';
		}else{
			$out['status'] = 'fail';
			$out['msg'] = implode('<br>',$msg);
		}
	}
	return $out;
}
?>