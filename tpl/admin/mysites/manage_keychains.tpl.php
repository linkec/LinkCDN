<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">密钥管理</h4>
</div>
<div class="modal-body">
	<a class="btn btn-primary" onclick="add_keychains();">添加新的证书</a>
	<hr>
	<table class="table">
		<thead>
			<tr>
				<th>实例名</th>
				<th>过期时间</th>
				<th style="text-align:right;">操作</th>
			</tr>
		</thead>
		<tbody>
			<!--#foreach($certs as $v){#-->
			<tr>
				<td style="padding-bottom: 8px;">{$v['name']}</td>
				<td style="padding-bottom: 8px;">{#date('Y-m-d',$v['certinfo']['validTo_time_t'])#}</td>
				<td style="text-align:right;padding-bottom: 8px;"><a onclick="del_keychain({$v['id']})">删除</a></td>
			</tr>
			<!--#}#-->
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		关闭
	</button>
</div>
<script>
function del_keychain(id){
	$.get('/mysites/delete_keychains/?id='+id,function(data){
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
		if(data.script){
			eval(data.script);
		}
	},'json');
}
</script>