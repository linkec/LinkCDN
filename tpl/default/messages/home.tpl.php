<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">消息中心</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->
<!-- MAIN CONTENT -->
<div id="page-content">
<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title">系统消息</h3>
	</div>

	<!--Data Table-->
	<!--===================================================-->
	<div class="panel-body">
						
		<!--Custom Content-->
		<!--===================================================-->
		<div class="list-group">
			<!--#foreach($notice as $v){#-->
			<a href="/messages/view/{$v['id']}" class="list-group-item">
				<div class="list-group-item-heading{#$v['is_read'] ? '' : ' text-semibold'#}">{$v['type']}：{$v['subject']}<span class="text-xs pull-right">{$v['in_time']}</span></div>
			</a>
			<!--#}#-->
		</div>
		<!--===================================================-->

	</div>
	<!--===================================================-->
	<!--End Data Table-->

</div>
</div>
<!-- END MAIN CONTENT -->