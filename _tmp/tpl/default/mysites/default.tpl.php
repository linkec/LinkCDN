<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-06-23 21:55:45

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">我的域名</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!-- MAIN CONTENT -->
<div id="page-content">
	
	<div class="panel panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">域名列表</h3>
		</div>
		<div class="panel-body">
			<div class="pad-btm form-inline">
				<div class="row">
					<div class="col-sm-6 table-toolbar-left">
						<a class="btn btn-purple" href="/mysites/add"><i class="demo-pli-add"></i> 添加域名</a>
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
							<th style="width:90px;">接入方式</th>
							<th style="width:120px;">域名</th>
							<th style="width:200px;">状态</th>
							<th style="width:150px;" class="text-right">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($mysites as $v){ ?>
						<tr id="site_<?=$v['id']?>">
							<td style="width:90px;"><span class="label label-purple"><?=strtoupper($v['cdn_type'])?></span></td>
							<td style="width:120px;" class="lead text-uppercase"><?=$v['domain']?></td>
							<td style="width:200px;" class="site_status">
								<?php if($v['status']=='wait_verify'){ ?>
									等待验证
								<?php }elseif($v['status']=='success'){ ?>
								<a class="add-tooltip <?=in_array($v['id'],$antiddos_sites) ? 'text-danger' : 'text-success'?>" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="AntiDDoS <?=in_array($v['id'],$antiddos_sites) ? 'Pro' : '免费版'?>"><i class="ion-nuclear"></i></a>
								<a class="add-tooltip <?=in_array($v['id'],$waf_sites) ? 'text-danger' : 'text-success'?>" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Waf <?=in_array($v['id'],$waf_sites) ? 'Pro' : '免费版'?>"><i class="ion-leaf"></i></a>
								<?php }elseif($v['status']=='pendding'){ ?>
									等待配置主机名
								<?php } ?>
							</td>
							<td style="width:150px;" class="text-right">
								<?php if($v['status']=='success'){ ?>
								<a class="btn btn-primary" href="/mysites/records/<?=$v['id']?>"><i class="fa fa-sitemap"></i> 域名解析 </a>
								<a class="btn btn-primary" href="/mysites/controll/<?=$v['id']?>"><i class="fa fa-cog"></i> 控制面板 </a>
								<a class="btn btn-primary" href="/mysites/stats/<?=$v['id']?>"><i class="fa fa-bar-chart-o"></i> 统计报表 </a>
								<?php }elseif($v['status']=='pendding'){ ?>
								<a class="btn btn-warning" href="/mysites/add_2/<?=$v['id']?>"><i class="fa fa-forward"></i> 继续接入</a>
								<a class="btn btn-primary" href="/mysites/records/<?=$v['id']?>"><i class="fa fa-sitemap"></i> 域名解析 </a>
								<?php }elseif($v['status']=='wait_verify'){ ?>
								<a class="btn btn-warning" onclick="verify_dns(<?=$v['id']?>)"><i class="fa fa-refresh"></i> 重新验证</a>
								<a class="btn btn-warning" href="/mysites/add_2/<?=$v['id']?>"><i class="fa fa-forward"></i> 继续接入</a>
								<a class="btn btn-primary" href="/mysites/records/<?=$v['id']?>"><i class="fa fa-sitemap"></i> 域名解析 </a>
								<?php } ?>
								<a class="btn btn-danger" onclick="delete_site(<?=$v['id']?>);"><i class="fa font-lg fa-trash-o"></i></a>
							</td>
						</tr>
						<?php } ?>
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
	$("#site_"+id+" .site_status").html('<img src="<?=$static_url?>/img/loading.png"> 正在验证中');
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