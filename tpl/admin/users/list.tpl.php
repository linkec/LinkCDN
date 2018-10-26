<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">用户管理</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!-- MAIN CONTENT -->
<div id="page-content">
	
	<div class="panel panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">用户列表</h3>
		</div>
		<div class="panel-body">
			<div class="pad-btm form-inline">
				<div class="row">
					<div class="col-sm-6 table-toolbar-left">
						<a class="btn btn-purple" href="/admin/mysites/add"><i class="demo-pli-add"></i> 添加用户</a>
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
							<th>用户ID</th>
							<th>用户名</th>
							<th>电子邮箱</th>
							<th>手机号码</th>
							<th>余额</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<!--#foreach($users as $v){#-->
						<tr>
							<td>{$v['userid']}</td>
							<td>{$v['username']}</td>
							<td>{$v['email']}</td>
							<td>{$v['phone']}</td>
							<td>{$v['wealth']}</td>
							<td><a class="btn btn-primary" href="/admin/users/login/?userid={$v['userid']}" target="_blank">模拟登陆</a></td>
						</tr>
						<!--#}#-->
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- END MAIN CONTENT -->
<script>
function verify_dns(id){
	$("#site_"+id+" .fa.fa-refresh").parent().attr('onclick','');
	$("#site_"+id+" .site_status").html('<img src="{$static_url}/img/loading.png"> 正在验证中');
	$.ajax({
		url: '/mysites/verify_dns',
		type: "POST",
		data: 'site_id='+id,
		dataType:'json',
		success: function (data) {
			if(data.status=='success'){
				$("#site_"+id+" .site_status").html('验证通过，请刷新本页进行操作');
			}else{
				$("#site_"+id+" .fa.fa-refresh").parent().attr('onclick','verify_dns('+id+')');
				$("#site_"+id+" .site_status").html('验证失败，请重新验证');
				$.niftyNoty({
					type: 'danger',
					container: 'floating',
					html: '<strong>错误</strong> '+data.msgs,
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
		},
		error: function (jqXhr, textStatus, errorThrown) {
			console.log(errorThrown);
			$.niftyNoty({
				type: 'danger',
				container: 'floating',
				html: '<strong>错误</strong> '+textStatus,
				closeBtn: false,
				floating: {
					position: "top-right",
					animationIn: "bounceInDown",
					animationOut: "fadeOut"
				},
				focus: true,
				timer: 5000
			});
			$("#site_"+id+" .fa,.fa-refresh").parent().attr('onclick','verify_dns('+id+')');
			$("#site_"+id+" .site_status").html('验证失败，请重新验证');
		}
	});
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