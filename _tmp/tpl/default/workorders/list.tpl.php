<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-04-18 11:59:39

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<div id="page-content">
	<!-- MAIL INBOX -->
	<!--===================================================-->
	
	<div class="fixed-fluid">
		<div class="fixed-sm-200 fixed-md-250 pull-sm-left">
			<div class="panel">
				<div class="pad-all bord-btm">
					<a href="/workorders/new" class="btn btn-block btn-success">新的工单</a>
				</div>
	
				<p class="pad-hor mar-top text-main text-bold">分类</p>
				<div class="list-group bg-trans pad-btm bord-btm">
					<a href="/workorders/list" class="list-group-item<?=$hiconsole_req[3]!='waiting' && $hiconsole_req[3]!='closed' ? ' text-semibold text-main':''?>">
						<span class="badge badge-success pull-right"><?=$count['pendding']?></span>
						<span class="<?=$hiconsole_req[3]!='waiting' && $hiconsole_req[3]!='closed' ? 'text-main':''?>"><i class="demo-pli-inbox-full icon-lg icon-fw"></i> 待处理</span>
					</a>
					<a href="/workorders/list/waiting" class="list-group-item<?=$hiconsole_req[3]=='waiting' ? ' text-semibold text-main':''?>">
						<span class="badge badge-success pull-right"><?=$count['waiting']?></span>
						<i class="demo-pli-file icon-lg icon-fw"></i>
						<span class="<?=$hiconsole_req[3]=='waiting' ? 'text-main':''?>">待回复</span>
					</a>
					<a href="/workorders/list/closed" class="list-group-item<?=$hiconsole_req[3]=='closed' ? ' text-semibold text-main':''?>">
						<span class="badge badge-success pull-right"><?=$count['closed']?></span>
						<i class="demo-pli-mail-send icon-lg icon-fw"></i>
						<span class="<?=$hiconsole_req[3]=='closed' ? 'text-main':''?>">已关闭</span>
					</a>
				</div>
			</div>
		</div>
		<div class="fluid">
			<div class="panel">
				<div id="demo-email-list" class="panel-body">
					<h1 class="page-header text-overflow pad-btm">我的工单</h1>
	
					<hr class="hr-sm">
	
					<!--Mail list group-->
					<ul id="demo-mail-list" class="mail-list">
	
						<?php foreach($workorders as $v){ ?>
						<li class="mail-starred">
							<a href="/workorders/case/<?=$v['id']?>">
							<div class="mail-from"><?=$v['subject']?></div>
							<div class="mail-time"><?=$v['in_time']?></div>
							<div class="mail-subject"><?=$v['content']?></div>
							</a>
						</li>
						<?php } ?>
	
					</ul>
				</div>
	
	
				<!--Mail footer-->
				<div class="panel-footer clearfix">
					<div class="pull-right">
						<span class="text-muted"><strong>1-50</strong> of <strong>160</strong></span>
						<div class="btn-group btn-group">
							<button type="button" class="btn btn-default">
								<i class="demo-psi-arrow-left"></i>
							</button>
							<button type="button" class="btn btn-default">
								<i class="demo-psi-arrow-right"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!--===================================================-->
	<!-- END OF MAIL INBOX -->
	
</div>
