<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div id="page-content">
	<div class="panel">
		<!--Heading-->
		<div class="panel-heading">
			<!--#if($workorder['status']!='<span class="label label-default">已关闭</span>'){#-->
			<div class="panel-control">
				问题已经解决了？
				<a class="btn btn-danger" onclick="close_wo({$wo_id});">关闭该工单</a>
			</div>
			<!--#}#-->
			<h3 class="panel-title">{$workorder['status']} {$workorder['subject']}</h3>
		</div>
		<style>
			.speech img{
				cursor:pointer;
			}
		</style>
		<!--Widget body-->
		<div>
			<div >
				<div class="nano-content pad-all">
					<ul class="list-unstyled media-block" id="workorder_area">
						<li class="mar-btm">
							<div class="media-right">
								<img src="{$static_url}/img/profile-photos/1.png" class="img-circle img-sm" alt="Profile Picture">
							</div>
							<div class="media-body pad-hor speech-right">
								<div class="speech" style="width:100%;overflow:hidden;">
									<a href="#" class="media-heading">{$app_username}</a>
									<div id="wo_content">{$workorder['content']}</div>
									<p class="speech-time">
										<i class="demo-pli-clock icon-fw"></i> {$workorder['in_time']}
									</p>
								</div>
							</div>
						</li>
						<!--#foreach($reply as $v){#-->
							<!--#if($v['userid']!=$app_uid){#-->
							<li class="mar-btm">
								<div class="media-left">
									<img src="{$static_url}/img/profile-photos/1.png" class="img-circle img-sm" alt="Profile Picture">
								</div>
								<div class="media-body pad-hor">
									<div class="speech" style="width:100%;overflow:hidden;">
										<a href="#" class="media-heading">{#$v['username'] ? $v['username'] : '客户服务'#}</a>
										<div id="wo_content">{$v['content']}</div>
										<p class="speech-time">
										<i class="demo-pli-clock icon-fw"></i> {$v['in_time']}
										</p>
									</div>
								</div>
							</li>
							<!--#}else{#-->
							<li class="mar-btm">
								<div class="media-right">
									<img src="{$static_url}/img/profile-photos/1.png" class="img-circle img-sm" alt="Profile Picture">
								</div>
								<div class="media-body pad-hor speech-right">
									<div class="speech" style="width:100%;overflow:hidden;">
										<a href="#" class="media-heading">{#$v['username'] ? $v['username'] : $app_username#}</a>
										<div id="wo_content">{$v['content']}</div>
										<p class="speech-time">
											<i class="demo-pli-clock icon-fw"></i> {$v['in_time']}
										</p>
									</div>
								</div>
							</li>
							<!--#}#-->
						<!--#}#-->
					</ul>
				</div>
			</div>

			<!--Widget footer-->
			<div class="panel-footer">
				<form method="post">
				<input type="hidden" name="task" value="add">
					<div class="row">
						<div class="col-xs-12">
							<textarea placeholder="请在此处键入信息" rows="5" class="form-control" name="content" id="reply_area"></textarea>
						</div>
						<div class="col-xs-1">
							<button class="btn btn-primary btn-block" type="submit">提交</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$('#reply_area').summernote({
	height:200
});
function close_wo(id){
	$.get('/workorders/close_wo/'+id,function(data){
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
		}else{
			$.niftyNoty({
				type: 'danger',
				container: 'floating',
				html: '<strong>出现错误了</strong> '+data.msgs,
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
	},'json');
}
$('#wo_content img').bind('click',function(event){
	window.open(event.target.src);
	// $('#remoteModal div.modal-content').html('<img src="'+event.target.src+'" style="width:100%">');
	// $('#remoteModal').modal('show');
});
</script>