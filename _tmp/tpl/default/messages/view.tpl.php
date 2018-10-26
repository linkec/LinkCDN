<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-04-18 15:16:05

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">消息中心</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->
<div id="page-content">
	<div class="panel">
		<div class="panel-heading">
			<h2 class="panel-title text-center"><?=$article['subject']?></h2>
		</div>
		<div class="text-center"><?=$article['in_time']?></div>
		<div class="panel-body">
			<p><?=$article['content']?></p>
		</div>
	</div>
</div>