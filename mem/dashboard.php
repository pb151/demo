<?php
/**
 * Dashboard
 *
 * @package		gmi
 * @subpackage	master-ds
 *
 * @copyright	GetMyInvoices
 */

/**
 * Include configuration
 */
include_once('configs/config.inc.php');
verify_login();

// if last access page is set, then redirect their
if(isset($_SESSION['cms']['redirect_after_login']) && $_SESSION['cms']['redirect_after_login'] != '') {
	$redirect_page = $_SESSION['cms']['redirect_after_login'];
	unset($_SESSION['cms']['redirect_after_login']);
	network_redirect($redirect_page);
	die;
}

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['dashboard'] . ' - ' . $GLOBALS['config']['cms']['title'];


/**
 * BEGIN - Include CSS
 */
include_once($GLOBALS['config']['cms']['design_path'].'base/header.inc.php');
/**
 * END - Include CSS
 */

/**
 * BEGIN - Include JS
 */
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

	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption caption-md"><?php echo $GLOBALS['i18']['dashboard'];?></div>
					<div class="actions">
					</div>
				</div>
				<div class="portlet-body">

				</div>
			</div>
		</div>
	</div>

<?php

/**
 * END - HTML Output
 */
include_once($GLOBALS['config']['cms']['design_path'].'base/footer.inc.php');
?>