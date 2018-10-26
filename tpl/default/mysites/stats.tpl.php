<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div id="page-title">
	<h1 class="page-header text-overflow">数据报表</h1>
</div>
<div id="page-content">
	<div class="panel">
		<div class="panel-heading">
			<form class="panel-control form-inline" onchange="if(this.site_id.value=={$site_id}){location='/mysites/stats/'+this.site_id.value+'/'+this.host.value+'/'+this.period.value;}else{location='/mysites/stats/'+this.site_id.value;}">
				<div class="form-group">
					<label class="">子域名</label>
					<select class="form-control" name="host">
						<!--#foreach($records as $k=>$v){#-->
							<option value="{$k}" {#ifselected($k,$host,'str')#}>{$k}</option>
						<!--#}#-->
					</select>
				</div>
				 | 
				<div class="form-group">
					<label class="">主域名</label>
					<select class="form-control" name="site_id">
						<!--#foreach($domains as $v){#-->
							<option value="{$v['id']}" {#ifselected($v['domain'],$site['domain'],'str')#}>{$v['domain']}</option>
						<!--#}#-->
					</select>
				</div>
				 | 
				<div class="form-group">
					<select class="form-control" name="period">
						<option value="today" {#ifselected('today',$period,'str')#}>今日</option>
						<option value="yesterday" {#ifselected('yesterday',$period,'str')#}>昨日</option>
						<option value="7days" {#ifselected('7days',$period,'str')#}>最近7日</option>
						<option value="30days" {#ifselected('30days',$period,'str')#}>最近30日</option>
					</select>
				</div>
			</form>
			<h3 class="panel-title">
				报表
			</h3>
		</div>
		<div class="panel-body">
			<h1 class="page-header text-overflow pad-btm" id="source">情报概览</h1>
			<hr>
			<div class="row">
				<div class="col-lg-3">
					<div class="panel panel-primary panel-colorful">
						<div class="pad-all text-center">
							<span class="text-3x text-thin" id="stat_req">-</span>
							<p>请求总数</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-warning panel-colorful">
						<div class="pad-all text-center">
							<span class="text-3x text-thin" id="stat_uv">-</span>
							<p>独立访客数（UV）</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-purple panel-colorful">
						<div class="pad-all text-center">
							<span class="text-3x text-thin" id="stat_ip">-</span>
							<p>独立访问 IP 数（含蜘蛛）</p>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-primary panel-colorful">
						<div class="pad-all text-center">
							<span class="text-3x text-thin" id="stat_data">-</span> <span class="text-3x text-thin" id="stat_data_unit"></span>
							<p>消耗流量</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="panel">
						<div class="panel-body">
			
							<!--Morris Donut Chart placeholder -->
							<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
							<div id="pie1" style="width:100%;height:400px"></div>
							<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
			
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="panel">
						<div class="panel-body">
			
							<!--Morris Donut Chart placeholder -->
							<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
							<div id="pie2" style="height:400px"></div>
							<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
			
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title">流量趋势图</h3>
						</div>
						<div class="panel-body">
			
							<!--Morris Area Chart placeholder-->
							<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
							<div id="llqst" style="height:212px"></div>
							<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
			
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
// $(document).ready(function() {
function get_stats_data(){
	$.getScript('/mysites/stats_data/{$site_id}/{$host}/{$period}/');
}
get_stats_data();
// setInterval('get_stats_data()',10000);
// }
</script>