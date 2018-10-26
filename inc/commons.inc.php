<?php
/*
#	Project: XDDrive Private - File Manage System
#
#	$Id: inc/commons.inc.php 2016-07-07 10:43:28 Linkec $
#
#	Copyright (C) 2004-2016 Suike-Tech.Ltd. All Rights Reserved.
#
*/
//页面计时函数
function get_runtime($start,$end='') {
	static $_ps_time = array();
	if(!empty($end)) {
		if(!isset($_ps_time[$end])) {
			$mtime = explode(' ', microtime());
		}
		return number_format(($mtime[1] + $mtime[0] - $_ps_time[$start]), 6);
	}else{
		$mtime = explode(' ', microtime());
		$_ps_time[$start] = $mtime[1] + $mtime[0];
	}
}
//页面计时开始
get_runtime('start');
//初始化Session
session_start();
//初始化变量
$C = $settings = $sysmsg = array();
//定义转行符
if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	define('LF',"\r\n");
}else{
	define('LF',"\n");
}
//定义系统常用变量
define('APP_ROOT', substr(dirname(__FILE__), 0, -3));
define('STATIC_PATH', '');
define('IN_APP',TRUE);
define('SERVER_NAME',$_SERVER['SERVER_NAME']);
//定义时区
if(function_exists('date_default_timezone_set')){
	@date_default_timezone_set('Asia/Shanghai');
}
//初始化时间变量
$timestamp = time();
define('TS',$timestamp);
@set_magic_quotes_runtime(0);
//载入数据库配置文件
$config_file = APP_ROOT.'sys/configs.inc.php';
if(!file_exists($config_file)){
	header("Location: ./errpage/404.html");
	exit;
}else{
	require($config_file);
}
// 数据库表头初始化
$tpf = $configs['tpf'];
//Debug函数初始化
$C['set']['debug'] = $configs['debug'];
define('DEBUG',$C['set']['debug'] ? true : false);
if(DEBUG){
	error_reporting(E_ALL ^ E_NOTICE);
	@ini_set('display_errors', 'On');
}else{
	error_reporting(0);
	@ini_set('display_errors', 'Off');
}
	error_reporting(E_ALL ^ E_NOTICE);
	@ini_set('display_errors', 'On');
//字符编码初始化
$charset = $configs['charset'];
$charset_arr = array('gbk' => 'gbk','utf-8' => 'utf8');
$db_charset = $charset_arr[strtolower($configs['charset'])];
header("Content-Type: text/html; charset=$charset");
// 载入函数库
$arr = array('global','cache');
for ($i=0;$i<count($arr);$i++){
	require(APP_ROOT.'inc/function/'.$arr[$i].'.func.php');
}
$arr = array('core','mysql');
for ($i=0;$i<count($arr);$i++){
	require(APP_ROOT.'inc/class/'.$arr[$i].'.class.php');
}
//获取用户IP
$onlineip = get_ip();

// 初始化核心，数据库，配置缓存
app_core::init_core();
$db = app_core::init_db_connect();
$file = APP_ROOT.'sys/settings.inc.php';
file_exists($file) ? require_once $file : settings_cache();
$file = APP_ROOT.'sys/group_settings.inc.php';
file_exists($file) ? require_once $file : group_settings_cache();

unset($file);
// 初始化用户级缓存配置
$settings[open_cache] = (int)$settings[open_cache];
require(APP_ROOT.'inc/class/cache.class.php');
// 初始化模板系统
$C['gz']['open'] = $settings['gzipcompress'];
app_core::gzcompress_open();
$arr = app_core::init_tpl();
$user_tpl_dir = $arr['user_tpl_dir'];
$user_tpl_dir = 'tpl/default/';
$static_url = '/demo';
$admin_tpl_dir = 'tpl/admin/';
unset($arr);

// 初始化GPC数据
if(!@get_magic_quotes_gpc()){
	$_GET = addslashes_array($_GET);
	$_POST = addslashes_array($_POST);
	$_COOKIE = addslashes_array($_COOKIE);
}
$task = trim(gpc('task','GP',''));

$hiconsole_req = explode('/',str_ireplace($_SERVER["SCRIPT_NAME"],'',$_SERVER["REQUEST_URI"]));
$mod = $hiconsole_req[1] ? $hiconsole_req[1] : 'default';
$act = $hiconsole_req[2] ? $hiconsole_req[2] : 'default';
// phpinfo();
// var_dump($hiconsole_req);
$pg = (int)gpc('pg','G',0);
!$pg &&	$pg = 1;
$perpage = $C['set']['perpage'] ? (int)$C['set']['perpage'] : 20;

$error = false;
$item = trim(gpc('item','GP',''));
$app = trim(gpc('app','GP',''));
$action = trim(gpc('act','GP',''));
$menu = trim(gpc('menu','GP',''));
$p_formhash = trim(gpc('formhash','P',''));

$formhash = formhash();

// 获取用户数据
if($_SESSION['uid']){
	$admin_uid = (int)$_SESSION['admin_uid'];
	$myinfo = get_profile($_SESSION['uid']);
	if($admin_uid && $myinfo['gid']==1){
		$myinfo = get_profile($admin_uid);
		$app_uid = $myinfo['uid'];
		$app_username = $myinfo['username'];
		$app_email = $myinfo['email'];
		$app_gid = $myinfo['gid'];
		$app_group_name = $myinfo['group_name'];
	}else{
		$app_uid = $myinfo['userid'];
		$app_username = $myinfo['username'];
		$app_email = $myinfo['email'];
		$app_gid = $myinfo['gid'];
		$app_group_name = $myinfo['group_name'];
	}
}else{
	$app_uid = 0;
	$app_pwd = '';
}
if($app_uid){
	$has_new_msg = $db->result_first("SELECT COUNT(*) FROM {$tpf}notification WHERE userid='$app_uid' and is_read=0 ");
	$q = $db->query("SELECT * FROM {$tpf}notification WHERE userid='$app_uid' ORDER BY id DESC LIMIT 10");
	$notifications = array();
	while($rs = $db->fetch_array($q)){
		$rs['in_time'] = date('Y-m-d h:i:s',$rs['in_time']);
		$rs['subject'] = $rs['id'].$rs['subject'];
		$notifications[] = $rs;
	}
}
?>