<?php

header('Pragma: no-cache');
header('Cache-Control: max-age=1, s-maxage=1, no-store, no-cache, post-check=0, pre-check=0, must-revalidate, proxy-revalidate');

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html>
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title><?php echo $GLOBALS['config']['cms']['title'];?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="pragma" content="no-cache" />
		
		<base href="<?php echo $GLOBALS['config']['cms']['site_url'];?>">
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo $GLOBALS['config']['cms']['design_path']; ?>images/favicon.png?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>"/>
		
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
		
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/font-awesome/css/font-awesome.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/simple-line-icons/simple-line-icons.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/bootstrap/css/bootstrap.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/bootstrap-toastr/toastr.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/uniform/css/uniform.default.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		
		<?php
		if(isset($page_error) && $page_error) {
			?>
			<link href="<?php echo $GLOBALS['config']['cms']['design_path']; ?>css/error.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
			<?php
		} else {
			?>
			<link href="<?php echo $GLOBALS['config']['cms']['design_path'];?>css/login.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
			<?php
		}
		?>
		
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/css/components-md.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/css/plugins-md.min.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/layout/css/layout.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/layout/css/themes/default.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		
		<link href="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/layout/css/custom.css?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" rel="stylesheet" type="text/css"/>
		
		<!-- END GLOBAL MANDATORY STYLES -->
	</head>
	<body class="<?php if(isset($page_error) && $page_error) echo ' page-404-3'; else echo 'login';?>">