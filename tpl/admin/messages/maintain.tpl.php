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
		<h3 class="panel-title">维护公告</h3>
	</div>

	<!--Data Table-->
	<!--===================================================-->
	<div class="panel-body">
						
		<!--Custom Content-->
		<!--===================================================-->
		<div class="list-group">
			<!--#foreach($announces as $v){#-->
			<a href="#" class="list-group-item">
				<h4 class="list-group-item-heading">{$v['subject']}<span class="text-xs pull-right">{$v['in_time']}</span></h4>
				<p class="list-group-item-text">{$v['intro']}</p>
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