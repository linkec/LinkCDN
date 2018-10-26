<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-04-18 11:42:58

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div id="page-content">
	<div class="panel panel-default panel-left">
		<div class="panel-body">
			<div class="mar-btm pad-btm clearfix">
				<h1 class="page-header text-overflow">
					发布新的工单
				</h1>
			</div>
			<!--Input form-->
			<form role="form" class="" method="post" onsubmit="compose_wo(this);">
			<input type="hidden" name="task" value="add">
				<div class="row">
					<div class="col-lg-12">
						<input type="text" id="inputSubject" class="form-control" name="subject" placeholder="请输入工单标题" style="width: 50%;">
					</div>
				</div>
				<hr>
				<!--Wysiwyg editor : Summernote placeholder-->
				<textarea id="workorder-compose" name="content"></textarea>

				<div class="pad-ver">

					<!--Send button-->
					<button type="submit" class="btn btn-primary">
						<i class="demo-psi-mail-send icon-lg icon-fw"></i> 提交
					</button>

					<!--Discard button-->
					<a href="/workorders/list" class="btn btn-default">
						<i class="demo-pli-mail-remove icon-lg icon-fw"></i> 取消
					</a>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$('#workorder-compose').summernote({
	height:300
});
function compose_wo(obj){
	
}
</script>