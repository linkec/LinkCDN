<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">节点管理</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!-- MAIN CONTENT -->
<div id="page-content">
	
	<div class="panel panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">节点列表</h3>
		</div>
		<div class="panel-body">
			<div class="pad-btm form-inline">
				<div class="row">
					<div class="col-sm-12 table-toolbar-left">
						<a class="btn btn-purple" onclick="add_node()"><i class="demo-pli-add"></i> 添加节点</a>
						<a class="btn btn-purple" target="_blank" href="/admin/nodes/reloaddns">重载DNS</a>
						<a class="btn btn-purple" onclick="cleancache()">刷新缓存</a>
						<a class="btn btn-purple" onclick="rebuild()">重载节点</a>
						<a class="btn btn-purple" onclick="update_api()">更新API</a>
						<a class="btn btn-purple" onclick="check_ver()">获取API版本</a>
						<a class="btn btn-purple" onclick="check_ngtest()">Nginx配置测试</a>
						<a class="btn btn-purple" onclick="check_ngstatus()">Nginx启动状态</a>
						<a class="btn btn-purple" onclick="docmd()">执行命令</a>
					</div>
					<div class="col-sm-12 table-toolbar-left">
						<a class="btn btn-purple" onclick="smart_select(1)">1</a>
						<a class="btn btn-purple" onclick="smart_select(2)">2</a>
						<a class="btn btn-purple" onclick="smart_select(3)">3</a>
						<a class="btn btn-purple" onclick="smart_select(4)">4</a>
						<a class="btn btn-purple" onclick="smart_select(5)">5</a>
						<a class="btn btn-purple" onclick="smart_select(6)">6</a>
						<a class="btn btn-purple" onclick="smart_select(1);smart_select(2);smart_select(3);smart_select(4);smart_select(5);">全部CDN</a>
						<a class="btn btn-purple" onclick="smart_select(99)">故障</a>
						<a class="btn btn-purple" onclick="smart_select(0)">取消</a>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-vcenter">
					<thead>
						<tr>
							<th onclick="reverse_check()" style="cursor:pointer;">反选</th>
							<th>节点名</th>
							<th>命令执行缓冲区</th>
							<th>所属套餐</th>
							<th>DNS权重</th>
							<th>状态</th>
							<th>负载</th>
							<th>内存</th>
							<th>连接数</th>
							<th>网卡</th>
							<th class="text-right">操作</th>
						</tr>
					</thead>
					<tbody>
						<!--#foreach($nodes as $v){#-->
						<tr id="node_{$v['id']}" onclick="$('#node_cb_{$v['id']}').prop('checked',!$('#node_cb_{$v['id']}').prop('checked'))">
							<td><input type="checkbox" class="magic-checkbox node_g{$v['area']}" name="node_ids[]" value="{$v['id']}" id="node_cb_{$v['id']}"><label for="node_cb_{$v['id']}"></label></td>
							<td>{$v['id']}-{$v['name']}<br>{$v['hostname']}</td>
							<td><pre id="ver_{$v['id']}" style="color:white;background:black;font-size:12px;margin: 0px;"> - </pre></td>
							<td>{$v['area']}<br>{$v['views']}</td>
							<td>{$v['weight']}</td>
							<td>{$v['status']}<br>{$v['check_time']} 秒前</td>
							<td><a onclick="seetop('{$v['id']}')">{$v['load_avg']}</a></td>
							<td>空闲：{#get_size($v['mem_free'])#} <br>总共：{#get_size($v['mem_total'])#}</td>
							<td>{$v['connections']}</td>
							<td><font color="red"><i class="ion-arrow-up-c"></i> {$v['tx_result']}/s </font> | <font color="green"><i class="ion-arrow-down-c"></i> {$v['rx_result']}/s </font><br>{$v['ip']}</td>
							<td><a class="btn btn-xs btn-primary" onclick="edit_node({$v['id']})">编辑</a></td>
						</tr>
						<!--#if($act=='nglist'){#-->
						<tr>
							<td colspan="3">{$v['ng_status']}</td>
							<td colspan="5">{$v['ng_configtest']}</td>
						</tr>
						<!--#}#-->
						<!--#}#-->
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- END MAIN CONTENT -->
<script>
var nodes = {#json_encode($nodes_simple)#};
function smart_select(gid){
	console.log(gid);
	$("input[name='node_ids[]']").each(function(){
		// console.log($(this));
		if(gid==0){
			$(this).prop('checked',false);
		}else if($(this).hasClass('node_g'+gid)){
			$(this).prop('checked',true);
		}
	  });
}
function edit_node(id){
	$.get('/admin/nodes/edit/'+id,function(html){$('#remoteModal div.modal-content').html(html)});
	$('#remoteModal').modal('show');
}
function reverse_check(){
	$("input[name='node_ids[]']").each(function(){
		$(this).prop('checked',!$(this).prop('checked'));
	  });
}
function check_ver(){
	$("input[name='node_ids[]']:checked").each(function(){
		var node_id = $(this).val();
		var node_ip = nodes[node_id].ip;
		var node_port = nodes[node_id].port;
		var node_pwd = nodes[node_id].password;
		
		$('#ver_'+node_id).html('<i class="fa fa-refresh"></i>');
		$.ajax({
			url:'https://raycdnapi.file002.com/'+node_ip+':'+node_port+'/getversion',
			dataType:'json',
			success:function(data){
				$('#ver_'+node_id).html(data.data);
			},
			error:function(){
				$('#ver_'+node_id).html('failed');
			}
		})
	}) 
}
function check_ngtest(){
	$("input[name='node_ids[]']:checked").each(function(){
		var node_id = $(this).val();
		var node_ip = nodes[node_id].ip;
		var node_port = nodes[node_id].port;
		var node_pwd = nodes[node_id].password;
		
		$('#ver_'+node_id).html('<i class="fa fa-refresh"></i>');
		$.ajax({
			url:'https://raycdnapi.file002.com/'+node_ip+':'+node_port+'/getngtest',
			dataType:'json',
			success:function(data){
				data.data = data.data.replace('syntax is ok','<span style="color:green;font-weight:bolder;">syntax is ok</span>');
				data.data = data.data.replace('successful','<span style="color:green;font-weight:bolder;">successful</span>');
				$('#ver_'+node_id).html(data.data);
			},
			error:function(){
				$('#ver_'+node_id).html('failed');
			}
		})
	})
}
function check_ngstatus(){
	$("input[name='node_ids[]']:checked").each(function(){
		var node_id = $(this).val();
		var node_ip = nodes[node_id].ip;
		var node_port = nodes[node_id].port;
		var node_pwd = nodes[node_id].password;
		
		$('#ver_'+node_id).html('<i class="fa fa-refresh"></i>');
		$.ajax({
			url:'https://raycdnapi.file002.com/'+node_ip+':'+node_port+'/getngstatus',
			dataType:'json',
			success:function(data){
				data.data = data.data.replace('running','<span style="color:green;font-weight:bolder;">running</span>');
				$('#ver_'+node_id).html(data.data);
			},
			error:function(){
				$('#ver_'+node_id).html('failed');
			}
		})
	})
}
function update_api(){
	$("input[name='node_ids[]']:checked").each(function(){
		var node_id = $(this).val();
		var node_ip = nodes[node_id].ip;
		var node_port = nodes[node_id].port;
		var node_pwd = nodes[node_id].password;
		
		$('#ver_'+node_id).html('<i class="fa fa-refresh"></i>');
		$.ajax({
			url:'https://raycdnapi.file002.com/'+node_ip+':'+node_port+'/update',
			dataType:'json',
			success:function(data){
				$('#ver_'+node_id).html(data.status);
			},
			error:function(){
				$('#ver_'+node_id).html('failed');
			}
		})
	})
}
function rebuild(){
	$("input[name='node_ids[]']:checked").each(function(){
		var node_id = $(this).val();
		var node_ip = nodes[node_id].ip;
		var node_port = nodes[node_id].port;
		var node_pwd = nodes[node_id].password;
		
		$('#ver_'+node_id).html('<i class="fa fa-refresh"></i>');
		$.ajax({
			url:'https://raycdnapi.file002.com/'+node_ip+':'+node_port+'/rebuild',
			dataType:'json',
			success:function(data){
				data.data = data.data.replace('syntax is ok','<span style="color:green;font-weight:bolder;">syntax is ok</span>');
				data.data = data.data.replace('successful','<span style="color:green;font-weight:bolder;">successful</span>');
				$('#ver_'+node_id).html(data.data);
			},
			error:function(){
				$('#ver_'+node_id).html('failed');
			}
		})
	})
}
function cleancache(){
	$("input[name='node_ids[]']:checked").each(function(){
		var node_id = $(this).val();
		var node_ip = nodes[node_id].ip;
		var node_port = nodes[node_id].port;
		var node_pwd = nodes[node_id].password;
		
		$('#ver_'+node_id).html('<i class="fa fa-refresh"></i>');
		$.ajax({
			url:'https://raycdnapi.file002.com/'+node_ip+':'+node_port+'/purge',
			dataType:'json',
			success:function(data){
				$('#ver_'+node_id).html(data.data);
			},
			error:function(){
				$('#ver_'+node_id).html('failed');
			}
		})
	})
}
function docmd(){
	var cmd = prompt("请输入命令:","php raycdn.php status");
	if(cmd){
		$("input[name='node_ids[]']:checked").each(function(){
			var node_id = $(this).val();
			var node_ip = nodes[node_id].ip;
			var node_port = nodes[node_id].port;
			var node_pwd = nodes[node_id].password;
			
			$('#ver_'+node_id).html('<i class="fa fa-refresh"></i>');
			$.ajax({
				url:'https://raycdnapi.file002.com/'+node_ip+':'+node_port+'/cmd?cmd='+cmd+'',
				dataType:'json',
				success:function(data){
					$('#ver_'+node_id).html(data.data);
				},
				error:function(){
					$('#ver_'+node_id).html('failed');
				}
			})
		})
	}
}
function seetop(id){
	$.get('/nodes/top/'+id,function(html){
		$('#remoteModal div.modal-content').html(html)
	});
	$('#remoteModal').modal('show');
}
</script>