<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">重载节点</h1>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!-- MAIN CONTENT -->
<div id="page-content">
	
	<div class="panel panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">重载节点</h3>
		</div>
		<div class="panel-body">
			<div class="pad-btm form-inline">
				<div class="row">
					<div class="col-sm-6 table-toolbar-left">
						<a class="btn btn-purple" href="/nodes/list">节点列表</a>
						<a class="btn btn-purple" href="/nodes/doreload">开始</a>
					</div>
				</div>
			</div>
			<div id="reload_console">
				
			</div>
		</div>
	</div>
</div>
<!-- END MAIN CONTENT -->
<script>
function start_reload(){
	$('#remoteModal div.modal-content').html(html);
}
</script>