<?php

header('Pragma: no-cache');
header('Cache-Control: max-age=1, s-maxage=1, no-store, no-cache, post-check=0, pre-check=0, must-revalidate, proxy-revalidate');
?>
<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html>
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8"/>
	<title><?php echo $GLOBALS['config']['cms']['title']; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport"/>

	<meta http-equiv="cache-control" content="no-cache"/>
	<meta http-equiv="expires" content="0"/>
	<meta http-equiv="pragma" content="no-cache"/>

	<base href="<?php echo $GLOBALS['config']['cms']['site_url']; ?>">

	<!-- Favicon -->
	<link rel="shortcut icon"
		  href="<?php echo $GLOBALS['config']['cms']['design_path']; ?>images/favicon.png?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"/>

	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
		  type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/font-awesome/css/font-awesome.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/simple-line-icons/simple-line-icons.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/bootstrap/css/bootstrap.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/bootstrap-toastr/toastr.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES -->
	<?php
	if(isset($GLOBALS['cms']['includeCSS'])) {
		foreach($GLOBALS['cms']['includeCSS'] as $css) {
			echo '<link rel="stylesheet" href="/'.$css.'?v='.$GLOBALS['config']['cms']['build_version'].'" type="text/css" />'."\n";
		}
		unset($css);
	}
	?>
	<!-- END PAGE LEVEL STYLES -->

	<!-- BEGIN THEME STYLES -->
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/css/components-md.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/css/plugins-md.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/layout/css/layout.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/layout/css/themes/default.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>

	<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/layout/css/custom.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>"
		  rel="stylesheet" type="text/css"/>
	<!-- END THEME STYLES -->

	<link href="<?php echo $GLOBALS['config']['cms']['design_path']; ?>css/custom.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo $GLOBALS['config']['cms']['design_path']; ?>css/dropdown.css?v=<?php echo $GLOBALS['config']['cms']['build_version']; ?>" rel="stylesheet" type="text/css"/>

</head>
<body class="page-container-bg-solid page-header-menu-fixed page-md">
<!-- loading animation -->
<div id="qLoverlay"></div>
<div id="qLbar"></div>

<!-- BEGIN HEADER -->
<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container-fluid">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<div class="logo_holder">
					<center>
						<a href="<?php echo $GLOBALS['config']['cms']['site_url']; ?>">Master DS</a>
					</center>
				</div>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler"></a>
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown dropdown-user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<span class="username username-hide-on-mobile"><?php echo $_SESSION['username'];?></span>
							<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<?php if(has_access('user_administrator')) { ?>
								<li <?php echo @$selected_menu == 'users' ? 'class="active"' : ''; ?>>
									<a href="users.php"><i class="fa fa-user"></i> <?php echo $GLOBALS['i18']['users']; ?></a>
								</li>
							<?php } ?>
							<li><a href="change_password.php"><i class="fa fa-key"></i> <?php echo $GLOBALS['i18']['change_password'];?> </a></li>
							<li><a href="logout.php"><i class="fa fa-sign-out"></i> <?php echo $GLOBALS['i18']['logout'];?> </a></li>
						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>

			</div>
			<!-- END TOP NAVIGATION MENU -->
		</div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	<div class="page-header-menu">
		<div class="container-fluid">
			<!-- BEGIN MEGA MENU -->
			<!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
			<!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
			<div class="hor-menu  ">
				<ul class="nav navbar-nav">
					<?php if(has_access('overview')) { ?>
						<li <?php echo @$selected_menu == 'overview' ? 'class="active"' : ''; ?>>
							<a href="overview.php"> <?php echo $GLOBALS['i18']['overview']; ?></a>
						</li>
					<?php } ?>
					<?php if(has_access('sys_log') || has_access('qf_log') || has_access('release_logs') || has_access('scaling_logs')) { ?>
						<li class="portal-dropdown-list <?php if(@$selected_menu == 'sys_log' || @$selected_menu == 'qf_log' || @$selected_menu == 'release_logs' || @$selected_menu == 'scaling_logs') echo 'active'; ?>">
							<div class="dropdown">
								<a class="main-dropdown-button"> Logs <i class="fa fa-caret-down"></i></a>
								<div class="dropdown-content">
									<?php if(has_access('sys_log')) { ?>
									<a href="sys_log.php"> DS</a>
									<?php } ?>
                                    <?php if(has_access('qf_log')) { ?>
									<a href="qf_log.php"> QF</a>
                                    <?php } ?>
                                    <?php if(has_access('release_logs')) { ?>
									<a href="release_logs.php"> Releases</a>
                                    <?php } ?>
                                    <?php if(has_access('scaling_logs')) { ?>
									<a href="scaling_logs.php"> Worker-Scaling</a>
                                    <?php } ?>
								</div>
							</div>
						</li>
					<?php } ?>
					<?php if(has_access('ustack')) { ?>
						<li class="portal-dropdown-list <?php if(@$selected_menu == 'ustack' || @$selected_menu == 'merge_back') echo 'active'; ?>">
							<div class="dropdown">
								<a class="main-dropdown-button"> <?php echo $GLOBALS['i18']['ustack']; ?> <i class="fa fa-caret-down"></i></a>
								<div class="dropdown-content">
									<a href="ustacks.php"> <?php echo $GLOBALS['i18']['update_projects_from_ustack']; ?></a>
									<a href="merge_back.php"> <?php echo $GLOBALS['i18']['update_ustack_from_projects']; ?></a>
								</div>
							</div>
                        </li>
                    <?php } ?>				
                    <?php if(has_access('releases')) { ?>
                        <li <?php echo @$selected_menu == 'releases' ? 'class="active"' : ''; ?>>
                            <a href="releases.php"> <?php echo $GLOBALS['i18']['releases']; ?></a>
                        </li>
                    <?php } ?>
				</ul>
			</div>
			<!-- END MEGA MENU -->
		</div>
	</div>
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<!-- BEGIN CONTENT BODY -->
		<!-- BEGIN PAGE HEAD-->
		<div class="page-head">
			<div class="container-fluid">&nbsp;</div>
		</div>
		<!-- END PAGE HEAD-->
		<!-- BEGIN PAGE CONTENT BODY -->
		<div class="page-content">

			<div class="container-fluid">

				<div class="page-content-inner">
