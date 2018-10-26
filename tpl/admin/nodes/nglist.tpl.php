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
			<h3 class="panel-title">节点列表</h3>
		</div>
		<div class="panel-body">
			<div class="pad-btm form-inline">
				<div class="row">
					<div class="col-sm-6 table-toolbar-left">
						<a class="btn btn-purple" onclick="add_node()"><i class="demo-pli-add"></i> 添加节点</a>
						<a class="btn btn-purple" href="/nodes/reload">重载节点</a>
						<a class="btn btn-purple" href="/views_code.html" target="_blank">节点代码</a>
					</div>
					<div class="col-sm-6 table-toolbar-right">
						<div class="form-group">
							<input id="demo-input-search2" type="text" placeholder="Search" class="form-control" autocomplete="off">
						</div>
						<div class="btn-group">
							<button class="btn btn-default"><i class="ion-search"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-vcenter">
					<thead>
						<tr>
							<th>节点名</th>
							<th>所属套餐</th>
							<th>状态</th>
							<th>负载</th>
							<th>内存</th>
							<th>连接数</th>
							<th>网卡</th>
							<th class="text-right">操作</th>
						</tr>
					</thead>
					<tbody>
						<!--#foreach($nodes as $v){#-->
						<tr id="node_{$v['id']}">
							<td>{$v['name']}<br>{$v['hostname']}</td>
							<td>{$v['area']}<br>{$v['views']}</td>
							<td>{$v['status']} {$v['check_time']} 秒前</td>
							<td>{$v['load_avg']}</td>
							<td>已用：{#get_size($v['mem_free']*1024)#} <br>总共：{#get_size($v['mem_total']*1024)#}</td>
							<td>{$v['connections']}</td>
							<td><font color="red"><i class="ion-arrow-up-c"></i> {$v['tx_result']}/s </font> | <font color="green"><i class="ion-arrow-down-c"></i> {$v['rx_result']}/s </font><br>{$v['ip']}</td>
							<td><a class="btn btn-xs btn-primary" onclick="edit_node({$v['id']})">编辑</a></td>
						</tr>
						<!--#if($act=='nglist'){#-->
						<tr>
							<td colspan="3">{$v['ng_status']}</td>
							<td colspan="5">{$v['ng_configtest']}</td>
						</tr>
						<!--#}#-->
						<!--#}#-->
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- END MAIN CONTENT -->
<script>
function edit_node(id){
	$.get('/nodes/edit/'+id,function(html){$('#remoteModal div.modal-content').html(html)});
	$('#remoteModal').modal('show');
}
function delete_site(id){
	bootbox.dialog({
		message: "你确定要删除该网站吗？删除该网站后将无法继续使用RayCDN提供的加速及安全防护！",
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
					$.get('/mysites/delete_site/'+id,function(data){
						if(data.status=='success'){
							$.niftyNoty({
								type: 'success',
								container: 'floating',
								html: '<strong>域名已经成功删除</strong>',
								closeBtn: false,
								floating: {
									position: "top-right",
									animationIn: "bounceInDown",
									animationOut: "fadeOut"
								},
								focus: true,
								timer: 5000
							});
							$("#site_"+id).remove();
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
</script>