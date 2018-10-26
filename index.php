<?php
include("inc/commons.inc.php");
// echo $app_uid;
// exit;
if(in_array($mod,array())){
	if(file_exists(APP_ROOT."mod/".$mod.".inc.php")){
		require_once APP_ROOT."mod/".$mod.".inc.php";
	}else{
		hi_err('定义的模块未找到');
	}
}

$allow_mods = array('manage','addon','default','account','mysites','workorders','orders','messages','settings','FFGKBMQ3AJEQHA3S','newapi');
if($app_uid==1 || $app_uid==2 || $_SESSION['adminuid']==1){
	array_push($allow_mods,'admin');
}
if(!in_array($mod,$allow_mods)){
	header('location:/');
}else{
	if(!$app_uid && !in_array($mod,array('account','default','FFGKBMQ3AJEQHA3S','newapi'))){
		header('location:/account/signin');
	}elseif(file_exists(APP_ROOT."mod/".$mod.".inc.php")){
		require_once APP_ROOT."mod/".$mod.".inc.php";
		exit;
	}else{
		hi_err('定义的模块未找到');
	}
}
exit;