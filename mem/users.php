<?php
/**
 * Portal Queue
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
verify_has_access(array('user_administrator'));
$selected_menu = 'users';

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['users'] . ' - ' . $GLOBALS['config']['cms']['title'];

/**
 * BEGIN - Include CSS
 */
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/uniform/css/uniform.default.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/datatables.min.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['design_path'] .'js/plugins/multi-select/multi-select.css';

include_once($GLOBALS['config']['cms']['design_path'] . 'base/header.inc.php');
/**
 * END - Include CSS
 */
/**
 * BEGIN - Include JS
 */
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/scripts/datatable.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/datatables.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'] . 'js/users.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'] . 'js/plugins/multi-select/jquery.multi-select.js';
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
                <div class="caption caption-md">
                    <span class="caption-subject font-green-steel bold uppercase"><?php echo $GLOBALS['i18']['users']; ?></span>
                    <span class="caption-helper"><?php echo $GLOBALS['i18']['users_helptext']; ?></span>
                </div>
                <div class="actions">
					<button data-container="body" data-placement="bottom" class="btn theme-color tooltips" title="<?php echo $GLOBALS['i18']['add_user']; ?>" onclick="add_user();"><i class="fa fa-plus"></i> </button>
                    <button class="btn theme-color" onclick="grid_filter();"><i class="fa fa-refresh"></i> </button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container" id="tbl_users_container">
                    <table class="table table-striped table-bordered table-hover" id="tbl_users">
                        <thead>
                            <tr>
                                <th><?php echo $GLOBALS['i18']['username']; ?></th>
                                <th><?php echo $GLOBALS['i18']['ip_address']; ?></th>
                                <th><?php echo $GLOBALS['i18']['status']; ?></th>
                                <th><?php echo $GLOBALS['i18']['last_login_time']; ?></th>
                                <th width="25%">&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="edit_user" class="modal fade bs-modal-sm" role="dialog" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" action="#" id="frm_user" method="POST" autocomplete="off" class="form-horizontal form-bordered form-row-stripped">
                <input type="hidden" name="prim_uid" id="prim_uid" value="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title"><?php echo $GLOBALS['i18']['add_user']; ?></h4>
                </div>
                <div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo $GLOBALS['i18']['username']; ?> </label>
						<div class="col-md-7 col-sm-11 col-xs-11">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" class="form-control" name="username" id="username" value="">
							</div>
						</div>
					</div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['first_name']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="first_name" id="first_name" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['last_name']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="">
                            </div>
                        </div>
                    </div>
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo $GLOBALS['i18']['nice_username']; ?> </label>
						<div class="col-md-7 col-sm-11 col-xs-11">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" class="form-control" name="nice_username" id="nice_username" value="">
							</div>
						</div>
					</div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['password']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                <input type="password" class="form-control" name="password" id="password" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['email']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="text" class="form-control" name="email" id="email" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['ip_address']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon">IP</span>
                                <input type="text" class="form-control" name="ip_address" id="ip_address" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['pm_username']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="pm_username" id="pm_username" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['git_username_fino']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="git_username_fino" id="git_username_fino" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['git_username_simplessus']; ?> </label>
                        <div class="col-md-7 col-sm-11 col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="git_username_simplessus" id="git_username_simplessus" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-7 col-sm-11 col-xs-11 col-md-offset-4">
                            <label class="radio-inline"><input type="radio" value="1" name="status"><?php echo $GLOBALS['i18']['user_locked']; ?></label>
                            <label class="radio-inline"><input type="radio" value="0" name="status"><?php echo $GLOBALS['i18']['user_unlocked']; ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['permission']; ?> </label>
                    </div>
                    <select multiple="multiple" id="my-select" name="permissions[]">
                        <?php foreach($GLOBALS['config']['permission'] as $key => $value) { ?>
                            <option value='<?php echo $key; ?>'><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['ds_system']; ?> </label>
                    </div>
                    <select multiple="multiple" id="ds_system_select" name="selected_ds_system[]">
                        <?php foreach($GLOBALS['ds_systems'] as $system) { ?>
                            <option value='<?php echo $system['url']; ?>'><?php echo $system['name']; ?></option>
                        <?php } ?>
                    </select>

                    <div class="form-group">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['watch_system']; ?> </label>
                    </div>
                    <select multiple="multiple" id="watch_system_select" name="selected_watch_system[]">
                        <?php foreach($GLOBALS['watch_systems'] as $system) { ?>
                            <option value='<?php echo $system['url']; ?>'><?php echo $system['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn theme-color"><i class="fa fa-check"></i> <?php echo $GLOBALS['i18']['save']; ?></button>
                    <button type="button" class="btn default" data-dismiss="modal"><?php echo $GLOBALS['i18']['cancel']; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
/**
 * END - HTML Output
 */
include_once($GLOBALS['config']['cms']['design_path'] . 'base/footer.inc.php');
?>
