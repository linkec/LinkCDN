<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">节点设置</h4>
</div>
<form id="save_form" action="/admin/nodes/save" onsubmit="return false;">
<input type="hidden" name="node_id" value="{$node_id}">
<div class="modal-body">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">节点名称</label>
					<input type="text" class="form-control" name="name" value="{$node['name']}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">节点区域</label>
					<input type="text" class="form-control" name="area" value="{$node['area']}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">IP</label>
					<input type="text" class="form-control" name="ip" value="{$node['ip']}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">线路权重</label>
					<input type="text" class="form-control" name="views" value="{$node['views']}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">DNS权重</label>
					<input type="text" class="form-control" name="weight" value="{$node['weight']}">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		关闭
	</button>
	<button type="submit" class="btn btn-primary" onclick="add_keychain()">
		保存
	</button>
</div>
</form>
<script>
function add_keychain(){
	var form = $('#save_form');
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