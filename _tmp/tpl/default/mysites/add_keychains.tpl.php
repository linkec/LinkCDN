<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-04-14 17:38:37

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">密钥管理</h4>
</div>
<form id="add_keychain_form" action="/mysites/add_keychains" onsubmit="return false;">
<input type="hidden" name="task" value="add">
<div class="modal-body">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">密钥名称</label>
					<input type="text" class="form-control" name="name">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">公钥内容 Certification</label>
					<textarea rows="10" class="form-control" name="cert" placeholder="-----BEGIN CERTIFICATE-----
ABCDEFHIJKLMNOPQRSTUVWXYZ
ABCDEFHIJKLMNOPQRSTUVWXYZ
ABCDEFHIJKLMNOPQRSTUVWXYZ
ABCDEFHIJKLMNOPQRSTUVWXYZ
...
-------END CERTIFICATE-----" style="word-wrap: normal;font-family: cursive;"></textarea> 
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">私钥内容 Key</label>
					<textarea rows="10" class="form-control" name="key" placeholder="-----BEGIN RSA PRIVATE KEY-----
ABCDEFHIJKLMNOPQRSTUVWXYZ
ABCDEFHIJKLMNOPQRSTUVWXYZ
ABCDEFHIJKLMNOPQRSTUVWXYZ
ABCDEFHIJKLMNOPQRSTUVWXYZ
...
-----END RSA PRIVATE KEY-----" style="word-wrap: normal;font-family: cursive;"></textarea> 
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