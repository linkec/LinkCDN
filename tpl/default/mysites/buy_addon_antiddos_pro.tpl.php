<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">Ray Anti-DDoS Pro</h4>
</div>
<form id="pay_form" action="/mysites/pay_addon" onsubmit="return false;">
<input type="hidden" name="task" value="antiddos_pro">
<input type="hidden" name="site_id" value="{$site_id}">
<div class="modal-body">
	<div class="panel-body">
		<div class="alert alert-warning">
			<strong>提醒：</strong> 当您在此购买高级防护后，您帐号下的所有站点均可免费启用！
		</div>
		<div class="row">
			<div class="col-lg-6">
				<h2 class="text-warning"><i class="fa fa-cny"></i> <span id="addon_price">999</span></h2>
			</div>
			<div class="col-lg-6">
				<!-- Radio Buttons -->
				<div class="radio">
					<input id="addon_year" class="magic-radio" type="radio" name="days" value="365" onchange="if(this.checked){$('#addon_price').html('9999')}">
					<label for="addon_year">年付 （365 天）</label>
				</div>
				<div class="radio">
					<input id="addon_month" class="magic-radio" type="radio" name="days" value="30" checked="" onchange="if(this.checked){$('#addon_price').html('999')}">
					<label for="addon_month">月付 （30 天）</label>
				</div>
			</div>
		</div>
		<p class="text-semibold text-main">高级应用防火墙</p>
		<p>Anti-DDOS Pro 是针对易被攻击站点所提供的防御增值服务：</p><p>我们在全球布置了5个高防区域，无论攻击大小与类型，我们均无条件介入防护；启用后系统会自动为您的站点启用多个高防节点，在攻击发生时自动切换到高防节点抵御一切攻击，是您网站最坚实屏障；本功能需预付费使用。</p>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		关闭
	</button>
	<button type="submit" class="btn btn-primary" onclick="pay_now();">
		确定
	</button>
</div>
</form>
<script>
function pay_now(){
	var form = $('#pay_form');
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