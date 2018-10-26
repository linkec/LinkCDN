<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2018-10-26 20:23:58

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">我的概览</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!--Page content-->
<!--===================================================-->
<div id="page-content">
	
	<div class="row">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-sm-6 col-lg-3">
					<div class="panel panel-primary panel-colorful">
						<div class="pad-all media">
							<div class="media-left">
								<i class="ion-ios-pulse icon-3x icon-fw"></i>
							</div>
							<div class="media-body">
								<p class="h3 text-light mar-no media-heading"><?=$today_data?></p>
								<span>今日消耗流量</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							加速流量：<?=get_size($data['data']['hdata'])?>
							<span class="pull-right">回源流量：<?=get_size($data['data']['bdata'])?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="panel panel-primary panel-colorful">
						<div class="pad-all media">
							<div class="media-left">
								<i class="ion-ios-color-wand-outline icon-3x icon-fw"></i>
							</div>
							<div class="media-body">
								<p class="h3 text-light mar-no media-heading"><?=get_humannum($today_req)?> 次</p>
								<span>今日请求次数</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							加速次数：<?=get_humannum($data['data']['hreq'])?>
							<span class="pull-right">回源次数：<?=get_humannum($data['data']['breq'])?></span>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="panel panel-primary panel-colorful">
						<div class="pad-all media">
							<div class="media-left">
								<i class="ion-social-yen-outline icon-3x icon-fw"></i>
							</div>
							<div class="media-body">
								<p class="h3 text-light mar-no media-heading"><?=$today_paid?> 元</p>
								<span>今日消费金额</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							昨日消费 <span class="text-semibold"> <?=$yesterday_paid?> 元</span>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="panel panel-primary panel-colorful">
						<div class="pad-all media">
							<div class="media-left">
								<i class="ion-social-yen icon-3x icon-fw"></i>
							</div>
							<div class="media-body">
								<p class="h3 text-light mar-no media-heading"><?=$myinfo['wealth']?> 元</p>
								<span>账户余额</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							预计可维持当前服务 <span class="text-semibold"><?=$approx_days?></span> 天 
							<span class="pull-right" style="cursor:pointer;" onclick="topup();">充值</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
		<div class="alert alert-dark">
				<strong>请注意 ：</strong> 请保持账户内有充足的余额，账户一旦欠费，系统将会对您的域名停止加速和防护服务并进行回源处理（您的源IP将暴露）。
			</div>
			</div>
		<div class="col-lg-12">
	
			<!--Network Line Chart-->
			<!--===================================================-->
			<div id="demo-panel-network" class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">今日加速流量概览</h3>
				</div>
				<div class="panel-body">
					<!--Morris line chart placeholder-->
					<div id="morris-chart-network" class="morris-full-content" style="height:300px;padding-bottom:20px;"></div>
				</div>
			</div>
			<!--===================================================-->
			<!--End network line chart-->
	
		</div>
		<div class="col-lg-12">
			<div class="panel-group accordion" id="demo-acc-purple">
				<div class="panel panel-primary">
	
					<!-- Accordion title -->
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-parent="#demo-acc-purple" data-toggle="collapse" href="#demo-acd-purple-1" aria-expanded="true" class="">什么情况下解析会被回源？</a>
						</h4>
					</div>
	
					<!-- Accordion content -->
					<div class="panel-collapse collapse in" id="demo-acd-purple-1" aria-expanded="true">
						<div class="panel-body">
							一：加速系统按照流量消耗每小时计费一次，一旦账户欠费，系统将在下一个计费周期停止加速服务并对域名进行回源处理，此时充值缴清欠款即可恢复加速服务。</br>
							二：我们免费为所有用户提供 5Gbps ddos 防御；对于没有购买 Anti-DDOS Pro 的用户如果攻击流量大于5Gbps系统将会对解析进行回源处理以避免影响其他用户。</br>
						</div>
					</div>
				</div>
				<div class="panel panel-primary">
	
					<!-- Accordion title -->
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-parent="#demo-acc-purple" data-toggle="collapse" href="#demo-acd-purple-2" class="collapsed" aria-expanded="false">网站正在被攻击，如何接入？</a>
						</h4>
					</div>
	
					<!-- Accordion content -->
					<div class="panel-collapse collapse" id="demo-acd-purple-2" aria-expanded="false">
						<div class="panel-body">
							我们对正在被攻击的用户提供全程的技术支持；如果您的网站/服务器正在被攻击，请联系我们的客服根据您网站正在遭受的攻击类型、大小等因素为您提供最佳的接入方案，客服会全程协助您完成系统的接入直至网站服务完全恢复。
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	
function topup(){
	$.get('/settings/topup',function(data){$('#remoteModal div.modal-content').html(data)});
	$('#remoteModal').modal('show');
}
	Morris.Area({
			element: 'morris-chart-network',
			data: <?=$_5m_data?>,
			// axes:false,
			gridEnabled: true,
			gridLineColor: '#e7ecf3',
			behaveLikeLine: true,
			xkey: 'timeline',
			ykeys: ['hit_data', 'bypass_data'],
			labels: ['加速流量', '回源流量'],
			lineColors: ['#045d97'],
			pointSize: 0,
			postUnits: ' m',
			pointStrokeColors : ['#045d97'],
			lineWidth: 0,
			resize:false,
			hideHover: 'auto',
			fillOpacity: 0.7,
			parseTime:true,
			// xLabelFormat: function(x) { return ''; },
			// yLabelFormat: function(x) { return ''; },
			dateFormat:function (x) { return new Date(x).Format('yyyy-MM-dd hh:mm') },
		});
	</script>