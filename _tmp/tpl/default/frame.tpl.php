<?php 
// This is auto-generated file. Do NOT modify.

// Cache Time:2017-07-26 03:22:22

!defined('IN_APP') && exit('[MYAPP] Access Denied');

?>
<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>控制台 | 睿速 - 睿智的网站加速</title>


    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>


    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="<?=$static_url?>/css/bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="<?=$static_url?>/css/nifty.min.css" rel="stylesheet">


    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="<?=$static_url?>/css/demo/nifty-demo-icons.min.css" rel="stylesheet">

    <!--Ion Icons [ OPTIONAL ]-->
    <link href="<?=$static_url?>/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">



        
    <!--Morris.js [ OPTIONAL ]-->
    <link href="<?=$static_url?>/plugins/morris-js/morris.min.css" rel="stylesheet">


    <!--Magic Checkbox [ OPTIONAL ]-->
    <link href="<?=$static_url?>/plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="<?=$static_url?>/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">


	<style>
	.record-list tr{
		height:49px;
	}
	.unchangeable {
		background-color: #f9f9f9;
	}
	#floating-top-right {
		z-index:99999999;
	}
	</style>



    
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="<?=$static_url?>/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=$static_url?>/plugins/pace/pace.min.js"></script>


    <!--jQuery [ REQUIRED ]-->
    <script src="<?=$static_url?>/js/jquery.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="<?=$static_url?>/js/bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="<?=$static_url?>/js/nifty.min.js"></script>
    <!--Select2 [ OPTIONAL ]-->
    <link href="<?=$static_url?>/plugins/select2/css/select2.min.css" rel="stylesheet">
        
    <!--Summernote [ OPTIONAL ]-->
    <link href="<?=$static_url?>/plugins/summernote/summernote.min.css" rel="stylesheet">









    <!--=================================================-->
    <!--Flot Chart [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/flot-charts/jquery.flot.min.js"></script>
	<script src="<?=$static_url?>/plugins/flot-charts/jquery.flot.resize.min.js"></script>

    <!--Morris.js [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/morris-js/morris.min.js"></script>
	<script src="<?=$static_url?>/plugins/morris-js/raphael-js/raphael.min.js"></script>
    <!--Easy Pie Chart [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>



    <!--Sparkline [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Bootbox Modals [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/bootbox/bootbox.min.js"></script>

    <!--Select2 [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/select2/js/select2.min.js"></script>
    <!--Summernote [ OPTIONAL ]-->
    <script src="<?=$static_url?>/plugins/summernote/summernote.min.js"></script>
    <script src="<?=$static_url?>/js/jquery.animateNumber.min.js"></script>
    <script src="<?=$static_url?>/js/echarts.min.js"></script>

    <script>
	Date.prototype.Format = function (fmt) { //author: meizz 
		var o = {
			"M+": this.getMonth() + 1, //月份 
			"d+": this.getDate(), //日 
			"h+": this.getHours(), //小时 
			"m+": this.getMinutes(), //分 
			"s+": this.getSeconds(), //秒 
			"q+": Math.floor((this.getMonth() + 3) / 3), //季度 
			"S": this.getMilliseconds() //毫秒 
		};
		if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
		for (var k in o)
		if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		return fmt;
	}
	</script>







    
    <!--=================================================

    REQUIRED
    You must include this in your project.


    RECOMMENDED
    This category must be included but you may modify which plugins or components which should be included in your project.


    OPTIONAL
    Optional plugins. You may choose whether to include it in your project or not.


    DEMONSTRATION
    This is to be removed, used for demonstration purposes only. This category must not be included in your project.


    SAMPLE
    Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


    Detailed information and more samples can be found in the document.

    =================================================-->
        

</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <div id="container" class="effect aside-float aside-bright mainnav-lg footer-fixed navbar-fixed">
        
        <!--NAVBAR-->
        <!--===================================================-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">

                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <a href="#" class="navbar-brand">
                        <img src="<?=$static_url?>/img/logo.png" alt="Nifty Logo" class="brand-icon">
                        <div class="brand-title">
                            <span class="brand-text">RAYCDN</span>
                        </div>
                    </a>
                </div>
                <!--================================-->
                <!--End brand logo & name-->


                <!--Navbar Dropdown-->
                <!--================================-->
                <div class="navbar-content clearfix">
                    <ul class="nav navbar-top-links pull-left">

                        <!--Navigation toogle button-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="tgl-menu-btn">
                            <a class="mainnav-toggle" href="#">
                                <i class="demo-pli-view-list"></i>
                            </a>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End Navigation toogle button-->



                        <!--Notification dropdown-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="demo-pli-bell"></i>
								<?php if($has_new_msg){ ?>
                                <span class="badge badge-header badge-danger"></span>
								<?php } ?>
                            </a>

                           
                            <div class="dropdown-menu dropdown-menu-md">
                                <div class="pad-all bord-btm">
                                    <p class="text-semibold text-main mar-no">你有 <?=$has_new_msg?> 个新消息</p>
                                </div>
                                <div class="nano scrollable">
                                    <div class="nano-content">
                                        <ul class="head-list">
											 <?php foreach($notifications as $v){ ?>
                                            <li>
                                                <a class="media" href="/messages/view/<?=$v['id']?>">
                                                    <div class="media-body">
                                                        <div class="text-nowrap<?=$v['is_read'] ? '' : ' text-semibold'?>"><?=$v['subject']?></div>
                                                        <small class="text-muted"><?=$v['in_time']?></small>
                                                    </div>
                                                </a>
                                            </li>
											 <?php } ?>
                                        </ul>
                                    </div>
                                </div>

                                <!--Dropdown footer-->
                                <div class="pad-all bord-top">
                                    <a href="/messages/home" class="btn-link text-dark box-block">
                                        <i class="fa fa-angle-right fa-lg pull-right"></i> 查看所有消息
                                    </a>
                                </div>
                            </div>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End notifications dropdown
                        <li style="color:red;font-size:25px;font-weight:bolder;">
						系统正在升级中，暂时可能不能对域名进行修改等操作。
                        </li>-->
                    </ul>
                    <ul class="nav navbar-top-links pull-right">

                        <!--User dropdown-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li id="dropdown-user">
                            <a href="/settings/profile" class="text-right">
                                <span class="pull-right">
                                    <!--<img class="img-circle img-user media-object" src="img/profile-photos/1.png" alt="Profile Picture">-->
                                    <i class="demo-pli-male ic-user"></i>
                                </span>
                                <div class="username hidden-xs"><?=$app_username?></div>
                            </a>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End user dropdown-->

                        <li>
                            <a onclick="logout();">
                                <i class="ion-arrow-return-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--================================-->
                <!--End Navbar Dropdown-->

            </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->
		<script>
			function logout(){
				bootbox.dialog({
					message: "你确定要退出吗？",
					title: "系统提醒！",
					buttons: {
						default: {
							label: "取消",
							className: "btn-default",
							callback: function() {
							}
						},

						danger: {
							label: "确定！",
							className: "btn-danger",
							callback: function() {
								location='/account/logout';
							}
						}
					}
				});

			}
		</script>
        <div class="boxed">


            <div id="content-container">
				<?php require_once template_echo(''.$mod.'/'.$act.'','tpl/default/'); ?>
            </div>
            
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container" style="position:fixed">
                <div id="mainnav">

                    <!--Menu-->
                    <!--================================-->
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">

                                <!--Profile Widget-->
                                <!--================================-->
                                <div id="mainnav-profile" class="mainnav-profile">
                                    <div class="profile-wrap">
                                        <div class="pad-btm">
                                            <img class="img-circle img-sm img-border" src="<?=$static_url?>/img/profile-photos/1.png" alt="Profile Picture">
                                        </div>
                                        <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                            <p class="mnp-name"><?=$app_username?></p>
                                            <span class="mnp-desc"><?=$app_email?></span>
                                        </a>
                                    </div>
                                </div>


                                <!--Shortcut buttons-->
                                <!--================================-->
                                <div id="mainnav-shortcut">
                                    <ul class="list-unstyled">


                                        <li class="col-xs-3" data-content="个人信息">
                                            <a class="shortcut-grid" href="/settings/profile">
                                                <i class="ion-person" style="font-size: 20px;"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" data-content="消息中心">
                                            <a class="shortcut-grid" href="/messages/home">
                                                <i class="ion-ios-bell" style="font-size: 20px;"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" data-content="域名列表">
                                            <a class="shortcut-grid" href="/mysites">
                                                <i class="ion-earth" style="font-size: 19px;"></i>
                                            </a>
                                        </li>
                                        <li class="col-xs-3" data-content="发布工单">
                                            <a class="shortcut-grid" href="/workorders/new">
                                                <i class="ion-ios-list-outline" style="font-size: 19px;"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <ul id="mainnav-menu" class="list-group">
						            <li class="list-header">导航</li>
						            <li <?=$mod=='home' ? 'class="active-link"' : ''?>>
						                <a href="/home">
						                    <i class="ion-ios-nutrition"></i>
						                    <span class="menu-title">
												我的概览
											</span>
						                </a>
						            </li>
						            <li <?=$mod=='mysites' ? 'class="active"' : ''?>>
						                <a href="#">
						                    <i class="ion-earth"></i>
						                    <span class="menu-title">
												我的域名
											</span>
											<i class="arrow"></i>
						                </a>
						                <ul class="collapse">
						                    <li <?=$mod=='mysites' && $act=='add' ? 'class="active-link"' : ''?>><a href="/mysites/add">添加域名</a></li>
						                    <li <?=$mod=='mysites' && $act!='add' ? 'class="active-link"' : ''?>><a href="/mysites">域名列表</a></li>
						                </ul>
						            </li>
						            <li <?=$mod=='addon' ? 'class="active"' : ''?>>
						                <a href="#">
						                    <i class="ion-ios-cart"></i>
						                    <span class="menu-title">
												增值服务
											</span>
											<i class="arrow"></i>
						                </a>
						                <ul class="collapse">
											<li <?=$mod=='addon' && $act=='waf_pro' ? 'class="active-link"' : ''?>><a href="/addon/waf_pro">WAF PRO</a></li>
						                    <li <?=$mod=='addon' && $act=='antiddos_pro' ? 'class="active-link"' : ''?>><a href="/addon/antiddos_pro">Anti-DDOS PRO</a></li>
						                </ul>
						            </li>
						            <li <?=$mod=='orders' ? 'class="active"' : ''?>>
						                <a href="#">
						                    <i class="ion-social-yen"></i>
						                    <span class="menu-title">
												财务相关
											</span>
											<i class="arrow"></i>
						                </a>
						                <ul class="collapse">
						                    <li <?=$mod=='orders' && $act=='topup' ? 'class="active-link"' : ''?>><a href="/orders/topup">订单记录</a></li>
											<li <?=$mod=='orders' && $act=='money_log' ? 'class="active-link"' : ''?>><a href="/orders/money_log">购买记录</a></li>
											<li <?=$mod=='orders' && $act=='pay_log' ? 'class="active-link"' : ''?>><a href="/orders/pay_log">消费记录</a></li>
						                </ul>
						            </li>
						            <li <?=$mod=='settings' ? 'class="active"' : ''?>>
						                <a href="#">
						                    <i class="ion-ios-gear-outline"></i>
						                    <span class="menu-title">
												账户设置
											</span>
											<i class="arrow"></i>
						                </a>
						                <ul class="collapse">
						                    <li <?=$mod=='settings' && $act=='profile' ? 'class="active-link"' : ''?>><a href="/settings/profile">个人信息</a></li>
											<li <?=$mod=='settings' && $act=='limit' ? 'class="active-link"' : ''?>><a href="/settings/limit">消费限额</a></li>
											
						                </ul>
						            </li>
						            <li <?=$mod=='workorders' ? 'class="active"' : ''?>>
						                <a href="#">
						                    <i class="ion-ios-list-outline"></i>
						                    <span class="menu-title">
												工单系统
											</span>
											<i class="arrow"></i>
						                </a>
						                <ul class="collapse">
						                    <li <?=$mod=='workorders' && $act=='new' ? 'class="active-link"' : ''?>><a href="/workorders/new">新的工单</a></li>
											<li <?=$mod=='workorders' && $act!='new' ? 'class="active-link"' : ''?>><a href="/workorders/list">我的工单</a></li>
						                </ul>
						            </li>
						            <li <?=$mod=='messages' ? 'class="active-link"' : ''?>>
						                <a href="/messages/home">
						                    <i class="ion-ios-bell-outline"></i>
						                    <span class="menu-title">
												消息中心
											</span>
						                </a>
						            </li>
                            </div>
                        </div>
                    </div>
                    <!--================================-->
                    <!--End menu-->

                </div>
            </nav>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->

        </div>

        

        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                睿速 - 睿智的网站加速　　
            </div>

            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

            <p class="pad-lft">&#0169; 2017 RayCDN.COM</p>



        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->


        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
		<div class="modal fade" id="remoteModal" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				</div>
			</div>
		</div>



    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->


    
</body>
</html>
