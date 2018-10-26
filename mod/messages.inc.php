<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
switch($act){
	case'maintain':
		$q = $db->query("SELECT * FROM {$tpf}announce WHERE type='maintain' ORDER BY id DESC");
		$announces = array();
		while($rs = $db->fetch_array($q)){
			$rs['in_time'] = date('Y-m-d h:i:s',$rs['in_time']);
			$announces[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'announce':
		$q = $db->query("SELECT * FROM {$tpf}announce WHERE type!='maintain' ORDER BY id DESC");
		$announces = array();
		while($rs = $db->fetch_array($q)){
			$rs['in_time'] = date('Y-m-d h:i:s',$rs['in_time']);
			$announces[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'home':
		$q = $db->query("SELECT * FROM {$tpf}notification WHERE userid='$app_uid' ORDER BY id DESC");
		$notice = array();
		while($rs = $db->fetch_array($q)){
			$rs['in_time'] = date('Y-m-d h:i:s',$rs['in_time']);
			$notice[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'view':
		$id = (int)$hiconsole_req[3];
		$article = $db->fetch_one_array("SELECT * FROM {$tpf}notification WHERE userid='$app_uid' and id='$id'");
		if(!$article['is_read']){
			$db->query_unbuffered("UPDATE {$tpf}notification SET is_read=1 WHERE id='$id'");
		}
		$article['in_time'] = date('Y-m-d H:i:s',$article['in_time']);
		require_once template_echo("frame",$user_tpl_dir);
		break;
}
?>