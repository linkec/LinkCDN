<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-07-01 17:15:01

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">账户设置</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->
<!-- MAIN CONTENT -->
<div id="page-content">
<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title">个人信息</h3>
	</div>

	<!--Data Table-->
	<!--===================================================-->
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-3">
				<div class="panel-heading">
					<h3 class="panel-title">账户余额</h3>
				</div>
				<div class="panel-body">
					<h1 class="mar-no text-warning" ><i class="fa fa-cny"></i> <?=$myinfo['wealth']?></h1>
					<hr>
					<a class="btn btn-warning" onclick="topup();">充值</a>
					<a class="btn btn-primary">发票</a>
					<a href="/orders/topup" class="btn btn-primary">订单记录</a>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="panel-heading">
					<h3 class="panel-title">账号信息</h3>
				</div>
				<div class="panel-body">
					<ul class="list-group bord-no">
						<li class="list-group-item">用户姓名：<?=$myinfo['username']?></li>
						<li class="list-group-item">登录邮箱：<?=$myinfo['email']?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel-heading">
					<h3 class="panel-title">基本资料</h3>
				</div>
				<style>
				.basic-info a{
					margin:0 10px;
				}
				.list-group-item {
				}
				</style>
				<div class="panel-body">
					<ul class="list-group bord-no basic-info" style="font-size:13px;">
						<li class="list-group-item">账户类型：<span class="text-main">个人账户<span> <a href="javascript:void(0);" onclick="upgrade_ent();" class="text-primary"> 升级为企业用户</a></li>
						<li class="list-group-item">手机号码：<span class="text-main" id="phone_number"><?=$myinfo['phone']?> <a href="javascript:void(0);" onclick="edit_phone();" class="text-primary"> 修改 </a> </span> 
							<div id="edit_phone_tip" style="display:none;"> 
								<form class="form-inline" onsubmit="return save_phone();">
									<div class="form-group">
										<input type="text" name="phone" placeholder="请输入手机号码" id="phone_num" class="form-control input-sm">
									</div>
									<button class="btn btn-primary btn-sm" type="submit">修改</button>
									<a class="btn btn-primary btn-sm" href="javascript:void(0);" onclick="$('#edit_phone_tip').css('display','none');">取消</a>
								</form>
					        </div>
						</li>
						<li class="list-group-item">所属行业：<span class="text-main">未设定<span> <a href="javascript:void(0);" onclick="edit_industry();" class="text-primary"> 修改</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!--===================================================-->
	<!--End Data Table-->

</div>
</div>
<!-- END MAIN CONTENT -->
<script>
function topup(){
	$.get('/settings/topup',function(data){$('#remoteModal div.modal-content').html(data)});
	$('#remoteModal').modal('show');
}
function edit_phone(){
	$('#edit_phone_tip').css('display','inline-table');
}
function save_phone(){
	var phone_num = $('#phone_num').val();
	if(!$.isNumeric(phone_num)){
		$.niftyNoty({
			type: 'danger',
			container: 'floating',
			html: '<strong>请输入正确的手机号码</strong> ',
			closeBtn: false,
			floating: {
				position: "top-right",
				animationIn: "bounceInDown",
				animationOut: "fadeOut"
			},
			focus: true,
			timer: 5000
		});
	}else{
		$.ajax({
			url: '/settings/save_phone',
			type: "POST",
			data: 'phone_num='+phone_num,
			dataType:'json',
			success: function (data) {
				if(data.status=='success'){
					$('#phone_number').html(phone_num+'<a href="javascript:void(0);" onclick="edit_phone();" class="text-primary"> 修改 </a>');
					$('#edit_phone_tip').css('display','none');
					// $('#edit_phone_tip').html(' <a href="javascript:void(0);" onclick="verify_phone();" class="text-primary">验证</a>');
				}else{
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
				// alert(errorThrown);
			}
		});

	}
	return false;
}
function upgrade_ent(){
	$.niftyNoty({
		type: 'danger',
		container: 'floating',
		html: '<strong>系统升级中，请联系客服处理</strong> ',
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
function edit_industry(){
	$.niftyNoty({
		type: 'danger',
		container: 'floating',
		html: '<strong>系统升级中，请联系客服处理</strong> ',
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
</script>