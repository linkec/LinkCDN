<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">Ray WAF Status</h4>
</div>
<div class="modal-body">
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12">
				<table class="table table-hover table-vcenter">
					<thead>
						<tr>
							<th>参数</th>
							<th>数值</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>处理请求</td>
							<td>{$data['requests']}</td>
						</tr>
						<tr>
							<td>攻击次数</td>
							<td>{$data['attacks']}</td>
						</tr>
						<tr>
							<td>拦截次数</td>
							<td>{$data['blocked']}</td>
						</tr>
						<tr>
							<td>可疑操作</td>
							<td>{$data['tnt_errors']}</td>
						</tr>
						<tr>
							<td>处理时间</td>
							<td>{$data['time_detect']}</td>
						</tr>
						<tr>
							<td>上次更新</td>
							<td>{$last_update_time} 秒前</td>
						</tr>
						<tr>
							<td>系统版本</td>
							<td>RayWaf v{$data['db_id']}.{$data['lom_id']}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		关闭
	</button>
</div>
<script>
</script>