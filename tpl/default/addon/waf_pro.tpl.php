<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div id="page-title">
	<h1 class="page-header text-overflow">高级网页应用防火墙（RAY WAF PRO）</h1>
</div>

<div id="page-content">
	<div class="row">
		<div class="col-md-3 mar-btm">
			<h4><i class="demo-psi-idea-2 text-warning icon-fw"></i> 核心优势</h4>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
			<p class="text-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;基于NGINX PLUS 和 RAYWAF 打造的顶级网页应用防火墙</p>
			<div class="list-group bg-trans">
				<a class="list-group-item list-item-sm" href="#"><span class="badge badge-purple badge-icon badge-fw pull-left"></span> 极致精准的清洗恶意流量</a>
				<a class="list-group-item list-item-sm" href="#"><span class="badge badge-success badge-icon badge-fw pull-left"></span> 每15分钟更新一次安全规则</a>
				<a class="list-group-item list-item-sm" href="#"><span class="badge badge-info badge-icon badge-fw pull-left"></span> 中间源同步防御系统</a>
				<a class="list-group-item list-item-sm" href="#"><span class="badge badge-pink badge-icon badge-fw pull-left"></span> 中间源异步防御系统</a>
			</div>
			<!--===================================================-->

			<hr class="new-section-sm bord-no">
	
			<!--===================================================-->
			<div class="pad-top">
				<!--#if($myinfo['waf_end_time']<$timestamp){#-->
				<a onclick="buy_waf_pro()" class="btn btn-primary">立刻为我的域名启用 WAF PRO</a>
				<!--#}else{#-->
				当前已购买高级WAF防护服务，到期时间为:{#date('Y-m-d',$myinfo['waf_end_time'])#}。
				<br><br><a onclick="buy_waf_pro()" class="btn btn-primary">续费</a><a onclick="wafstatus()" class="btn btn-warning">状态</a>
				<!--#}#-->
			</div>
			<!--===================================================-->
	
		</div>
		<div class="col-md-9">
			<div class="panel">
				<div class="panel-body">
	
					<!-- GENERAL -->
					<!--===================================================-->
					<h4 class="mar-no pad-btm bord-btm"><i class="ion-chatbubbles"></i> 常见问题</h4>
					<div id="demo-gen-faq" class="panel-group accordion">
					<div class="pad-ver mar-ver">
	
							<!-- Question -->
							<a href="#demo-gen-faq1" class="text-semibold text-lg text-main" data-toggle="collapse" data-parent="#demo-gen-faq">RAY WAF PRO 是什么？</a>
	
							<!-- Answer -->
							<div id="demo-gen-faq1" class="mar-ver collapse in">
								WAF即Web Application Firewall（网页应用防火墙），通过执行一系列针对HTTP/HTTPS的安全策略来专门为Web应用提供保护的产品，能够有效防御黑客利用网站应用程序漏洞入侵渗透等攻击，这些攻击可能会导致大量的数据外泄、数据丢失/损坏、客户流失或者其他恶劣的影响；RAY WAF PRO 是 RAYCDN 提供的更稳定，更高效率，更安全可靠的网页应用防火墙。
							</div>
						</div>	
						<div class="pad-ver mar-ver">
	
							<!-- Question -->
							<a href="#demo-gen-faq2" class="text-semibold text-lg text-main" data-toggle="collapse" data-parent="#demo-gen-faq">我是否需要这个功能？</a>
	
							<!-- Answer -->
							<div id="demo-gen-faq2" class="mar-ver collapse in">
								如果您的网站被黑客利用WEB漏洞攻击，拖库、篡改网页、植入恶意代码等，使用 RAY WAF PRO 将会是一个绝佳的选择，只需要在控制面板勾选本功能并保存设置，等待几分钟即可生效，轻松一步即可为网站提供近100%的安全防御支持。
							</div>
						</div>	
						<div class="pad-ver mar-ver">
	
							<!-- Question -->
							<a href="#demo-gen-faq3" class="text-semibold text-lg text-main" data-toggle="collapse" data-parent="#demo-gen-faq">与其他传统的WAF区别在哪里 ？</a>
	
	
							<!-- Answer -->
							<div id="demo-gen-faq3" class="mar-ver collapse in">
								一般的云加速的服务商都是使用开源的WAF，没有稳定的开发团队和维护团队，无法保障系统的稳定和适应性更新，一般来说他们仅仅依靠已有规则来防御黑客攻击，同时他们的防火墙仅布置在边缘节点，无法深度的清晰流量，针对未知漏洞攻击分析和阻断的能力十分薄弱；RAY WAF PRO 不仅在边缘节点上有基础的防御设施，还会启用多组中间源系统来实时协同清洗流量，具有极高的安全保障。
							</div>
						</div>				
						<div class="pad-ver mar-ver">
	
							<!-- Question -->
							<a href="#demo-gen-faq4" class="text-semibold text-lg text-main" data-toggle="collapse" data-parent="#demo-gen-faq">怎样启用 WAF PRO ?</a>
	
	
							<!-- Answer -->
							<div id="demo-gen-faq4" class="mar-ver collapse in">
								添加域名到 RAYCDN 并生效后，进入 [域名列表] ，选择需要开启本功能的域名，点击 [控制面板]，在 [安全防护] 项中，您可以看到 [WAF 高级版]的启用开关，勾选启用，并 [保存设置] ；保存设置后一般等待5分钟即可生效。
							</div>
						</div>
						<div class="pad-ver mar-ver">
	
							<!-- Question -->
							<a href="#demo-gen-faq5" class="text-semibold text-lg text-main" data-toggle="collapse" data-parent="#demo-gen-faq">怎样收费 / 怎么计费 ?</a>
	
							<!-- Answer -->
							<div id="demo-gen-faq5" class="mar-ver collapse in">
								我们为所有用户免费提供WAF基础版（90% 拦截能力）；WAF PRO 则按照域名收费，50元/月，购买 WAF PRO 后，用户帐号下的所有域名均可以免费开启 WAF PRO ；本功能由于涉及到双中间源布置以及边缘节点重构，所以需要先购买后才能启用，不支持按用量计费。
							</div>
						</div>
						<div class="pad-ver mar-ver">
	
							<!-- Question -->
							<a href="#demo-gen-faq6" class="text-semibold text-lg text-main" data-toggle="collapse" data-parent="#demo-gen-faq">是否保证能拦截所有攻击 ？</a>
	
	
							<!-- Answer -->
							<div id="demo-gen-faq6" class="mar-ver collapse in">
								我们不认为有任何一个人或者企业能实现真正的100%安全，总有我们不知道的漏洞和攻击方式，所以我们无法承诺我们能拦截所有的漏洞攻击；但是我们的 WAF PRO 基于NGINX PLUS 和 RAYWAF 打造，稳定性和可靠性值得信耐，安全性和高效率绝非其他开源的WAF可比拟；对于已知漏洞的攻击拦截成功率保证100%，对于未知漏洞的攻击拦截成功率保证不低于99%，一旦漏洞被确认，我们会在15分钟内为所有系统下发新的规则文件，以确保100%拦截成功。
							</div>
						</div>
						<div class="pad-ver mar-ver">
	
							<!-- Question -->
							<a href="#demo-gen-faq7" class="text-semibold text-lg text-main" data-toggle="collapse" data-parent="#demo-gen-faq">是否会影响网站的访问速度 ？</a>
	
	
							<!-- Answer -->
							<div id="demo-gen-faq7" class="mar-ver collapse in">
								一般情况下不会有影响，高级应用防火墙是基于多组中间源来协同完成流量清洗，所以如果您的服务器网络本身就特别的好，启用本系统可能会增加毫秒级的延迟，这种延迟的增加，用户是不会有任何感知的；如果您的服务器网络相对较差，那么启用本系统反而会大幅度减少延迟。
							</div>
						</div>
					</div>
					<!--===================================================-->	
				</div>
			</div>
		</div>
	</div>
		
</div>
<!--===================================================-->
<!--End page content-->

<script>
function buy_waf_pro(){
	$.get('/mysites/buy_addon/0/waf_pro',function(html){
		$('#remoteModal div.modal-content').html(html);
		$('#remoteModal').modal('show');
	});
}
function wafstatus(){
	$.get('/mysites/wafstatus',function(html){
		$('#remoteModal div.modal-content').html(html);
		$('#remoteModal').modal('show');
	});
}
</script>