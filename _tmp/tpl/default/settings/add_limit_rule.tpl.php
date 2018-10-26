<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-04-18 14:38:35

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">添加消费限额</h4>
</div>
<form class="form-horizontal" action="/settings/<?=$act?>" onsubmit="return check_form(this);" method="post">
<?php if($act=='edit_limit_rule'){ ?>
<input type="hidden" name="task" value="update">
<input type="hidden" name="id" value="<?=$id?>">
<?php }else{ ?>
<input type="hidden" name="task" value="add">
<?php } ?>
<div class="modal-body">
	<div class="panel-body">
		<div class="form-group">
			<label class="col-sm-3 control-label" for="demo-hor-inputemail">主域名</label>
			<div class="col-sm-9">
				<?php if($act=='edit_limit_rule'){ ?>
					<?=$rule['domain']?>
				<?php }else{ ?>
				<select class="form-control" onchange="load_host(this);" name="domain">
					<option value="">请选择主域名</option>
					<?php foreach($sites as $v){ ?>
					<option value="<?=$v['domain']?>"><?=$v['domain']?></option>
					<?php } ?>
				</select>
				<?php } ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="hosts">子域名</label>
			<div class="col-sm-9">
				<?php if($act=='edit_limit_rule'){ ?>
					<?=$rule['host']?>
				<?php }else{ ?>
				<select class="form-control" disabled id="hosts" name="host">
				</select>
				<?php } ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="hosts">限额 / 周期</label>
			<div class="col-sm-9">
				<div class="row">
				<div class="col-sm-6">
					<input type="text" class="form-control" name="fee" value="<?=$rule['fee']?>">
				</div>
				<div class="col-sm-6">
					<select class="form-control" name="unit">
						<option value="day" <?=ifselected($rule['unit'],'day','str')?>>天</option>
						<option value="week" <?=ifselected($rule['unit'],'week','str')?>>周</option>
						<option value="month" <?=ifselected($rule['unit'],'month','str')?>>月</option>
					</select>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		关闭
	</button>
	<button type="submit" class="btn btn-primary">
		提交
	</button>
</div>
</form>
<script>
function check_form(obj){
	if(!obj.domain.value || !obj.host.value || !obj.fee.value || !obj.unit.value){
		alert('不完整的参数');
		return false;
	}
}
function load_host(obj){
	var domain = obj.value;
	var inhtml = '<option value="*">全部子域名</option>';
	$.get('/settings/load_host/'+domain,function(data){
		if(data.status=='success'){
			for(i=0;i<data.hosts.length;i++){
				inhtml += '<option value="'+data.hosts[i]+'">'+data.hosts[i]+'</option>';
			}
			$('#hosts').removeAttr('disabled');
			$('#hosts').html(inhtml);
		}
	},'json');
}
function add_keychain(){
	var form = $('#add_keychain_form');
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