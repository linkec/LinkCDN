<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">财务相关</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->
<!-- MAIN CONTENT -->
<div id="page-content">
<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title">购买记录</h3>
	</div>

	<!--Data Table-->
	<!--===================================================-->
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>操作</th>
						<th>金额</th>
						<th>时间</th>
					</tr>
				</thead>
				<tbody>
					<!--#foreach($logs as $v){#-->
					<tr>
						<td>{$v['id']}</td>
						<td>{$v['act']}</td>
						<td>{$v['money']}</td>
						<td>{$v['in_time']}</td>
					</tr>
					<!--#}#-->
				</tbody>
			</table>
		</div>
	</div>
	<!--===================================================-->
	<!--End Data Table-->

</div>
</div>
<!-- END MAIN CONTENT -->