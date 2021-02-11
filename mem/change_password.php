<?php
/**
 * Change Password page
 *
 * @package       gmi
 * @subpackage    ds
 *
 * @copyright     GetMyInvoices
 */

/**
 * Include configuration
 */
include_once('configs/config.inc.php');
if(!$_SESSION['cms']['logged_in']) {
	doLogout();
	network_redirect('login.php');
	die();
}

if(!isset($_SESSION['username']) || !check_ip($_SESSION['username'])) {
	doLogout();
	network_redirect('login.php');
	die();
}

/**
 * BEGIN - Include CSS
 */
include_once($GLOBALS['config']['cms']['design_path'].'base/header_login.inc.php');
/**
 * END - Include CSS
 */

/**
 * BEGIN - Include JS
 */
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'global/scripts/metronic.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'admin/layout/scripts/layout.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'admin/layout/scripts/demo.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'].'js/login.js';

/**
 * END - Include JS
 */

/**
 * BEGIN - Business Logic
 */

/**
 * END - Business Logic
 */

/**
 * BEGIN - HTML Output
 */
?>
	
	<div class="content">
		<form role="form" id="frm_change_password" class="change-form form-horizontal form-bordered form-row-stripped" method="post" autocomplete="off">
			<h3 class="form-title">Change Password</h3>
			<div class="alert wrong-credential alert-danger display-hide">
				<button class="close" data-close="alert"></button>
				<span>Wrong Password or passwords doesn't match.</span>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Old Password</label>
				<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Old Password" name="old_password"/>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">New Password</label>
				<input id="new_password" class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="New Password" name="new_password"/>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Confirm Password</label>
				<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Confirm Password" name="confirm_password"/>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-success uppercase">Change Password</button>
				<a class="pull-right" href="logout.php"><i class="icon-logout"></i> Logout</a>
			</div>
		</form>
	</div>
<?php
/**
 * END - HTML Output
 */
include_once($GLOBALS['config']['cms']['design_path'].'base/footer_login.inc.php');
?>