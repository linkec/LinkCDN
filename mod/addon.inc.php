<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
switch($act){
	case'antiddos_pro':
		// $antiddos = $db->fetch_one_array("SELECT * FROM {$tpf}addon WHERE userid='$app_uid' and addon='antiddos_pro' and end_time>$timestamp ORDER BY end_time DESC");
		// if($antiddos){
			// $antiddos['end_time'] = date('Y-m-d',$antiddos['end_time']);
		// }
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'waf_pro':
		// $waf = $db->fetch_one_array("SELECT * FROM {$tpf}addon WHERE userid='$app_uid' and addon='waf_pro' and end_time>$timestamp ORDER BY end_time DESC");
		// if($waf){
			// $waf['end_time'] = date('Y-m-d',$waf['end_time']);
		// }
		require_once template_echo("frame",$user_tpl_dir);
		break;
}
?>