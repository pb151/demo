<?php
/**
 * Login page
 *
 * @package       gmi
 * @subpackage    master-ds
 *
 * @copyright     GetMyInvoices
 */

/**
 * Include configuration
 */
include_once('configs/config.inc.php');

if($_SESSION['cms']['logged_in']) {
    network_redirect('dashboard.php');
    die();
}

if(isset($_COOKIE["userdata"])){
    $username = $_COOKIE["userdata"];
}else{
    $username = '';
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
    <form role="form" id="frm_login" class="login-form form-horizontal form-bordered form-row-stripped" method="post" autocomplete="off">
        <h3 class="form-title">Login</h3>
        <div class="alert wrong-credential alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span id="error-message">Wrong Credentials</span>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" value="<?php echo $username; ?>" name="username"/>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase">Login</button>
        </div>
    </form>
</div>
<?php
/**
 * END - HTML Output
 */
include_once($GLOBALS['config']['cms']['design_path'].'base/footer_login.inc.php');
