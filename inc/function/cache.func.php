<?php 
/**
#	Project: WingMan - PUA Tranning APP
#
#	$Id: inc/function/cache.func.php 2016-1-6 08:57:35 Linkec $
#
#	Copyright (C) 2004-2016 Doopaa.Inc. All Rights Reserved.
#
*/
if(!defined('IN_APP')) {
	exit('[XDDrive] Access Denied');
}
// 系统配置表缓存
function settings_cache($arr=0){
	global $db,$tpf,$configs,$settings;
	if(is_array($arr)){
		foreach($arr as $k => $v){
			$v = str_replace(array("'",'\\'),'',$v);
			$sqls .= "('$k','".$db->escape(trim($v))."'),";
		}
		$sqls = substr($sqls,0,-1);
		$db->query("replace into `{$configs['dbname']}`.{$tpf}settings (vars,value) values $sqls;");
	}
	$q = $db->query("select * from `{$configs['dbname']}`.{$tpf}settings order by vars ");
	while($rs = $db->fetch_array($q)){
		$str_c .= "\t'".$rs['vars']."' => '".$rs['value']."',".LF;
	}
	$db->free($q);
	unset($rs);

	$str = "<?php".LF.LF;
	$str .= "// This is auto-generated file. Do NOT modify.".LF;
	$str .= "// Cache Time: ".date("Y-m-d H:i:s").LF.LF;
	$str .= "\$settings = array(".LF;
	$str .= $str_c;
	$str .= ");".LF.LF;
	$str .= "?>".LF;

	write_file(APP_ROOT."./sys/settings.inc.php",$str);

}
// 模板表缓存
function tpl_cache(){
	global $db,$tpf;
	$q = $db->query("select * from {$tpf}templates order by tpl_name");
	$str_c = '';
	while($rs = $db->fetch_array($q)){
		$str_c .= "\t'{$rs[tpl_name]}'=>array(".LF;
		$str_c .= "\t\t'tpl_name'=>'{$rs[tpl_name]}',".LF;
		$str_c .= "\t\t'actived'=>'{$rs[actived]}',".LF;
		$str_c .= "\t\t'tpl_type'=>'{$rs[tpl_type]}',".LF;
		$str_c .= "\t),".LF;
	}
	$db->free($q);
	unset($rs);

	$str = "<?php".LF.LF;
	$str .= "// This is auto-generated file. Do NOT modify.".LF;
	$str .= "// Cache Time: ".date("Y-m-d H:i:s").LF.LF;
	$str .= "\$tpl_settings = array(".LF;
	$str .= $str_c;
	$str .= ");".LF.LF;
	$str .= "?>".LF;

	write_file(APP_ROOT."./sys/tpl_settings.inc.php",$str);
}
// 用户组设置表缓存
function group_settings_cache(){
	global $db,$tpf;

	$q = $db->query("select * from {$tpf}groups order by gid");
	while($rs = $db->fetch_array($q)){
		$str_c .= "\t'".$rs['gid']."' => array(".LF;
		$str_c .= "\t\t'max_messages' => '".$rs['max_messages']."',".LF;
		$str_c .= "\t\t'max_flow_down' => '".$rs['max_flow_down']."',".LF;
		$str_c .= "\t\t'max_flow_view' => '".$rs['max_flow_view']."',".LF;
		$str_c .= "\t\t'max_storage' => '".$rs['max_storage']."',".LF;
		$str_c .= "\t\t'max_filesize' => '".$rs['max_filesize']."',".LF;
		$str_c .= "\t\t'group_file_types' => '".$rs['group_file_types']."',".LF;
		$str_c .= "\t\t'max_folders' => '".$rs['max_folders']."',".LF;
		$str_c .= "\t\t'max_files' => '".$rs['max_files']."',".LF;
		$str_c .= "\t\t'can_share' => '".$rs['can_share']."',".LF;
		$str_c .= "\t\t'secs_loading' => '".$rs['secs_loading']."',".LF;
		$str_c .= "\t\t'server_ids' => '".$rs['server_ids']."',".LF;
		$str_c .= "\t),".LF.LF;

	}
	$db->free($q);
	unset($rs);

	$str = "<?php".LF.LF;
	$str .= "// This is auto-generated file. Do NOT modify.".LF;
	$str .= "// Cache Time: ".date("Y-m-d H:i:s").LF.LF;
	$str .= "\$group_settings = array(".LF;
	$str .= $str_c;
	$str .= ");".LF.LF;
	$str .= "?>".LF;

	write_file(APP_ROOT."./sys/group_settings.inc.php",$str);
}
?>