<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">{$site['domain']}</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!-- MAIN CONTENT -->
<div id="page-content">
	
	<div class="panel panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">域名解析</h3>
		</div>
		<div class="panel-body">
			<div class="pad-btm form-inline">
				<div class="row">
					<div class="col-sm-6 table-toolbar-left">
						<a class="btn btn-purple" id="add_record_btn"><i class="demo-pli-add"></i> 添加记录</a>
						<a class="btn btn-primary" href="/admin/mysites/controll/{$site_id}">控制面板</a>
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
			<div class="">
				<table class="table table-vcenter record-list">
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
						<tr id="record_{$v['id']}" class="{$v['change_status']}" data-rcd-id="{$v['id']}">
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
							<!--#if($v['dns_type']=='A' || $v['dns_type']=='CNAME'){#-->
							<input id="{$v['id']}-switch" class="toggle-switch" type="checkbox" {#ifchecked($v['cdn_status'],'on','str')#} disabled="disabled" onchange="if(this.checked){$(this).attr('checked','checked')}else{$(this).removeAttr('checked')}">
							<label for="{$v['id']}-switch"></label>
							<!--#}#-->
							</td>
							<td style="width:90px;"><a class="btn btn-xs btn-primary" onclick="edit_record({$v['id']})">编辑</a> <a class="btn btn-xs btn-danger" onclick="delete_record({$v['id']})">删除</a></td>
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
var ori = new Array();
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
function check_mx(obj,id){
	// alert(obj.value);
	if(obj.value=='MX'){
		$('#record_'+id+' td')[5].innerHTML='<input type="text"class="form-control input-sm"value="">';//记录
	}else{
		$('#record_'+id+' td')[5].innerHTML='';
	}
}
function record_template(id){
	<!--#if($site['cdn_type']=='ns'){#-->
	return '<tr id="record_'+id+'"><td><select class="form-control input-sm" onchange="check_mx(this,'+id+');">'+dns_types()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="select_searchbar">'+dns_lines()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="form-control input-sm">'+dns_ttls()+'</select></td><td>-</td><td class="text-center"><input id="'+id+'-switch" class="toggle-switch" type="checkbox" checked=""><label for="'+id+'-switch"></label></td><td><a class="btn btn-xs btn-primary" onclick="save_record('+id+')">保存</a> <a class="btn btn-xs btn-danger" onclick="cancle_record('+id+')">取消</a></td></tr>';
	<!--#}else{#-->
	return '<tr id="record_'+id+'"><td><select class="form-control input-sm">'+dns_types()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="select_searchbar">'+dns_lines()+'</select></td><td><input type="text"class="form-control input-sm"value=""></td><td><select class="form-control input-sm">'+dns_ttls()+'</select></td><td>创建后自动生成</td><td class="text-center"><input id="'+id+'-switch" class="toggle-switch" type="checkbox" checked=""><label for="'+id+'-switch"></label></td><td><a class="btn btn-xs btn-primary" onclick="save_record('+id+')">保存</a> <a class="btn btn-xs btn-danger" onclick="cancle_record('+id+')">取消</a></td></tr>';
	<!--#}#-->
}
function edit_record(id){
	if(id<0){
		$.niftyNoty({
			type: 'danger',
			container: 'floating',
			html: '<strong>错误</strong> 不能修改默认NS记录',
			closeBtn: false,
			floating: {
				position: "top-right",
				animationIn: "bounceInDown",
				animationOut: "fadeOut"
			},
			focus: true,
			timer: 5000
		});
		return;
	}
	ori[id] = $('#record_'+id).html();
	if($('#record_'+id+' td')[0].innerHTML=='A' || $('#record_'+id+' td')[0].innerHTML=='CNAME'){
		$('#'+id+'-switch').removeAttr('disabled');
	}
	if($('#record_'+id+' td')[0].innerHTML=='MX'){
		$('#record_'+id+' td')[5].innerHTML='<input type="text"class="form-control input-sm"value="'+$('#record_'+id+' td')[5].innerHTML+'">';//mx记录
	}
	$('#record_'+id+' td')[0].innerHTML='<select class="form-control input-sm" onchange="check_mx(this,'+id+');">'+dns_types($('#record_'+id+' td')[0].innerHTML)+'</select>';//记录类型
	$('#record_'+id+' td')[1].innerHTML='<input type="text"class="form-control input-sm"value="'+$('#record_'+id+' td')[1].innerHTML+'">';//主机名
	$('#record_'+id+' td')[2].innerHTML='<select class="form-control input-sm select_searchbar">'+dns_lines($('#record_'+id+' td')[2].innerHTML)+'</select>';//记录线路
	$('#record_'+id+' td')[3].innerHTML='<input type="text"class="form-control input-sm"value="'+$('#record_'+id+' td')[3].innerHTML+'">';//记录
	$('#record_'+id+' td')[4].innerHTML='<select class="form-control input-sm">'+dns_ttls($('#record_'+id+' td')[4].innerHTML)+'</select>';//ttl记录
	<!--#if($site['cdn_type']=='cn'){#-->
	$('#record_'+id+' td')[5].innerHTML=$('#record_'+id+' td')[5].innerHTML;//CNAME记录
	<!--#}#-->
	// $('#record_'+id+' td')[6].innerHTML=$('#record_'+id+' td')[6].innerHTML;//状态
	$('#record_'+id+' td')[7].innerHTML='<a class="btn btn-xs btn-primary" onclick="save_record('+id+')">保存</a> <a class="btn btn-xs btn-danger" onclick="cancle_record('+id+')">取消</a>';//操作
	$('.select_searchbar').select2({width:'100%'});
	$('#record_'+id+' input,select').bind('keyup',function(event){
		if(event.keyCode==13){
			save_record($(event.target).parent().parent()[0].dataset['rcdId']);
			// save_record($(event.target).parent().parent().parent()[0].dataset['rcdId']);
		}
	});
}
function cancle_record(id){
	if(id==0){
		$('#record_'+id).remove();
		$('#add_record_btn').removeClass('disabled');
		return;
	}
	$('#record_'+id).html(ori[id]);
}
function save_record(id){
	//通知服务器保存
	var dns_type = $($('#record_'+id+' td')[0]).find('select').val();
	var dns_host = $($('#record_'+id+' td')[1]).find('input').val();
	var dns_line = $($('#record_'+id+' td')[2]).find('select').val();
	var dns_value = $($('#record_'+id+' td')[3]).find('input').val();
	var dns_ttl = $($('#record_'+id+' td')[4]).find('select').val();
	if(dns_type=='A' || dns_type=='CNAME'){
		var cdn_status = $('#'+id+'-switch').prop('checked') ? 'on' : 'off';
	}else{
		var cdn_status = 'off';
	}
	if(dns_type=='MX'){
		var dns_mx = $($('#record_'+id+' td')[5]).find('input').val();
	}else{
		var dns_mx = '';
	}
	
	$.ajax({
			url: '/admin/mysites/save_record',
			type: "POST",
			data: 'site_id='+{$site_id}+'&record_id='+id+'&dns_type='+dns_type+'&dns_host='+dns_host+'&dns_line='+dns_line+'&dns_value='+dns_value+'&dns_ttl='+dns_ttl+'&dns_mx='+dns_mx+'&cdn_status='+cdn_status,
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
					<!--#if($site['cdn_type']=='ns'){#-->
					$('#record_'+id+' td')[5].innerHTML=dns_mx;//mx
					<!--#}else{#-->
					if(data.cname){
						$('#record_'+id+' td')[5].innerHTML=data.cname;//mx记录
					}
					<!--#}#-->
					$('#'+id+'-switch').attr('disabled','disabled');
					// $('#record_'+id+' td')[6].innerHTML=$('#record_'+id+' td')[6].innerHTML;//状态
					$('#record_'+id+' td')[7].innerHTML='<a class="btn btn-xs btn-primary" onclick="edit_record('+id+')">编辑</a> <a class="btn btn-xs btn-danger" onclick="delete_record('+id+')">删除</a>';//操作
					
					if(data.id){
						//更新记录ID
						$('#record_'+id+' td')[7].innerHTML='<a class="btn btn-xs btn-primary" onclick="edit_record('+data.id+')">编辑</a> <a class="btn btn-xs btn-danger" onclick="delete_record('+data.id+')">删除</a>';//操作
						$('#record_'+id).attr('id','record_'+data.id);
					}
				}else{
					$.niftyNoty({
						type: 'danger',
						container: 'floating',
						html: '<strong>错误</strong> '+data.msg,
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
	$('.select_searchbar').select2({width:'100%'});
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
							url: '/admin/mysites/delete_record',
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
										html: '<strong>错误</strong> '+data.msg,
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
});
// $('.select_searchbar').chosen({width:'100%'});
function go_step3(id){
	$.get('/admin/mysites/go_step3/'+id,function(data){
		if(data.status=='success'){
			location  = '/admin/mysites/add_3/'+id;
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
	},'json');
}
</script>