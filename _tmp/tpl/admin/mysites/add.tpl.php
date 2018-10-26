<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-07-01 17:15:07

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<div id="page-title">
	<h1 class="page-header text-overflow">添加域名</h1>
</div>

<div id="page-content">
<div class="col-lg-12">
	<h4 class="text-main pad-btm bord-btm">添加一个新的网站至 RayCDN 进行安全防护和加速</h4>
	<div class="panel">

		<!-- Bubble Numbers Form Wizard -->
		<!--===================================================-->
		<div id="demo-step-wz">
			<div>

				<!--Nav-->
				<ul class="wz-nav-off wz-icon-inline wz-classic">
					<li class="col-xs-4 bg-mint active">
						<a>
							<span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="demo-pli-information icon-lg"></i></span> 添加域名
						</a>
					</li>
					<li class="col-xs-4 bg-mint">
						<a>
							<span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="demo-pli-male icon-lg"></i></span> 配置子域名
						</a>
					</li>
					<li class="col-xs-4 bg-mint">
						<a>
							<span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="demo-pli-home icon-lg"></i></span> 配置服务商设置
						</a>
					</li>
				</ul>
				<div class="progress progress-xs progress-striped active">
					<div class="progress-bar progress-bar-dark" style="width: 33.33%;"></div>
				</div>
			</div>

			<!--Form-->
			<form class="form-horizontal" id="site_add_1" action="/mysites/add/post" onsubmit="return site_add_s1(this);">
				<input type="hidden" name="cdn_type" value="ns" class="hide">
				<div class="panel-body">
					<div class="col-lg-8 col-lg-offset-2 tab-content">
						<div class="tab-pane active" id="tab1">
							<br>
							<h3><strong>第一步 </strong> - 添加域名</h3>
							<div class="alert alert-info fade in">
								<i class="fa-fw fa fa-info"></i>
								<strong>提醒</strong> 请输入您的域名并选择接入方式
							</div>
							<div class="row" id="domain_input">
								<div class="col-sm-12">
									<div class="input-group" style="width:100%;padding-bottom:20px;">
										<input class="form-control input-lg" placeholder="请输入您的域名，不包含 www；例如：raycdn.com" type="text" name="domain" id="domain">
									</div>
								</div>
							</div>
							<div id="domain_alert">
							</div>
							<style>
							.type-item {
								height: 100px;
								padding: 5px 0;
								border: 1px dashed #e5e5e5;
								cursor: pointer;
								width: 400px;
							}
							.type-item.active{
								border: 1px solid #3cadfb;
								background: url(<?=$static_url?>/img/icon_addweb_choose_ea192ff.png) no-repeat bottom right;
							}
							.type-item p.title {
								color: #333;
								font-weight: 700;
								line-height: 32px;
								height: 32px;
							}
							.type-item p {
								color: #999;
								line-height: 22px;
								height: 22px;
								text-indent: 10px;
								margin:0px;
							}
							#domain_alert {
								padding-bottom:20px;
							}
							</style>
							<div class="row">
								<div class="col-sm-6">
									<div class="type-item active pull-right" id="cdn_type_ns" onclick="select_ns();">
										<p class="title">NS接入方式（推荐）</p>
										<p>一劳永逸</p>
										<p>NS方式可以使用RAYCDN高智能DNS服务</p>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="type-item" id="cdn_type_cn" onclick="select_cn();">
										<p class="title">CNAME接入方式</p>
										<p>简单快捷、生效迅速</p>
										<p>每个子域名都需要单独设置CNAME解析</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--Footer button-->
				<div class="panel-footer text-right">
					<div class="box-inline">
						<button type="submit" class="next btn btn-info">下一步</button>
					</div>
				</div>
			</form>
		</div>
		<!--===================================================-->
		<!-- End Bubble Numbers Form Wizard -->

	</div>
</div>
</div>
<script>
function select_cn(){
	$('#cdn_type_ns').removeClass('active');
	$('#cdn_type_cn').addClass('active');
	$('#site_add_1')[0].cdn_type.value='cn';
}
function select_ns(){
	$('#cdn_type_ns').addClass('active');
	$('#cdn_type_cn').removeClass('active');
	$('#site_add_1')[0].cdn_type.value='ns';
}
function site_add_s1(){
	var form = $('#site_add_1');
	$('button').attr('disabled',"true");
	  $.ajax({
			url: form.attr('action'),
			type: "POST",
			data: form.serialize(),
			dataType:'json',
			success: function (data) {
			  if(data.status=='success'){
				  location = '/mysites/add_2/'+data.id;
			  }else{
				  
				$.niftyNoty({
					type: 'danger',
					container: '#domain_alert',
					html: '<strong>错误</strong><br>'+data.msgs,
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
			  $('button').removeAttr('disabled',"true");
			},
			error: function (jqXhr, textStatus, errorThrown) {
				$('button').removeAttr('disabled',"true");
				alert(errorThrown);
			}
	  });
	event.preventDefault();
	return false;
}
</script>