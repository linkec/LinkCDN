<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-07-24 21:38:47

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">节点管理</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!-- MAIN CONTENT -->
<div id="page-content">
	
	<div class="panel panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">任务管理</h3>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-hover table-vcenter">
					<thead>
						<tr>
							<th>任务ID</th>
							<th>节点</th>
							<th>任务类型</th>
							<th>数据</th>
							<th>执行次数</th>
							<th>状态</th>
							<th>回调</th>
							<th>时间</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tasks as $v){ ?>
						<tr id="node_<?=$v['task_id']?>">
							<td><?=$v['task_id']?></td>
							<td><?=$v['node_id']?><br><?=$v['node_name']?></td>
							<td><?=$v['task']?></td>
							<td><pre id="data_<?=$v['task_id']?>" style="display:none;"><?=var_dump($v['data'])?></pre><a class="btn btn-xs btn-primary" onclick="seedata(<?=$v['task_id']?>)">查看</a></td>
							<td><?=$v['call_times']?></td>
							<td><?=$v['status']?></td>
							<td><pre id="res_<?=$v['task_id']?>" style="display:none;"><?=var_dump($v['response'])?></pre><a class="btn btn-xs btn-primary" onclick="seeres(<?=$v['task_id']?>)">查看</a></td>
							<td><?=$v['in_time']?></td>
							<td><a class="btn btn-xs btn-primary" onclick="excute_task(<?=$v['task_id']?>)">执行</a></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<div>
				<?=$pages?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END MAIN CONTENT -->
<script>
function seedata(id){
	$('#remoteModal div.modal-content').html('<pre style="height:500px;color:white;background:black;">'+$('#data_'+id).html()+'</pre>')
	$('#remoteModal').modal('show');
}
function seeres(id){
	$('#remoteModal div.modal-content').html('<pre style="height:500px;color:white;background:black;">'+$('#res_'+id).html()+'</pre>')
	$('#remoteModal').modal('show');
}
function excute_task(id){
	$.get('/admin/nodes/excute_task/'+id);
	alert('执行中，请等待片刻...');
}
</script>