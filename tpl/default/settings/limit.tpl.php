<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div id="page-title">
	<h1 class="page-header text-overflow">账户设置</h1>
</div>
<div id="page-content">
<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title">消费限额</h3>
	</div>
	<div class="panel-body">
		<div class="alert alert-primary">
			<strong>提醒：</strong> 当消费额度超出设定限额时，我们提供的加速服务将停止，域名解析将回源；限额周期结束后，加速服务将自动恢复。
		</div>
		<a class="btn btn-primary" onclick="add_limit_rule();">添加规则</a>
		<hr>
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">规则列表</h3>
			</div>
			<!-- Striped Table -->
			<!--===================================================-->
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>主域名</th>
								<th>已用额度</th>
								<th>限定额度</th>
								<th>周期单位</th>
								<th>当前周期</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<!--#foreach($rules as $v){#-->
							<tr id="limit_rule_{$v['id']}">
								<td>{$v['domain']}</td>
								<td><i class="fa fa-cny"></i> {$v['used_fee']}</td>
								<td><i class="fa fa-cny"></i> {$v['fee']}</td>
								<td>{$v['unit']}</td>
								<td>{$v['start_time']} 到 {$v['end_time']}</td>
								<td><a class="btn btn-xs btn-info" onclick="reset_limit_rule({$v['id']});">重置</a> <a class="btn btn-xs btn-primary" onclick="edit_limit_rule({$v['id']});">编辑</a> <a class="btn btn-xs btn-danger" onclick="del_limit_rule({$v['id']});">删除</a></td>
							</tr>
							<!--#}#-->
						</tbody>
					</table>
				</div>
			</div>
			<!--===================================================-->
			<!-- End Striped Table -->

		</div>
	</div>
</div>
</div>
<script>
function del_limit_rule(id){
	bootbox.dialog({
		message: "你确定要删除该规则吗？删除该规则后则不限制额度使用！",
		title: "系统提醒！",
		buttons: {
			default: {
				label: "取消",
				className: "btn-default",
				callback: function() {
				}
			},

			danger: {
				label: "确定！",
				className: "btn-danger",
				callback: function() {
					$.get('/settings/del_limit_rule/'+id,function(data){
						if(data.status=='success'){
							$.niftyNoty({
								type: 'success',
								container: 'floating',
								html: '<strong>规则已经成功删除</strong>',
								closeBtn: false,
								floating: {
									position: "top-right",
									animationIn: "bounceInDown",
									animationOut: "fadeOut"
								},
								focus: true,
								timer: 5000
							});
							$("#limit_rule_"+id).remove();
						}else{
							$.niftyNoty({
								type: 'danger',
								container: 'floating',
								html: '<strong>操作失败</strong> '+data.msgs,
								closeBtn: false,
								floating: {
									position: "top-right",
									animationIn: "bounceInDown",
									animationOut: "fadeOut"
								},
								focus: true,
								timer: 5000
							});
						}
					},'json');
				}
			}
		}
	});
}
function reset_limit_rule(id){
	bootbox.dialog({
		message: "你确定要重置该规则的已用额度和周期吗？",
		title: "系统提醒！",
		buttons: {
			default: {
				label: "取消",
				className: "btn-default",
				callback: function() {
				}
			},

			danger: {
				label: "确定！",
				className: "btn-danger",
				callback: function() {
					$.get('/settings/reset_limit_rule/'+id,function(data){
						if(data.status=='success'){
							$.niftyNoty({
								type: 'success',
								container: 'floating',
								html: '<strong>额度已经成功重置</strong>',
								closeBtn: false,
								floating: {
									position: "top-right",
									animationIn: "bounceInDown",
									animationOut: "fadeOut"
								},
								focus: true,
								timer: 5000
							});
							$("#limit_rule_"+id+" td")[2].innerHTML=('<i class="fa fa-cny"></i> 0.00');
						}else{
							$.niftyNoty({
								type: 'danger',
								container: 'floating',
								html: '<strong>操作失败</strong> '+data.msgs,
								closeBtn: false,
								floating: {
									position: "top-right",
									animationIn: "bounceInDown",
									animationOut: "fadeOut"
								},
								focus: true,
								timer: 5000
							});
						}
					},'json');
				}
			}
		}
	});
}

function add_limit_rule(){
	$.get('/settings/add_limit_rule',function(html){
		$('#remoteModal div.modal-content').html(html);
		$('#remoteModal').modal('show');
	});
}
function edit_limit_rule(id){
	$.get('/settings/edit_limit_rule/'+id,function(html){
		$('#remoteModal div.modal-content').html(html);
		$('#remoteModal').modal('show');
	});
}
</script>