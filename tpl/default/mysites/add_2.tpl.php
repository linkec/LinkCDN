<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div id="page-title">
	<h1 class="page-header text-overflow">添加域名</h1>
</div>
<div id="page-content">
	<div class="col-lg-12">
		<h4 class="text-main pad-btm bord-btm">添加一个新的网站至 RayCDN 进行安全防护和加速</h4>
		<div class="panel">
			<div>
				<div>
					<ul class="wz-nav-off wz-icon-inline wz-classic">
						<li class="col-xs-4 bg-mint">
							<a>
								<span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="demo-pli-information icon-lg"></i></span> 添加域名
							</a>
						</li>
						<li class="col-xs-4 bg-mint active">
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
						<div class="progress-bar progress-bar-dark" style="width: 66.66%;"></div>
					</div>
				</div>
				<style>
				.record-list tr{
					height:49px;
				}
				</style>
				<div class="panel-body">
					<div class="col-lg-8 col-lg-offset-2 tab-content">
						<div class="tab-pane active" id="tab1">
							<h3><strong>第二步 </strong> - 配置子域名 （{$site['domain']}）
								<span class="pull-right"><a class="btn btn-primary btn-md" id="add_record_btn">添加子域名</a></span>
							</h3><br>
							<div class="alert alert-info fade in">
								<i class="fa-fw fa fa-info"></i>
								<strong>提醒</strong> 请确认IP设置无误，否则可能影响网站访问！
							</div>
							<div id="domain_alert">
							</div>
							<div class="">
								<table class="table table-vcenter table-striped record-list">
									<thead>
										<tr>
											<th style="width:90px;">类型</th>
											<th style="width:135px;">主机名</th>
											<th style="width:90px;">线路</th>
											<th style="width:90px;">记录值</th>
											<th style="width:90px;">TTL</th>
											<!--#if($site['cdn_type']=='ns'){#-->
											<th style="width:170px;">MX优先级</th>
											<!--#}else{#-->
											<th style="width:170px;">别名</th>
											<!--#}#-->
											<th style="width:90px;" class="text-center">加速与防护</th>
											<th style="width:90px;">操作</th>
										</tr>
									</thead>
									<tbody>
										<!--#foreach($records as $v){#-->
										<tr id="record_{$v['id']}">
											<td style="width:90px;">{$v['dns_type']}</td>
											<td style="width:135px;">{$v['dns_host']}</td>
											<td style="width:90px;">{$v['dns_line']}</td>
											<td style="width:90px;">{$v['dns_value']}</td>
											<td style="width:90px;">{#$custom_ttl[$v['dns_ttl']]#}</td>
											<!--#if($site['cdn_type']=='ns'){#-->
											<td style="width:170px;">{$v['dns_mx']}</td>
											<!--#}else{#-->
											<td style="width:170px;">{$v['dns_cname']}</td>
											<!--#}#-->
											<td style="width:90px;" class="text-center">
											<input id="{$v['id']}-switch" class="toggle-switch" type="checkbox" {#ifchecked($v['cdn_status'],'on','str')#} disabled="disabled" onchange="if(this.checked){$(this).attr('checked','checked')}else{$(this).removeAttr('checked')}">
											<label for="{$v['id']}-switch"></label></td>
											<td style="width:90px;"><a class="btn btn-xs btn-primary" onclick="edit_record({$v['id']})">编辑</a> <a class="btn btn-xs btn-danger" onclick="delete_record({$v['id']})">删除</a></td>
										</tr>
										<!--#}#-->
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer text-right">
					<div class="box-inline">
						<a onclick="go_step3({$site_id})" class="next btn btn-info">下一步</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var ori = '';
function dns_lines(cur){
	arr = [<!--#foreach($_dns_line as $v){#-->'{$v}',<!--#}#-->];
	str = '';
	for(i=0;i<arr.length;i++){
		if(arr[i]==cur){
			str += '<option value="'+arr[i]+'" selected="selected">'+arr[i]+'</option>';
		}else{
			str += '<option value="'+arr[i]+'">'+arr[i]+'</option>';
		}
	}
	return str;
}
function dns_types(cur){
	<!--#if($site['cdn_type']=='ns'){#-->
	arr = [<!--#foreach($_dns_type as $v){#-->'{$v}',<!--#}#-->];
	<!--#}else{#-->
	arr = ['A','CNAME'];
	<!--#}#-->
	str = '';
	for(i=0;i<arr.length;i++){
		if(arr[i]==cur){
			str += '<option value="'+arr[i]+'" selected="selected">'+arr[i]+'</option>';
		}else{
			str += '<option value="'+arr[i]+'">'+arr[i]+'</option>';
		}
	}
	return str;
}
function dns_ttls(cur){
	arr = [<!--#foreach($_dns_ttl as $v){#-->'{$v}',<!--#}#-->];
	str = '';
	for(i=0;i<arr.length;i++){
		if(arr[i]==cur){
			str += '<option value="'+arr[i]+'" selected="selected">'+arr[i]+'</option>';
		}else{
			str += '<option value="'+arr[i]+'">'+arr[i]+'</option>';
		}
	}
	return str;
}
function record_template(id){
	<!--#if($site['cdn_type']=='ns'){#-->
	return '<tr id="record_'+id+'"><td><select class="form-control input-sm">'+dns_types()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="select_searchbar">'+dns_lines()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="form-control input-sm">'+dns_ttls()+'</select></td><td>-</td><td class="text-center"><input id="'+id+'-switch" class="toggle-switch" type="checkbox" checked=""><label for="'+id+'-switch"></label></td><td><a class="btn btn-xs btn-primary" onclick="save_record('+id+')">保存</a> <a class="btn btn-xs btn-danger" onclick="cancle_record('+id+')">取消</a></td></tr>';
	<!--#}else{#-->
	return '<tr id="record_'+id+'"><td><select class="form-control input-sm">'+dns_types()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="select_searchbar">'+dns_lines()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="form-control input-sm">'+dns_ttls()+'</select></td><td>创建后自动生成</td><td class="text-center"><input id="'+id+'-switch" class="toggle-switch" type="checkbox" checked=""><label for="'+id+'-switch"></label></td><td><a class="btn btn-xs btn-primary" onclick="save_record('+id+')">保存</a> <a class="btn btn-xs btn-danger" onclick="cancle_record('+id+')">取消</a></td></tr>';
	<!--#}#-->
}
function edit_record(id){
	ori = $('#record_'+id).html();
	$('#record_'+id+' td')[0].innerHTML='<select class="form-control input-sm">'+dns_types($('#record_'+id+' td')[0].innerHTML)+'</select>';//记录类型
	$('#record_'+id+' td')[1].innerHTML='<input type="text"class="form-control input-sm"value="'+$('#record_'+id+' td')[1].innerHTML+'">';//主机名
	$('#record_'+id+' td')[2].innerHTML='<select class="form-control input-sm select_searchbar">'+dns_lines($('#record_'+id+' td')[2].innerHTML)+'</select>';//记录线路
	$('#record_'+id+' td')[3].innerHTML='<input type="text"class="form-control input-sm"value="'+$('#record_'+id+' td')[3].innerHTML+'">';//记录
	$('#record_'+id+' td')[4].innerHTML='<select class="form-control input-sm">'+dns_ttls($('#record_'+id+' td')[4].innerHTML)+'</select>';//ttl记录
	<!--#if($site['cdn_type']=='ns'){#-->
	$('#record_'+id+' td')[5].innerHTML=$('#record_'+id+' td')[5].innerHTML;//mx记录
	<!--#}else{#-->
	$('#record_'+id+' td')[5].innerHTML=$('#record_'+id+' td')[5].innerHTML;//CNAME记录
	<!--#}#-->
	$('#'+id+'-switch').removeAttr('disabled');
	// $('#record_'+id+' td')[6].innerHTML=$('#record_'+id+' td')[6].innerHTML;//状态
	$('#record_'+id+' td')[7].innerHTML='<a class="btn btn-xs btn-primary" onclick="save_record('+id+')">保存</a> <a class="btn btn-xs btn-danger" onclick="cancle_record('+id+')">取消</a>';//操作
	$(".select_searchbar").select2({ width: '80px' });
}
function cancle_record(id){
	if(id==0){
		$('#record_'+id).remove();
		$('#add_record_btn').removeClass('disabled');
		return;
	}
	$('#record_'+id).html(ori);
}
function save_record(id){
	//通知服务器保存
	var dns_type = $($('#record_'+id+' td')[0]).find('select').val();
	var dns_host = $($('#record_'+id+' td')[1]).find('input').val();
	var dns_line = $($('#record_'+id+' td')[2]).find('select').val();
	var dns_value = $($('#record_'+id+' td')[3]).find('input').val();
	var dns_ttl = $($('#record_'+id+' td')[4]).find('select').val();
	var cdn_status = $('#'+id+'-switch').prop('checked') ? 'on' : 'off';
	
	$.ajax({
			url: '/mysites/save_record',
			type: "POST",
			data: 'site_id='+{$site_id}+'&record_id='+id+'&dns_type='+dns_type+'&dns_host='+dns_host+'&dns_line='+dns_line+'&dns_value='+dns_value+'&dns_ttl='+dns_ttl+'&cdn_status='+cdn_status,
			dataType:'json',
			success: function (data) {
				if(data.status=='success'){
					if(id==0){
						$('#add_record_btn').removeClass('disabled');
					}
					$('#record_'+id+' td')[0].innerHTML=dns_type;//记录类型
					$('#record_'+id+' td')[1].innerHTML=dns_host;//主机名
					$('#record_'+id+' td')[2].innerHTML=dns_line;//记录线路
					$('#record_'+id+' td')[3].innerHTML=dns_value;//记录
					$('#record_'+id+' td')[4].innerHTML=dns_ttl;//ttl记录
					$('#'+id+'-switch').attr('disabled','disabled');
					// $('#record_'+id+' td')[6].innerHTML=$('#record_'+id+' td')[6].innerHTML;//状态
					$('#record_'+id+' td')[7].innerHTML='<a class="btn btn-xs btn-primary" onclick="edit_record('+id+')">编辑</a> <a class="btn btn-xs btn-danger" onclick="delete_record('+id+')">删除</a>';//操作
					if(data.cname){
						$('#record_'+id+' td')[5].innerHTML=data.cname;//mx记录
					}
					if(data.id){
						//更新记录ID
						$('#record_'+id+' td')[7].innerHTML='<a class="btn btn-xs btn-primary" onclick="edit_record('+data.id+')">编辑</a> <a class="btn btn-xs btn-danger" onclick="delete_record('+data.id+')">删除</a>';//操作
						$('#record_'+id).attr('id','record_'+data.id);
						$('#'+id+'-switch').attr('id',data.id+'-switch');
						$('#'+data.id+'-switch').next().attr('for',data.id+'-switch');
					}
				}else{
					$.niftyNoty({
						type: 'danger',
						container: '#domain_alert',
						html: '<strong>错误</strong><br>'+data.msg,
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
function add_record(data){
	if(data){
		$('tbody').append('asd');
	}else{
		$('tbody').append(record_template(0));
	}
	// $('.select_searchbar').chosen({width:'100%'});
	$(".select_searchbar").select2({ width: '80px' });
}
function delete_record(id){
	bootbox.dialog({
		message: "你确定要删除该记录吗？",
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
					$.ajax({
							url: '/mysites/delete_record',
							type: "POST",
							data: 'site_id='+{$site_id}+'&record_id='+id,
							dataType:'json',
							success: function (data) {
								if(data.status=='success'){
									$('#record_'+id).remove();
								}else{
									$.niftyNoty({
										type: 'danger',
										container: 'floating',
										html: '<strong>错误</strong><br>'+data.msg,
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
			}
		}
	});
	//通知服务器删除
}
$('#add_record_btn').bind('click',function(){
	add_record();
	$('#add_record_btn').addClass('disabled');
	// $(".select_searchbar").select2();
});
// $('.select_searchbar').chosen({width:'100%'});
// $(".select_searchbar").select2();
function go_step3(id){
	$.get('/mysites/go_step3/'+id,function(data){
		if(data.status=='success'){
			location  = '/mysites/add_3/'+id;
		}else{
			$.niftyNoty({
				type: 'danger',
				container: 'floating',
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
	},'json');
}
</script>