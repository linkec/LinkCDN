<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die ("Could not connect");

$d_year = date('Y');
$d_month = date('m');
$d_day = date('d');
$d_hour = date('H');

$yesterday_time = mktime(0,0,0,$d_month,$d_day-1,$d_year);
$y_year = date('Y',$yesterday_time);
$y_month = date('m',$yesterday_time);
$y_day = date('d',$yesterday_time);

$q = $db->query("SELECT id FROM {$tpf}mysites WHERE userid='$app_uid' and status='success'");
while($rs=$db->fetch_array($q)){
	$ids[] = $rs['id'];
}
if($ids){
	$site_ids = implode(',',$ids);
	$key = 'total_'.$site_ids.'_'.$d_year.'_'.$d_month.'_'.$d_day;
	// echo $key;
	$data = $memcache->get($key);
	if(!$data){
		$data = callapi("http://158.69.54.69/api.php?a=total_stats&site_ids={$site_ids}&d_year={$d_year}&d_month={$d_month}&d_day={$d_day}");
		// var_dump($data);
		$memcache->set($key,$data,MEMCACHE_COMPRESSED,300);
	}
}
// var_dump($data);
$today_paid = number_format($db->result_first("SELECT sum(fee_1)+sum(fee_2)+sum(fee_3)+sum(fee_4)+sum(fee_5) FROM {$tpf}payment WHERE userid='$app_uid' and year='$d_year' and month='$d_month' and day='$d_day'"),4);
$yesterday_paid = number_format($db->result_first("SELECT sum(fee_1)+sum(fee_2)+sum(fee_3)+sum(fee_4)+sum(fee_5) FROM {$tpf}payment WHERE userid='$app_uid' and year='$y_year' and month='$y_month' and day='$y_day'"),4);

if($yesterday_paid==0){
	$approx_days = '-';
	// $pay_per = '...';
}else{
	$approx_days = round($myinfo['wealth']/$yesterday_paid);
	// if($yesterday_paid>$today_paid){
		// $yesterday_paid
	// }
}
$today_data = get_size($data['data']['bdata']+$data['data']['hdata']);
$today_req = (int)($data['data']['breq']+$data['data']['hreq']);
$time_start = mktime(0,0,0,$d_month,$d_day,$d_year);
$time_end = $time_start+86400;
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
$_5m_data = json_encode($day_stats['5m']);
require_once template_echo("frame",$user_tpl_dir);
?>