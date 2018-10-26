<?php 
!defined('IN_APP') && exit('[XDDrive] Access Denied!'); 
switch($act){
	case'list':
		switch($hiconsole_req[3]){
			case'waiting':
				$q = $db->query("SELECT * FROM {$tpf}workorders WHERE userid='$app_uid' and status='waiting'");
				break;
			case'closed':
				$q = $db->query("SELECT * FROM {$tpf}workorders WHERE userid='$app_uid' and status='closed'");
				break;
			default:
				$q = $db->query("SELECT * FROM {$tpf}workorders WHERE userid='$app_uid' and status='pendding'");
				break;
		}
		$count['pendding'] = $db->result_first("SELECT COUNT(*) AS pendding FROM {$tpf}workorders WHERE userid='$app_uid' and status='pendding'");
		$count['waiting'] = $db->result_first("SELECT COUNT(*) AS waiting FROM {$tpf}workorders WHERE userid='$app_uid' and status='waiting'");
		$count['closed'] = $db->result_first("SELECT COUNT(*) AS closed FROM {$tpf}workorders WHERE userid='$app_uid' and status='closed'");
		// var_dump($count);
		$workorders = array();
		while($rs = $db->fetch_array($q)){
			$rs['in_time'] = date('Y-m-d',$rs['in_time']);
			$rs['content'] = strip_tags(htmlspecialchars_decode(base64_decode(file_get_contents(APP_ROOT.$rs['content']))));
			$workorders[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'case':
		$wo_id = (int)$hiconsole_req[3];
		$workorder = $db->fetch_one_array("SELECT * FROM {$tpf}workorders WHERE id='$wo_id' and userid='$app_uid'");
		if(!$workorder){
			show_error('无法进行此操作，请联系客户服务。');
		}
		$content = stripslashes(gpc('content','P',''));
		if($task=='add' && $content){
			
			$wo_dir = '/sys/wo_content/'.date('Y/m/d/');
			
			make_dir(APP_ROOT.$wo_dir);
			
			$wo_file = $wo_dir.md5($content);
			
			@file_put_contents(APP_ROOT.$wo_file,base64_encode($content));
			
			$db->query_unbuffered("INSERT INTO {$tpf}workorders_reply SET wo_id='$wo_id',content='$wo_file',userid='$app_uid',in_time='$timestamp'");
			$db->query_unbuffered("UPDATE {$tpf}workorders SET status='pendding' WHERE id='$wo_id'");
		}
		$workorder['in_time'] = date('Y-m-d H:i:s',$workorder['in_time']);
		if($workorder['status']=='pendding'){
			$workorder['status']='<span class="label label-danger">处理中</span>';
		}elseif($workorder['status']=='waiting'){
			$workorder['status']='<span class="label label-warning">待回复</span>';
		}elseif($workorder['status']=='closed'){
			$workorder['status']='<span class="label label-default">已关闭</span>';
		}
		$workorder['content'] = htmlspecialchars_decode(base64_decode(file_get_contents(APP_ROOT.$workorder['content'])));
		
		$q = $db->query("SELECT * FROM {$tpf}workorders_reply WHERE wo_id='$wo_id' ORDER BY id ASC");
		$reply = array();
		while($rs = $db->fetch_array($q)){
			$rs['in_time'] = date('Y-m-d H:i:s',$rs['in_time']);
			$rs['content'] = htmlspecialchars_decode(base64_decode(file_get_contents(APP_ROOT.$rs['content'])));
			$reply[] = $rs;
		}
		require_once template_echo("frame",$user_tpl_dir);
		break;
	case'new':
		$content = stripslashes(gpc('content','P',''));
		$subject = trim(gpc('subject','P',''));
		if($task=='add' && $content && $subject){
			
			$wo_dir = '/sys/wo_content/'.date('Y/m/d/');
			make_dir(APP_ROOT.$wo_dir);
			$wo_file = $wo_dir.md5($content);
			@file_put_contents(APP_ROOT.$wo_file,base64_encode($content));
			
			$db->query_unbuffered("INSERT INTO {$tpf}workorders SET subject='$subject',content='$wo_file',userid='$app_uid',in_time='$timestamp'");
			
			header('location:/workorders/case/'.$db->insert_id());
		}else{
			require_once template_echo("frame",$user_tpl_dir);
		}
		break;
	case'close_wo':
		$wo_id = (int)$hiconsole_req[3];
		$workorder = $db->fetch_one_array("SELECT * FROM {$tpf}workorders WHERE id='$wo_id' and userid='$app_uid'");
		if(!$workorder){
			$out['status']='error';
			$out['msgs']='该工单不属于你';
		}else{
			$db->query_unbuffered("UPDATE {$tpf}workorders SET status='closed' WHERE id='$wo_id' and userid='$app_uid'");
			$out['status']='success';
			$out['msgs']='该工单已关闭';
		}
		echo json_encode($out);
		break;
}
?>