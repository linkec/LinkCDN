<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-04-27 02:59:50

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">缓存清理</h4>
</div>
<form id="purge_cache_form" action="/mysites/purge_cache" onsubmit="return ajaxform(this);">
<input type="hidden" name="task" value="submit">
<input type="hidden" name="site_id" value="<?=$site_id?>">
<input type="hidden" name="host" value="<?=$host?>">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">单个URL</label>
					<input type="text" class="form-control" name="url" placeholder="/image/logo.png">
				</div>
			</div>
			<div class="col-sm-12 text-center">
				或者 <br><br><a class="btn btn-primary" onclick="purge_all('<?=$site_id?>','<?=$host?>');">全站刷新</a>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="alert alert-danger">
				<strong>提醒：</strong> 刷新任务提交后将在后台执行，稍等片刻即可生效。
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a onclick="submit_purge()" class="btn btn-primary">
			提交
		</a>
		<button type="button" class="btn btn-default" data-dismiss="modal">
			关闭
		</button>
	</div>
</form>
<script>
function purge_all(site_id,host){
	// var form = /mysites/purge_cache;
	// console.log(form);
	$('button').attr('disabled',"true");
	  $.ajax({
			url: '/mysites/purge_cache',
			type: "POST",
			data: 'task=submit&site_id='+site_id+'&host='+host+'&url=*',
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
function submit_purge(){
	var form = $('#purge_cache_form');
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