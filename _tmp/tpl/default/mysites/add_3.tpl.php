<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-05-10 21:47:00

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>

<div id="page-title">
	<h1 class="page-header text-overflow">添加域名</h1>
</div>
<div id="page-content">
	<div class="col-lg-12">
		<h4 class="text-main pad-btm bord-btm">添加一个新的网站至 RayCDN 进行安全防护和加速</h4>
		<div class="panel">
			<div>
				<div>
					<ul class="wz-nav-off wz-icon-inline wz-classic">
						<li class="col-xs-4 bg-mint">
							<a>
								<span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="demo-pli-information icon-lg"></i></span> 添加域名
							</a>
						</li>
						<li class="col-xs-4 bg-mint">
							<a>
								<span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="demo-pli-male icon-lg"></i></span> 配置子域名
							</a>
						</li>
						<li class="col-xs-4 bg-mint active">
							<a>
								<span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="demo-pli-home icon-lg"></i></span> 配置服务商设置
							</a>
						</li>
					</ul>
					<div class="progress progress-xs progress-striped active">
						<div class="progress-bar progress-bar-dark" style="width: 100%;"></div>
					</div>
				</div>
				<div class="panel-body">
					<div class="col-lg-8 col-lg-offset-2 tab-content">
						<div class="tab-pane active" id="tab1">
							<h3><strong>第三步 </strong> - 配置服务商设置 （<?=$site['domain']?>）</h3><br>
							<?php if($site['status']=='success'){ ?>
								<div class="alert alert-success fade in">
									<i class="fa-fw fa fa-check"></i>
									<strong>恭喜</strong> 您的解析已经生效，请前往控制面板进行更多设置。
								</div>
							<?php } ?>
							<?php if($site['cdn_type']=='ns'){ ?>
							<div class="alert alert-info fade in">
								<i class="fa-fw fa fa-info"></i>
								<strong>提醒</strong> 修改 DNS 可能需要最多 48 小时来同步到全球的服务器！
							</div>
							<?php } ?>
							<?php if($site['cdn_type']=='cn'){ ?>
							<div class="alert alert-info fade in">
								<i class="fa-fw fa fa-info"></i>
								<strong>提醒</strong> 修改 DNS 记录 可能需要最多 24 小时来同步到全球的服务器！
							</div>
							<?php } ?>
							<div class="">
							
							<?php if($site['cdn_type']=='ns'){ ?>
								<?php if($site['status']=='success'){ ?>
									<table class="table table-hover">
										<thead>
											<tr>
												<th>您的NS记录</th>
												<th></th>
											</tr>
										</thead>
										<tbody class="smart-form" id="records_area">
											<tr>
												<td><?=substr($custom_dns[0],0,-1)?></td>
												<td><i class="fa-fw fa fa-check"></i> 设置正确</td>
											</tr><tr>
												<td><?=substr($custom_dns[1],0,-1)?></td>
												<td><i class="fa-fw fa fa-check"></i> 设置正确</td>
											</tr>
										</tbody>
									</table>
									<?php }else{ ?>
									<table class="table table-hover">
										<thead>
											<tr>
												<th>您的NS记录</th>
												<th></th>
												<th>记录值</th>
												<th></th>
											</tr>
										</thead>
										<tbody class="smart-form" id="records_area">
											<tr>
												<td><?=$get_dns[0]['target']?></td>
												<td>修改为</td>
												<td><?=substr($custom_dns[0],0,-1)?></td>
												<td>复制</td>
											</tr><tr>
												<td><?=$get_dns[1]['target']?></td>
												<td>修改为</td>
												<td><?=substr($custom_dns[1],0,-1)?></td>
												<td>复制</td>
											</tr>
										</tbody>
									</table>
								<?php } ?>
							<?php }else{ ?>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>子域名</th>
											<th></th>
											<th>记录值</th>
											<th></th>
										</tr>
									</thead>
									<tbody class="smart-form" id="records_area">
										<?php foreach($records as $v){ ?>
										<tr>
											<td><?=$v['host']?></td>
											<td>设置 <?=$v['type']?> 为</td>
											<td><?=$v['cname']?></td>
											<td>复制</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer text-right">
					<div class="box-inline">
						<a href="/mysites/add_2/<?=$site_id?>" class="next btn btn-default">返回</a>
						<a href="/mysites" class="next btn btn-info">完成</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>