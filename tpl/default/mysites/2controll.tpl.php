<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">{$site['domain']}</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->
<style>
.tab-pane {
	display:none;
}
.tab-pane.in{
	display:block;
}
</style>
<!-- MAIN CONTENT -->
<div id="page-content">
	<div class="panel-group accordion" id="accordion">
		<!--#foreach($records as $k=>$v){
			$ii++;if($ii>1){$tmp_css='collapsed';$tmp_css2='';$tmp_bool='false';}else{$tmp_css='';$tmp_css2=' in';$tmp_bool='true';}
			#-->
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-parent="#accordion" data-toggle="collapse" href="#{$k}_panel" aria-expanded="{$tmp_bool}" class="{$tmp_css}">{$k}.{$site['domain']}</a>
				</h4>
			</div>
			<div class="panel-collapse collapse{$tmp_css2}" id="{$k}_panel" aria-expanded="true">
				<div class="panel-body">
					<div class="tab-base tab-stacked-left">
						<!--Nav tabs-->
						<ul class="nav nav-tabs" style="width: 120px;">
							<li class="active">
								<a href="#{$k}_source" data-toggle="tab" aria-expanded="true">取源设置</a>
							</li>
							<li class="">
								<a href="#{$k}_https" data-toggle="tab" aria-expanded="false">HTTPS设置</a>
							</li>
							<li class="">
								<a href="#{$k}_cache" data-toggle="tab" aria-expanded="false">缓存规则</a>
							</li>
							<li class="">
								<a href="#{$k}_accel" data-toggle="tab" aria-expanded="false">加速设置</a>
							</li>
							<li class="">
								<a href="#{$k}_security" data-toggle="tab" aria-expanded="false">安全防护</a>
							</li>
							<li class="">
								<a href="#{$k}_nodes" data-toggle="tab" aria-expanded="false">优选节点</a>
							</li>
						</ul>
			
						<!--Tabs Content-->
						<form class="form-horizontal" action="/mysites/save_settings" onsubmit="return ajaxform(this);">
						<div class="">
							<div class="tab-pane fade active in" id="{$k}_source">
								<div class="form-group">
									<label class="col-md-2 control-label">回源节点</label>
									<div class="col-md-8 control-panel" style="padding-top: 7px;">
										<div class="row">
											<div class="col-sm-2">
												回源地址
											</div>
											<div class="col-sm-1">
												端口号
											</div>
											<div class="col-sm-2">
												线路类别
											</div>
											<div class="col-sm-1">
												权重
											</div>
											<div class="col-sm-2">
												最大失败数
											</div>
											<div class="col-sm-2">
												静默时间（s）
											</div>
										</div>
										<!--#foreach($v as $vv){#-->
										<div class="row sources">
											<div class="col-sm-2">
												<input type="text" disabled="disabled" class="form-control dns_value" value="{$vv['dns_value']}">
											</div>
											<div class="col-sm-1">
												<input type="text" name="source[{$vv['id']}][cdn_port]" class="form-control" value="{$vv['cdn_port']}">
											</div>
											<div class="col-sm-2">
												<select class="form-control" name="source[{$vv['id']}][cdn_type]">
													<option value="main" {#ifselected('main',$vv['cdn_type'],'str')#}>主线路</option>
													<option value="backup" {#ifselected('backup',$vv['cdn_type'],'str')#}>备用</option>
													<option value="down" {#ifselected('down',$vv['cdn_type'],'str')#}>停用</option>
												</select>
											</div>
											<div class="col-sm-1">
												<input type="text" name="source[{$vv['id']}][cdn_weight]" class="form-control" value="{$vv['cdn_weight']}">
											</div>
											<div class="col-sm-2">
												<input type="text" name="source[{$vv['id']}][cdn_fails]" class="form-control" value="{$vv['cdn_fails']}">
											</div>
											<div class="col-sm-2">
												<input type="text" name="source[{$vv['id']}][cdn_wait]" class="form-control" value="{$vv['cdn_wait']}">
											</div>
										</div>
										<!--#}#-->
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">回源协议</label>
									<div class="col-md-10">
										<label class="checkbox-inline">
											<input type="checkbox" class="checkbox source_protocol source_protocol_http" name="source_protocol" value="http" {#ifchecked($host_settings[$k]['source_protocol'],'http','str')#} onclick="if(this.checked){$('#{$k}_panel .source_protocol_https').attr('checked', false);}else{$('#{$k}_panel .source_protocol_https').click()}">
											<span>HTTP</span>
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" class="checkbox source_protocol source_protocol_https" name="source_protocol" value="https" {#ifchecked($host_settings[$k]['source_protocol'],'https','str')#} onclick="if(this.checked){$('#{$k}_panel .source_protocol_http').attr('checked', false);}else{$('#{$k}_panel .source_protocol_http').click()}">
											<span>HTTPS</span>
										</label>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="{$k}_https">
								<div class="form-group">
									<label class="col-md-2 control-label">HTTPS</label>
									<div class="col-md-10">
										<label class="checkbox-inline">
											  <input type="checkbox" class="checkbox https_switch" name="https_switch" {#ifchecked($host_settings[$k]['https_switch'],1,'int')#} value="1" onchange="if(!this.checked){$('#{$k}_panel .https_only,.http2_switch').attr('disabled', 'disabled');$('#{$k}_panel .https_only,.http2_switch').attr('checked', false);}else{$('#{$k}_panel .https_only,.http2_switch').removeAttr('disabled');}">
											  <span>启用</span>
										</label>
										<label class="checkbox-inline">
											  <input type="checkbox" class="checkbox https_only" name="https_only" value="1" {#ifchecked($host_settings[$k]['https_only'],1,'int')#} {#$host_settings[$k]['https_switch'] ? '' : 'disabled="disabled"'#}>
											  <span>仅限HTTPS访问</span>
										</label>
										<label class="checkbox-inline">
											  <input type="checkbox" class="checkbox http2_switch" name="http2_switch" value="1" {#ifchecked($host_settings[$k]['http2_switch'],1,'int')#} {#$host_settings[$k]['https_switch'] ? '' : 'disabled="disabled"'#}>
											  <span>启用HTTP2</span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">密钥选择</label>
									<div class="col-md-2 control-panel">
										<select class="form-control keychains" name="keychains">
											<option value="0">请选择对应的密钥</option>
											<!--#foreach($certs as $vvv){#-->
											<option value="{$vvv['id']}" {#ifselected($host_settings[$k]['keychains'],$vvv['id'],'int')#}>{$vvv['name']}</option>
											<!--#}#-->
										</select>
									</div>
									<div class="col-md-2 control-panel">
										<a class="btn btn-primary" onclick="manage_keychains();">管理密钥</a>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="{$k}_cache">
									<fieldset class="">
										<div class="form-group">
											<label class="col-md-2 control-label">浏览器缓存</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="browser_cache_switch" {#ifchecked($host_settings[$k]['browser_cache_switch'],1,'int')#} value="1">
													  <span>启用</span>
												</label>
											</div>
											<div class="col-md-1">
												<input class="form-control" name="browser_cache_time" value="{#$host_settings[$k]['browser_cache_time']#}" type="text">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="browser_cache_unit">
													<option value="s" {#ifselected($host_settings[$k]['browser_cache_unit'],'s','str')#}>秒</option>
													<option value="m" {#ifselected($host_settings[$k]['browser_cache_unit'],'m','str')#}>分</option>
													<option value="h" {#ifselected($host_settings[$k]['browser_cache_unit'],'h','str')#}>时</option>
													<option value="d" {#ifselected($host_settings[$k]['browser_cache_unit'],'d','str')#}>天</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">缓存JS/CSS/图片</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="statics_cache_switch" value="1" {#ifchecked($host_settings[$k]['statics_cache_switch'],1,'int')#}>
													  <span>启用</span>
												</label>
											</div>
											<div class="col-md-1">
													<input class="form-control" value="{#$host_settings[$k]['statics_cache_time']#}" type="text" name="statics_cache_time">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="statics_cache_unit">
													<option value="s" {#ifselected($host_settings[$k]['statics_cache_unit'],'s','str')#}>秒</option>
													<option value="m" {#ifselected($host_settings[$k]['statics_cache_unit'],'m','str')#}>分</option>
													<option value="h" {#ifselected($host_settings[$k]['statics_cache_unit'],'h','str')#}>时</option>
													<option value="d" {#ifselected($host_settings[$k]['statics_cache_unit'],'d','str')#}>天</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">缓存静态HTML</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="html_cache_switch" value="1" {#ifchecked($host_settings[$k]['html_cache_switch'],1,'int')#}>
													  <span>启用</span>
												</label>
											</div>
											<div class="col-md-1">
												<input class="form-control" value="{#$host_settings[$k]['html_cache_time']#}" type="text" name="html_cache_time">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="html_cache_unit">
													<option value="s" {#ifselected($host_settings[$k]['html_cache_unit'],'s','str')#}>秒</option>
													<option value="m" {#ifselected($host_settings[$k]['html_cache_unit'],'m','str')#}>分</option>
													<option value="h" {#ifselected($host_settings[$k]['html_cache_unit'],'h','str')#}>时</option>
													<option value="d" {#ifselected($host_settings[$k]['html_cache_unit'],'d','str')#}>天</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">缓存首页</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="index_cache_switch" value="1" {#ifchecked($host_settings[$k]['index_cache_switch'],1,'int')#}>
													  <span>启用</span>
												</label>
											</div>
											<div class="col-md-1">
												<input class="form-control" value="{#$host_settings[$k]['index_cache_time']#}" type="text" name="index_cache_time">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="index_cache_unit">
													<option value="s" {#ifselected($host_settings[$k]['index_cache_unit'],'s','str')#}>秒</option>
													<option value="m" {#ifselected($host_settings[$k]['index_cache_unit'],'m','str')#}>分</option>
													<option value="h" {#ifselected($host_settings[$k]['index_cache_unit'],'h','str')#}>时</option>
													<option value="d" {#ifselected($host_settings[$k]['index_cache_unit'],'d','str')#}>天</option>
												</select>
											</div>
										</div>
									</fieldset>
							</div>
							<div class="tab-pane fade" id="{$k}_accel">
									<fieldset class="demo-switcher-1">
										<div class="form-group">
											<label class="col-md-2 control-label">页面加速</label>
											<div class="col-md-6">
												<div class="col-md-4" rel="tooltip" data-placement="top" data-original-title="自动启用所有适用的优化项，让网页加载速度最快">
													<label class="radio radio-inline">
														  <input type="radio" class="radiobox" name="pagespeed_mode" {#ifchecked($host_settings[$k]['pagespeed_mode'],1,'int')#} value="1" onclick="check_ps_mode('{$k}');">
														  <span>极速模式</span>
													</label>
												</div>
												<div class="col-md-4" rel="tooltip" data-placement="top" data-original-title="极限压缩所有适用的资源，最大限度节约宽带">
													<label class="radio radio-inline">
														  <input type="radio" class="radiobox" name="pagespeed_mode" {#ifchecked($host_settings[$k]['pagespeed_mode'],2,'int')#} value="2" onclick="check_ps_mode('{$k}');">
														  <span>压缩模式</span>
													</label>
												</div>
												<div class="col-md-4" rel="tooltip" data-placement="top" data-original-title="自行选择启用哪些优化项">
													<label class="radio radio-inline">
														  <input type="radio" class="radiobox" name="pagespeed_mode" {#ifchecked($host_settings[$k]['pagespeed_mode'],3,'int')#} value="3" onclick="check_ps_mode('{$k}');">
														  <span>自选模式</span>
													</label>
												</div>
												<div class="col-md-4 ps_diy" rel="tooltip" data-placement="top" data-original-title="对网页加载的js元素进行压缩合并等优化">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="pagespeed_js" {#$host_settings[$k]['pagespeed_mode']!=3 ? 'disabled="disabled"' : ''#} {#ifchecked($host_settings[$k]['pagespeed_js'],1,'int')#} value="1">
														  <span>JS优化</span>
													</label>
												</div>
												<div class="col-md-4 ps_diy" rel="tooltip" data-placement="top" data-original-title="对网页加载的js元素进行压缩合并等优化2">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="pagespeed_css" {#$host_settings[$k]['pagespeed_mode']!=3 ? 'disabled="disabled"' : ''#} {#ifchecked($host_settings[$k]['pagespeed_css'],1,'int')#} value="1">
														  <span>CSS优化</span>
													</label>
												</div>
												<div class="col-md-4 ps_diy" rel="tooltip" data-placement="top" data-original-title="对网页加载的js元素进行压缩合并等优化3">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="pagespeed_image" {#$host_settings[$k]['pagespeed_mode']!=3 ? 'disabled="disabled"' : ''#} {#ifchecked($host_settings[$k]['pagespeed_image'],1,'int')#} value="1">
														  <span>图片优化</span>
													</label>
												</div>
											</div>
										</div>
										<!--<div class="form-group">
											<label class="col-md-2 control-label">静态资源托管</label>
											<div class="col-md-6">
												<div class="col-md-3">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="static_cachefly" {#ifchecked($host_settings[$k]['static_cachefly'],1,'int')#} value="1">
														  <span>启用</span>
													</label>
												</div>
											</div>
										</div>-->
										<div class="form-group">
											<label class="col-md-2 control-label">资源压缩</label>
											<div class="col-md-6">
												<div class="col-md-3">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="static_compress_gzip" {#ifchecked($host_settings[$k]['static_compress_gzip'],1,'int')#} value="1">
													  <span>GZIP</span>
												</label>
												</div>
												<div class="col-md-3">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="static_compress_brotil" {#ifchecked($host_settings[$k]['static_compress_brotil'],1,'int')#} value="1">
													  <span>Brotli</span>
												</label>
												</div>
											</div>
										</div>
									</fieldset>
							</div>
							<div class="tab-pane fade" id="{$k}_security">
									<fieldset class="demo-switcher-1">
										<div class="form-group">
											<label class="col-md-2 control-label">WAF 基础</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="waf" value="1" disabled="disabled" checked>
													  <span>启用</span>
												</label>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">WAF 高级</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="waf_pro" {#ifchecked($host_settings[$k]['waf_pro'],1,'int')#} value="1">
													  <span>启用</span>
												</label>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Anti-DDOS 基础</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="antiddos" value="1" disabled="disabled" checked>
													  <span>启用</span>
												</label>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Anti-DDOS 高级</label>
											<div class="col-md-1">
												<label class="checkbox-inline">
													  <input type="checkbox" class="checkbox" name="antiddos_pro" {#ifchecked($host_settings[$k]['antiddos_pro'],1,'int')#} value="1">
													  <span>启用</span>
												</label>
											</div>
										</div>
									</fieldset>
							</div>
							<div class="tab-pane fade" id="{$k}_nodes">
									<fieldset class="demo-switcher-1">
										<div class="form-group">
											<label class="col-md-2 control-label">普通线路</label>
											<div class="col-md-6">
												<!--#foreach($nodes[0] as $node){#-->
												<div class="col-md-3">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="nodes[]" {#in_array($node['id'],explode(',',$host_settings[$k]['nodes'])) ? 'checked' : ''#} value="{$node['id']}">
														  <span>{$node['name']}</span>
													</label>
												</div>
												<!--#}#-->
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">高速线路</label>
											<div class="col-md-6">
												<!--#foreach($nodes[1] as $node){#-->
												<div class="col-md-3">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="nodes[]" value="{$node['id']}" {#in_array($node['id'],explode(',',$host_settings[$k]['nodes'])) ? 'checked' : ''#}>
														  <span>{$node['name']}</span>
													</label>
												</div>
												<!--#}#-->
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">优化线路</label>
											<div class="col-md-6">
												<!--#foreach($nodes[2] as $node){#-->
												<div class="col-md-3">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="nodes[]" value="{$node['id']}" {#in_array($node['id'],explode(',',$host_settings[$k]['nodes'])) ? 'checked' : ''#}>
														  <span>{$node['name']}</span>
													</label>
												</div>
												<!--#}#-->
											</div>
										</div>
									</fieldset>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
		<!--#}#-->
	</div>
</div>
		<script>
		var temp_ = [];
		function show_more(site_id,host,btn){
			$('#'+host+'_panel .btn').removeClass('active');
			$('#'+host+'_panel .'+btn).addClass('active');
			$('#'+host+'_panel .entry-edit').slideDown(500);
			temp_[host] = $('#'+host+'_panel .tab-content').html();
		}
		function hide_more(site_id,host,btn){
			$('#'+host+'_panel .entry-edit').slideUp(500);
			$('#'+host+'_panel .nav-tabs li').removeClass('active');
			setTimeout("$('#"+host+"_panel .tab-content').html(temp_['"+host+"']);temp_['"+host+"']='';",500);
			
		}
		function cp_ad_cf(obj,host){
			if(obj.checked){
				$('#'+host+'_panel .entry-edit .sources input').removeAttr('disabled');
				$('#'+host+'_panel .entry-edit .sources .dns_value').attr('disabled','disabled');
				$('#'+host+'_panel .entry-edit .sources select').removeAttr('disabled');
			}else{
				$('#'+host+'_panel .entry-edit .sources input').attr('disabled','disabled');
				$('#'+host+'_panel .entry-edit .sources select').attr('disabled','disabled');
			}
		};
		function check_ps_mode(host){
			var form = $('#'+host+'_panel form')[0];
			// console.log(form);
			if(form.pagespeed_mode.value==3){
				$('#'+host+'_panel .ps_diy input').removeAttr('disabled');
			}else{
				$('#'+host+'_panel .ps_diy input').attr('disabled','disabled');
				$('#'+host+'_panel .ps_diy input').attr('checked',false);
			}
		}
		var pagefunction = function(){
			
		}
		</script>
	<script>
		$('.entry-edit input').bind('keyup',function(event){
			if(event.keyCode==13){
				dns_save($(event.target).parent().parent().parent()[0].dataset['rcdId']);
			}
		});
		function manage_keychains(){
			loadURL('/mysites/manage_keychains',$('#remoteModal div.modal-content'));
			$('#remoteModal').modal('show');
		}
		function add_keychains(){
			loadURL('/mysites/add_keychains',$('#remoteModal div.modal-content'));
			$('#remoteModal').modal('show');
		}
		function update_keychains(){
			$.get('/mysites/update_keychains',function(data){
				for (var i=0;i<data.length;i++){
					$(".keychains").html('<option value="'+data[i].id+'">'+data[i].name+'</option>');
				}
			},'json');
		}
	</script>
</div>
<!-- END MAIN CONTENT -->