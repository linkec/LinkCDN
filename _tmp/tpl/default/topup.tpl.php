<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-05-10 21:27:14

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">余额充值</h4>
</div>
<form action="/settings/pay_topup" class="form-horizontal" target="_blank" onsubmit="load_order_status();" method="post">
<div class="modal-body">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12">
					<div class="panel-body">
						<div class="form-group">
							<label class="col-sm-3 control-label text-lg">充值金额</label>
							<div class="col-sm-9">
								<input type="text" name="money" value="100" class="form-control input-lg">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label text-lg">支付方式</label>
							<div class="col-sm-9">
								<div class="radio">
									<input id="alipay" class="magic-radio" type="radio" name="method" value="alipay" checked="">
									<label for="alipay">支付宝</label>
									<input id="weixin" class="magic-radio" type="radio" name="method" value="weixin">
									<label for="weixin">微信支付</label>
									<input id="paypal" class="magic-radio" type="radio" name="method" value="paypal">
									<label for="paypal">PayPal</label>
								</div>
							</div>
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
	<button type="submit" class="btn btn-warning" onclick="add_keychain()">
		去支付
	</button>
</div>
</form>
<script>
</script>