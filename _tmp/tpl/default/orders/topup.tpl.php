<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-04-16 13:38:00

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
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
		<h3 class="panel-title">订单记录</h3>
	</div>

	<!--Data Table-->
	<!--===================================================-->
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>订单号</th>
						<th>类型</th>
						<th>金额</th>
						<th>状态</th>
						<th>时间</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($orders as $v){ ?>
					<tr>
						<td><?=$v['id']?></td>
						<td><?=$v['trade_num']?></td>
						<td><?=$v['type']?></td>
						<td><?=$v['money']?></td>
						<td><?=$v['status']?></td>
						<td><?=$v['in_time']?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<!--===================================================-->
	<!--End Data Table-->

</div>
</div>
<!-- END MAIN CONTENT -->