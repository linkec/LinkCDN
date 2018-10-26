<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>管理平台</title>
    <link href="{STATIC_PATH}/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="{STATIC_PATH}/assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="{STATIC_PATH}/dist/css/style.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
    <script src="{STATIC_PATH}/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="{STATIC_PATH}/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="{STATIC_PATH}/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{STATIC_PATH}/dist/js/app.min.js"></script>
    <script src="{STATIC_PATH}/dist/js/app.init.light-sidebar.js"></script>
    <script src="{STATIC_PATH}/dist/js/app-style-switcher.js"></script>
    <script src="{STATIC_PATH}/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="{STATIC_PATH}/assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="{STATIC_PATH}/dist/js/waves.js"></script>
    <script src="{STATIC_PATH}/dist/js/sidebarmenu.js"></script>
    <script src="{STATIC_PATH}/dist/js/custom.min.js"></script>
    <script src="{STATIC_PATH}/assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="{STATIC_PATH}/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="{STATIC_PATH}/assets/extra-libs/c3/d3.min.js"></script>
    <script src="{STATIC_PATH}/assets/extra-libs/c3/c3.min.js"></script>
    <script src="{STATIC_PATH}/assets/libs/chart.js/dist/Chart.min.js"></script>
    <script src="{STATIC_PATH}/dist/js/pages/dashboards/dashboard1.js"></script>
</head>
<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header">
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <a class="navbar-brand" href="index.html">
                        <b class="logo-icon">
                            <img src="{STATIC_PATH}/assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                            <img src="{STATIC_PATH}/assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <span class="logo-text">
                             <img src="{STATIC_PATH}/assets/images/logo-text.png" alt="homepage" class="dark-logo" />  
                             <img src="{STATIC_PATH}/assets/images/logo-light-text.png" class="light-logo" alt="homepage" />
                        </span>
                    </a>
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                    </ul>
                    <ul class="navbar-nav float-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{STATIC_PATH}/assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <span class="with-arrow"><span class="bg-primary"></span></span>
                                <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                                    <div class=""><img src="{STATIC_PATH}/assets/images/users/1.jpg" alt="user" class="img-circle" width="60"></div>
                                    <div class="m-l-10">
                                        <h4 class="m-b-0">{$myinfo['username']}</h4>
                                        <p class=" m-b-0">{$myinfo['email']}</p>
                                    </div>
                                </div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> 个人信息</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i> 技术支持</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-power-off m-r-5 m-l-5"></i> 安全退出</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li>
                            <div class="user-profile d-flex no-block dropdown m-t-20">
                                <div class="user-pic"><img src="{STATIC_PATH}/assets/images/users/1.jpg" alt="users" class="rounded-circle" width="40" /></div>
                                <div class="user-content hide-menu m-l-10">
                                    <a href="javascript:void(0)" class="" id="Userdd" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <h5 class="m-b-0 user-name font-medium">{$myinfo['username']} <i class="fa fa-angle-down"></i></h5>
                                        <span class="op-5 user-email">{$myinfo['email']}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Userdd">
                                        <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> 个人信息</a>
                                        <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i> 技术支持</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-power-off m-r-5 m-l-5"></i> 安全退出</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="p-15 m-t-10"><a href="javascript:void(0)" class="btn btn-block create-btn text-white no-block d-flex align-items-center"><i class="fa fa-plus-square"></i> <span class="hide-menu m-l-5">添加网站</span> </a></li>
                        
						<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">管理平台</span></li>
						<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link active" href="/manage" aria-expanded="false"><i class="mdi mdi-arrange-bring-forward"></i><span class="hide-menu">平台概览</span></a></li>
                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">站点中心</span></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/websites" aria-expanded="false"><i class="mdi mdi-web"></i><span class="hide-menu">我的网站</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/certificates" aria-expanded="false"><i class="mdi mdi-certificate"></i><span class="hide-menu">证书管理</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/purchase" aria-expanded="false"><i class="mdi mdi-cart-plus"></i><span class="hide-menu">购买套餐</span></a></li>
                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">用户中心</span></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/profile" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">个人信息</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/security" aria-expanded="false"><i class="mdi mdi-lock"></i><span class="hide-menu">安全设置</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/orders" aria-expanded="false"><i class="mdi mdi-format-list-bulleted"></i><span class="hide-menu">订单管理</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/costs" aria-expanded="false"><i class="mdi mdi-apps"></i><span class="hide-menu">消费明细</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/loginHistory" aria-expanded="false"><i class="mdi mdi-account-alert"></i><span class="hide-menu">登录日志</span></a></li>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="page-wrapper">
			<!--#include '.$mod.'/'.$act.'#-->
            <footer class="footer text-center">
				   All Rights Reserved by LinkCDN. Designed and Developed by <a href="https://wrappixel.com">WrapPixel</a>.
			</footer>
        </div>
    </div>
</body>
</html>