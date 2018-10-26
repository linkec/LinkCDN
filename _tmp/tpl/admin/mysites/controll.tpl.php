<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-07-01 18:39:04

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">控制面板 - <?=$site['domain']?></h1>
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
	<div class="fixed-fluid">
					    <div class="fixed-sm-200 fixed-md-250 pull-sm-left" id="float_menu">
					        <div class="panel">
					            <p class="pad-hor mar-top text-main text-bold">主机名</p>
								<select class="form-control" onchange="location='/mysites/controll/<?=$site_id?>/'+this.value;">
									<?php foreach($records as $k=>$v){ ?>
									<option value="<?=$k?>" <?=ifselected($k,$host,'str')?>><?=$k?>.<?=$site['domain']?></option>
									<?php } ?>
								</select>
					            <p class="pad-hor mar-top text-main text-bold">快速导航</p>
					            <div class="list-group bg-trans pad-btm bord-btm" style="cursor:pointer;">
					                <a onclick="_goto('source')" class="list-group-item">
										取源设置
					                </a>
					                <a onclick="_goto('https')" class="list-group-item">
										SSL 设置
					                </a>
					                <a onclick="_goto('cache')" class="list-group-item">
										缓存规则
					                </a>
					                <a onclick="_goto('accel')" class="list-group-item">
										加速设置
					                </a>
					                <a onclick="_goto('security')" class="list-group-item">
										安全防护
					                </a>
					                <a onclick="_goto('nodes')" class="list-group-item">
										优选节点
					                </a>
					            </div>
					            <div class="pad-all bord-btm">
					                <a id="save_btn" onclick="save_settings()" class="btn btn-block btn-primary disabled">保存</a>
					                <a href="/mysites/records/<?=$site_id?>" class="btn btn-block btn-default">返回</a>
					            </div>
					        </div>
					    </div>
					    <div class="fluid" style="margin-left:270px;">
					        <div class="panel">
					            <div class="panel-body">
									<form id="setting_form" class="form-horizontal" action="/mysites/save_settings" onsubmit="return false;">
									<input type="hidden" name="site_id" value="<?=$site_id?>">
									<input type="hidden" name="host" value="<?=$host?>">
					                <h1 class="page-header text-overflow pad-btm" id="source">取源设置</h1>
					                <hr class="hr-sm">
									<fieldset>
										<div class="form-group">
											<label class="col-md-2 control-label">回源配置</label>
											<div class="col-md-8 control-panel">
											<table class="table">
												<thead>
													<tr>
														<th>回源地址</th>
														<th>端口号</th>
														<th>线路类别</th>
														<th>权重</th>
														<th>最大失败数</th>
														<th>静默时间（s）</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($records[$host] as $vv){ ?>
													<tr>
														<td><input type="text" disabled="disabled" class="form-control dns_value" value="<?=$vv['dns_value']?>"></td>
														<td><input type="text" name="source[<?=$vv['id']?>][cdn_port]" class="form-control cdn_ports" value="<?=$vv['cdn_port']?>"></td>
														<td><select class="form-control" name="source[<?=$vv['id']?>][cdn_type]" style="width:100px;">
																<option value="main" <?=ifselected('main',$vv['cdn_type'],'str')?>>主线路</option>
																<option value="backup" <?=ifselected('backup',$vv['cdn_type'],'str')?>>备用</option>
																<option value="down" <?=ifselected('down',$vv['cdn_type'],'str')?>>停用</option>
															</select>
														</td>
														<td><input type="text" name="source[<?=$vv['id']?>][cdn_weight]" class="form-control" value="<?=$vv['cdn_weight']?>"></td>
														<td><input type="text" name="source[<?=$vv['id']?>][cdn_fails]" class="form-control" value="<?=$vv['cdn_fails']?>"></td>
														<td><input type="text" name="source[<?=$vv['id']?>][cdn_wait]" class="form-control" value="<?=$vv['cdn_wait']?>"></td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">回源协议</label>
											<div class="col-md-10 input-sm">
												<input id="source_http" class="magic-checkbox" type="radio" name="source_protocol" value="http" <?=ifchecked($host_settings['source_protocol'],'http','str')?> onchange="if(this.checked){$('.cdn_ports').val('80')}">
												<label for="source_http">HTTP</label>
												<input id="source_https" class="magic-checkbox" type="radio" name="source_protocol" value="https" <?=ifchecked($host_settings['source_protocol'],'https','str')?> onchange="if(this.checked){$('.cdn_ports').val('443')}">
												<label for="source_https">HTTPS</label>
											</div>
										</div>
									</fieldset>
									<h1 class="page-header text-overflow pad-btm" id="https">HTTPS (SSL) 设置 </h1>
					                <hr class="hr-sm">
									<fieldset class="">
										<div class="form-group">
											<label class="col-md-2 control-label">HTTPS</label>
											<div class="col-md-10 input-sm">
					                            <input id="https_switch" class="magic-checkbox" type="checkbox" name="https_switch" <?=ifchecked($host_settings['https_switch'],1,'int')?> value="1" onchange="if(!this.checked){$('#https_only,#http2_switch').prop('checked',false);$('#https_only').attr('disabled','disabled');}else{$('#http2_switch').prop('checked',true);$('#https_only').removeAttr('disabled');}">
					                            <label for="https_switch">启用</label>
					                            <input id="https_only" class="magic-checkbox" type="checkbox" name="https_only" <?=ifchecked($host_settings['https_only'],1,'int')?> value="1" <?=$host_settings['https_switch'] ? '' : 'disabled="disabled"'?>>
					                            <label for="https_only">仅限HTTPS访问</label>
					                            <input id="http2_switch" class="magic-checkbox" type="checkbox" name="http2_switch" <?=ifchecked($host_settings['https_switch'],1,'int')?> value="1" disabled="disabled">
					                            <label for="http2_switch">启用HTTP2</label>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">密钥选择</label>
											<div class="col-md-2 control-panel">
												<select class="form-control keychains" name="keychains">
													<option value="0">请选择对应的密钥</option>
													<?php foreach($certs as $vvv){ ?>
													<option value="<?=$vvv['id']?>" <?=ifselected($host_settings['keychains'],$vvv['id'],'int')?>><?=$vvv['name']?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-md-2 control-panel">
												<a class="btn btn-primary" onclick="manage_keychains();">管理密钥</a>
											</div>
										</div>
									</fieldset>
									<h1 class="page-header text-overflow pad-btm" id="cache">缓存设置
										<div class="pull-right">
											<a class="btn btn-danger" onclick="purge_cache('<?=$site['id']?>','<?=$k?>');">缓存清理</a>
										</div>
									</h1>
					                <hr class="hr-sm">
									<fieldset class="">
										<div class="form-group">
											<label class="col-md-2 control-label">浏览器缓存</label>
											<div class="col-md-1 input-sm">
					                            <input id="browser_cache_switch" class="magic-checkbox" type="checkbox" name="browser_cache_switch" <?=ifchecked($host_settings['browser_cache_switch'],1,'int')?> value="1" >
					                            <label for="browser_cache_switch">启用</label>
											</div>
											<div class="col-md-1">
												<input class="form-control" name="browser_cache_time" value="<?=$host_settings['browser_cache_time']?>" type="text">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="browser_cache_unit">
													<option value="s" <?=ifselected($host_settings['browser_cache_unit'],'s','str')?>>秒</option>
													<option value="m" <?=ifselected($host_settings['browser_cache_unit'],'m','str')?>>分</option>
													<option value="h" <?=ifselected($host_settings['browser_cache_unit'],'h','str')?>>时</option>
													<option value="d" <?=ifselected($host_settings['browser_cache_unit'],'d','str')?>>天</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">缓存JS/CSS/图片</label>
											<div class="col-md-1 input-sm">
					                            <input id="statics_cache_switch" class="magic-checkbox" type="checkbox" name="statics_cache_switch" <?=ifchecked($host_settings['statics_cache_switch'],1,'int')?> value="1" >
					                            <label for="statics_cache_switch">启用</label>
											</div>
											<div class="col-md-1">
													<input class="form-control" value="<?=$host_settings['statics_cache_time']?>" type="text" name="statics_cache_time">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="statics_cache_unit">
													<option value="s" <?=ifselected($host_settings['statics_cache_unit'],'s','str')?>>秒</option>
													<option value="m" <?=ifselected($host_settings['statics_cache_unit'],'m','str')?>>分</option>
													<option value="h" <?=ifselected($host_settings['statics_cache_unit'],'h','str')?>>时</option>
													<option value="d" <?=ifselected($host_settings['statics_cache_unit'],'d','str')?>>天</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">缓存静态HTML</label>
											<div class="col-md-1 input-sm">
					                            <input id="html_cache_switch" class="magic-checkbox" type="checkbox" name="html_cache_switch" <?=ifchecked($host_settings['html_cache_switch'],1,'int')?> value="1" >
					                            <label for="html_cache_switch">启用</label>
											</div>
											<div class="col-md-1">
												<input class="form-control" value="<?=$host_settings['html_cache_time']?>" type="text" name="html_cache_time">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="html_cache_unit">
													<option value="s" <?=ifselected($host_settings['html_cache_unit'],'s','str')?>>秒</option>
													<option value="m" <?=ifselected($host_settings['html_cache_unit'],'m','str')?>>分</option>
													<option value="h" <?=ifselected($host_settings['html_cache_unit'],'h','str')?>>时</option>
													<option value="d" <?=ifselected($host_settings['html_cache_unit'],'d','str')?>>天</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">缓存首页</label>
											<div class="col-md-1 input-sm">
					                            <input id="index_cache_switch" class="magic-checkbox" type="checkbox" name="index_cache_switch" <?=ifchecked($host_settings['index_cache_switch'],1,'int')?> value="1" >
					                            <label for="index_cache_switch">启用</label>
											</div>
											<div class="col-md-1">
												<input class="form-control" value="<?=$host_settings['index_cache_time']?>" type="text" name="index_cache_time">
											</div>
											<div class="col-md-1">
												<select class="form-control" name="index_cache_unit">
													<option value="s" <?=ifselected($host_settings['index_cache_unit'],'s','str')?>>秒</option>
													<option value="m" <?=ifselected($host_settings['index_cache_unit'],'m','str')?>>分</option>
													<option value="h" <?=ifselected($host_settings['index_cache_unit'],'h','str')?>>时</option>
													<option value="d" <?=ifselected($host_settings['index_cache_unit'],'d','str')?>>天</option>
												</select>
											</div>
										</div>
									</fieldset>
									<h1 class="page-header text-overflow pad-btm" id="accel">加速设置</h1>
					                <hr class="hr-sm">
									<fieldset>
										<div class="form-group">
											<label class="col-md-2 control-label">页面加速</label>
											<div class="col-md-6">
												<div class="col-md-3 input-sm" rel="tooltip" data-placement="top" data-original-title="自动启用所有适用的优化项，让网页加载速度最快">
													<input id="pagespeed_mode_0" class="magic-checkbox" type="radio" name="pagespeed_mode" <?=ifchecked($host_settings['pagespeed_mode'],0,'int')?> value="0" onclick="check_ps_mode('<?=$k?>');">
													<label for="pagespeed_mode_0">关闭</label>
												</div>
												<div class="col-md-3 input-sm" rel="tooltip" data-placement="top" data-original-title="自动启用所有适用的优化项，让网页加载速度最快">
													<input id="pagespeed_mode_1" class="magic-checkbox" type="radio" name="pagespeed_mode" <?=ifchecked($host_settings['pagespeed_mode'],1,'int')?> value="1" onclick="check_ps_mode('<?=$k?>');">
													<label for="pagespeed_mode_1">极速模式</label>
												</div>
												<div class="col-md-3 input-sm" rel="tooltip" data-placement="top" data-original-title="极限压缩所有适用的资源，最大限度节约宽带">
													<input id="pagespeed_mode_2" class="magic-checkbox" type="radio" name="pagespeed_mode" <?=ifchecked($host_settings['pagespeed_mode'],2,'int')?> value="2" onclick="check_ps_mode('<?=$k?>');">
													<label for="pagespeed_mode_2">压缩模式</label>
												</div>
												<div class="col-md-3 input-sm" rel="tooltip" data-placement="top" data-original-title="自行选择启用哪些优化项">
													<input id="pagespeed_mode_3" class="magic-checkbox" type="radio" name="pagespeed_mode" <?=ifchecked($host_settings['pagespeed_mode'],3,'int')?> value="3" onclick="check_ps_mode('<?=$k?>');">
													<label for="pagespeed_mode_3">自选模式</label>
												</div>
												<div class="col-md-3 ps_diy input-sm" rel="tooltip" data-placement="top" data-original-title="对网页加载的js元素进行压缩合并等优化">
													<input id="pagespeed_page" class="magic-checkbox" type="checkbox" name="pagespeed_page" <?=$host_settings['pagespeed_mode']!=3 ? 'disabled="disabled"' : 'disabled="disabled" checked="checked"'?> value="1" >
													<label for="pagespeed_page">页面优化</label>
												</div>
												<div class="col-md-3 ps_diy input-sm" rel="tooltip" data-placement="top" data-original-title="对网页加载的js元素进行压缩合并等优化">
													<input id="pagespeed_js" class="magic-checkbox" type="checkbox" name="pagespeed_js" <?=$host_settings['pagespeed_mode']!=3 ? 'disabled="disabled"' : ''?> <?=ifchecked($host_settings['pagespeed_js'],1,'int')?> value="1" >
													<label for="pagespeed_js">JS优化</label>
												</div>
												<div class="col-md-3 ps_diy input-sm" rel="tooltip" data-placement="top" data-original-title="对网页加载的js元素进行压缩合并等优化2">
													<input id="pagespeed_css" class="magic-checkbox" type="checkbox" name="pagespeed_css" <?=$host_settings['pagespeed_mode']!=3 ? 'disabled="disabled"' : ''?> <?=ifchecked($host_settings['pagespeed_css'],1,'int')?> value="1" >
													<label for="pagespeed_css">CSS优化</label>
												</div>
												<div class="col-md-3 ps_diy input-sm" rel="tooltip" data-placement="top" data-original-title="对网页加载的js元素进行压缩合并等优化3">
													<input id="pagespeed_image" class="magic-checkbox" type="checkbox" name="pagespeed_image" <?=$host_settings['pagespeed_mode']!=3 ? 'disabled="disabled"' : ''?> <?=ifchecked($host_settings['pagespeed_image'],1,'int')?> value="1" >
													<label for="pagespeed_image">图片优化</label>
												</div>
											</div>
										</div>
										<!--<div class="form-group">
											<label class="col-md-2 control-label">静态资源托管</label>
											<div class="col-md-6">
												<div class="col-md-3">
													<label class="checkbox-inline">
														  <input type="checkbox" class="checkbox" name="static_cachefly" <?=ifchecked($host_settings['static_cachefly'],1,'int')?> value="1">
														  <span>启用</span>
													</label>
												</div>
											</div>
										</div>-->
										<div class="form-group">
											<label class="col-md-2 control-label">资源压缩</label>
											<div class="col-md-6">
												<div class="col-md-3 input-sm">
													<input id="static_compress_gzip" class="magic-checkbox" type="checkbox" name="static_compress_gzip" onchange="if(this.checked){$('#static_compress_brotli').prop('checked',false)}" <?=ifchecked($host_settings['static_compress_gzip'],1,'int')?> value="1" >
													<label for="static_compress_gzip">Gzip</label>
												</div>
												<div class="col-md-3 input-sm">
													<input id="static_compress_brotli" class="magic-checkbox" type="checkbox" name="static_compress_brotli" onchange="if(this.checked){$('#static_compress_gzip').prop('checked',false)}" <?=ifchecked($host_settings['static_compress_brotli'],1,'int')?> value="1" >
													<label for="static_compress_brotli">Brotli</label>
												</div>
											</div>
										</div>
									</fieldset>
									<h1 class="page-header text-overflow pad-btm" id="security">安全防护</h1>
					                <hr class="hr-sm">
									<fieldset>
										<div class="form-group">
											<label class="col-md-2 control-label">防 CC 攻击</label>
											<div class="col-md-6 input-sm">
												<div class="col-md-3">
													<input id="anti_cc_0" class="magic-checkbox" name="anti_cc" type="radio" <?=ifchecked($host_settings['anti_cc'],0,'int')?> value="0" >
													<label for="anti_cc_0">关闭</label>
												</div>
												<div class="col-md-3">
													<input id="anti_cc_1" class="magic-checkbox" name="anti_cc" type="radio" <?=ifchecked($host_settings['anti_cc'],1,'int')?> value="1" >
													<label for="anti_cc_1">智能模式</label>
												</div>
												<div class="col-md-3">
													<input id="anti_cc_2" class="magic-checkbox" name="anti_cc" type="radio" <?=ifchecked($host_settings['anti_cc'],2,'int')?> value="2" >
													<label for="anti_cc_2">强制开启</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">WAF 基础</label>
											<div class="col-md-8 input-sm">
												<div class="col-md-3">
													<input class="magic-checkbox" type="checkbox" disabled="disabled" checked value="1" >
													<label>启用</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">WAF 高级</label>
											<div class="col-md-8 input-sm">
												<div class="col-md-3">
													<input id="waf_pro" class="magic-checkbox" type="checkbox" name="waf_pro" <?=ifchecked($host_settings['waf_pro'],1,'int')?> value="1" onchange="check_addon(<?=$site_id?>)">
													<label for="waf_pro">启用</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Anti-DDOS 基础</label>
											<div class="col-md-8 input-sm">
												<div class="col-md-3">
													<input class="magic-checkbox" type="checkbox" disabled="disabled" checked value="1" >
													<label>启用</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Anti-DDOS 高级</label>
											<div class="col-md-8 input-sm">
												<div class="col-md-3">
													<input id="antiddos_pro" class="magic-checkbox" type="checkbox" name="antiddos_pro" <?=ifchecked($host_settings['antiddos_pro'],1,'int')?> value="1" onchange="check_addon(<?=$site_id?>)">
													<label for="antiddos_pro">启用</label>
												</div>
											</div>
										</div>
									</fieldset>
									<h1 class="page-header text-overflow pad-btm" id="nodes">优选节点</h1>
					                <hr class="hr-sm">
									<fieldset>
										<div class="form-group">
											<label class="col-md-2 control-label">高级设置</label>
											<div class="col-md-8 input-sm">
												<div class="col-md-3">
													<input id="g_high" class="magic-checkbox" type="checkbox" name="node_group[]" value="1" <?=in_array(1,explode(',',$host_settings['node_group'])) ? 'checked':''?>>
													<label for="g_high">高速线路（0.2 元 / G）</label>
												</div>
												<div class="col-md-3">
													<input id="g_ultra" class="magic-checkbox" type="checkbox" name="node_group[]" value="2" <?=in_array(2,explode(',',$host_settings['node_group'])) ? 'checked':''?>>
													<label for="g_ultra">极速线路（0.6 元 / G）</label>
												</div>
												<div class="col-md-3">
													<input id="g_premium" class="magic-checkbox" type="checkbox" name="node_group[]" value="3" <?=in_array(3,explode(',',$host_settings['node_group'])) ? 'checked':''?>>
													<label for="g_premium">旗舰线路（1.2 元 / G）</label>
												</div>
												<div class="col-md-3">
													<input id="g_anti" class="magic-checkbox" type="checkbox" name="node_group[]" value="4" <?=ifchecked($host_settings['antiddos_pro'],1,'int')?> disabled>
													<label for="g_anti">高防线路（0.4 元 / G）</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">基本设置</label>
											<div class="col-md-8 input-sm">
												<div class="col-md-3">
													<input class="magic-checkbox" type="checkbox" checked disabled>
													<label>中间源链路优化节点（免费）</label>
												</div>
												<div class="col-md-3">
													<input class="magic-checkbox" type="checkbox" checked disabled>
													<label>中间源安全防御节点（免费）</label>
												</div>
												<div class="col-md-3">
													<input class="magic-checkbox" type="checkbox" checked disabled>
													<label>基础防御节点（0.15 元 / G）</label>
												</div>
											</div>
										</div>
									</fieldset>
									</form>
					            </div>
					        </div>
					    </div>
					</div>
</div>
		<script>
		function check_ps_mode(host){
			var form = $('#setting_form')[0];
			if(form.pagespeed_mode.value==3){
				$('#pagespeed_page').prop('checked',true);
				$('#pagespeed_js').removeAttr('disabled');
				$('#pagespeed_css').removeAttr('disabled');
				$('#pagespeed_image').removeAttr('disabled');
			}else{
				$('#pagespeed_js').attr('disabled','disabled');
				$('#pagespeed_css').attr('disabled','disabled');
				$('#pagespeed_image').attr('disabled','disabled');
				$('#pagespeed_page').prop('checked',false);
				$('#pagespeed_js').prop('checked',false);
				$('#pagespeed_css').prop('checked',false);
				$('#pagespeed_image').prop('checked',false);
			}
		}
		var pagefunction = function(){
			
		}
		function check_addon(site_id){
			param = '';
			param2 = '';
			// var antiddos = false;
			// var waf = false;
			if($('#antiddos_pro')[0].checked){
				param = 'antiddos_pro';
			}
			if($('#waf_pro')[0].checked){
				param += ',waf_pro';
			}
			if(param){
				$.get('/mysites/check_addon/'+site_id+'/'+param,function(data){
					if(data.antiddos_pro>=0 && data.antiddos_pro<Date.now()){
						$('#antiddos_pro').prop('checked',false);
						$('#g_anti').prop('checked',false);
						// antiddos = true;
						param2 = 'antiddos_pro';
					}
					if(data.waf_pro>=0 && data.waf_pro<Date.now()){
						$('#waf_pro').prop('checked',false);
						// waf = true;
						param2 = 'waf_pro';
					}
					if(param2){
						$.get('/mysites/buy_addon/'+site_id+'/'+param2,function(html){
							$('#remoteModal div.modal-content').html(html);
							$('#remoteModal').modal('show');
						});
					}
				},'json');
			}
			if(!param2){
				if($('#antiddos_pro').prop('checked')){
					$('#g_anti').prop('checked',true);
				}else{
					$('#g_anti').prop('checked',false);
				}
			}
		}
		function save_settings(obj){
			var form = $('#setting_form');
			// console.log(form);
			$('button').attr('disabled',"true");
			  $.ajax({
					url: form.attr('action'),
					type: "POST",
					data: form.serialize(),
					dataType: 'json',
					success: function (data) {
						if(data.status=='success'){
							$.niftyNoty({
								type: 'success',
								container: 'floating',
								html: '<strong>操作成功</strong> '+data.msgs,
								closeBtn: false,
								floating: {
									position: "top-right",
									animationIn: "bounceInDown",
									animationOut: "fadeOut"
								},
								focus: true,
								timer: 5000
							});
							$('#remoteModal div.modal-content').empty();
							$('#remoteModal').modal('hide');
							if(data.script){
								eval(data.script);
							}
							$('#save_btn').addClass('disabled');
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
						$('button').removeAttr('disabled',"true");
					},
					error: function (jqXhr, textStatus, errorThrown) {
						$('button').removeAttr('disabled',"true");
						
						$.niftyNoty({
							type: 'danger',
							container: 'floating',
							html: '<strong>出现错误了</strong> 出现未知的错误，请重试或联系管理员。',
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
			  });
		  event.preventDefault();
		  return false;
		}
		</script>
	<script type="text/javascript" src="<?=$static_url?>/js/scrolltrack.js"></script>
	<script>
		function manage_keychains(){
			$.get('/mysites/manage_keychains',function(data){$('#remoteModal div.modal-content').html(data)});
			$('#remoteModal').modal('show');
		}
		function purge_cache(id,host){
			$.get('/mysites/purge_cache/'+id+'/'+host,function(data){$('#remoteModal div.modal-content').html(data)});
			$('#remoteModal').modal('show');
		}
		function add_cache_rule(){
			
		}
		function add_keychains(){
			$.get('/mysites/add_keychains',function(data){$('#remoteModal div.modal-content').html(data)});
			$('#remoteModal').modal('show');
		}
		function update_keychains(){
			$.get('/mysites/update_keychains',function(data){
				for (var i=0;i<data.length;i++){
					$(".keychains").html('<option value="'+data[i].id+'">'+data[i].name+'</option>');
				}
			},'json');
		}
		// window.onscroll = function (e) {
			// console.log();
		// }
		$('#setting_form').bind('keyup',function(e){
			$('#save_btn').removeClass('disabled');
			// var code = e.keyCode || e.which;
			// console.log(code);
		});
		$('#setting_form').bind('change',function(){
			$('#save_btn').removeClass('disabled');
		});
		$("#float_menu").scrollTrack();
		function _goto(id){
			$("body").scrollTop($('#'+id)[0].offsetTop-50);
		}
		$('.keychains').select2();
	</script>
</div>
<!-- END MAIN CONTENT -->