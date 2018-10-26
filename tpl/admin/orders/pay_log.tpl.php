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
		<h3 class="panel-title">消费记录</h3>
	</div>

	<!--Data Table-->
	<!--===================================================-->
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>时间</th>
						<th>站点</th>
						<th>基础消费</th>
						<th>极速消费</th>
						<th>旗舰消费</th>
						<th>高防消费</th>
						<th>基本防护消费</th>
					</tr>
				</thead>
				<tbody>
					<!--#foreach($logs as $v){#-->
					<tr>
						<td>{$v['year']}-{$v['month']}-{$v['day']}</td>
						<td><a class="btn btn-xs btn-primary" href="/orders/pay_log_details/{$v['site_id']}">{$v['domain']}</a></td>
						<td>{$v['fee_1']}</td>
						<td>{$v['fee_2']}</td>
						<td>{$v['fee_3']}</td>
						<td>{$v['fee_4']}</td>
						<td>{$v['fee_5']}</td>
					</tr>
					<!--#}#-->
				</tbody>
			</table>
			{$multipage}
		</div>
	</div>
	<!--===================================================-->
	<!--End Data Table-->

</div>
</div>
<!-- END MAIN CONTENT -->