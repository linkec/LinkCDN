<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div id="ribbon">
	<!-- breadcrumb -->
	<ol class="breadcrumb">
		<li>RayCDN</li><li>提醒</li>
	</ol>
	<!-- end breadcrumb -->
</div>
<div id="content">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="row">
				<div class="col-sm-12">
					<div class="text-center error-box">
						<h2 class="font-xl"><strong><i class="fa fa-fw fa-warning fa-lg text-warning"></i> 拒绝操作</strong></h2>
						<br>
						<p class="lead">
						{$msg}
						</p>
						<p class="lead">
							<a class="btn btn-default" onclick="history.go(-1)">返回</a>
						</p>
	
					</div>
	
				</div>
	
			</div>
		</div>
	</div>
</div>