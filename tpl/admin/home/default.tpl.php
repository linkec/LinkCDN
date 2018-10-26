<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">系统概览</h1>
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
								<p class="h3 text-light mar-no media-heading">{$today_data}</p>
								<span>今日消耗流量</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							加速流量：{#get_size($data['data']['hdata'])#}
							<span class="pull-right">回源流量：{#get_size($data['data']['bdata'])#}</span>
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
								<p class="h3 text-light mar-no media-heading">{#get_humannum($today_req)#} 次</p>
								<span>今日请求次数</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							加速次数：{#get_humannum($data['data']['hreq'])#}
							<span class="pull-right">回源次数：{#get_humannum($data['data']['breq'])#}</span>
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
								<p class="h3 text-light mar-no media-heading">{$today_paid} 元</p>
								<span>今日消费金额</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							昨日消费 <span class="text-semibold"> {$yesterday_paid} 元</span>
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
								<p class="h3 text-light mar-no media-heading">{$myinfo['wealth']} 元</p>
								<span>账户余额</span>
							</div>
						</div>
						<div class="progress progress-xs progress-success mar-no">
							<div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-light" style="width: 30%"></div>
						</div>
						<div class="pad-all text-sm">
							预计可维持当前服务 <span class="text-semibold">{$approx_days}</span> 天 
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
	</div>
	<script>
	
function topup(){
	$.get('/settings/topup',function(data){$('#remoteModal div.modal-content').html(data)});
	$('#remoteModal').modal('show');
}
	Morris.Area({
			element: 'morris-chart-network',
			data: {$_5m_data},
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