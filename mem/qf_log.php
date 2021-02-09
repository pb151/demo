<?php
/**
 * QF log
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

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['qf_log'].' - '.$GLOBALS['config']['cms']['title'];

verify_login();
verify_has_access(array('qf_log'));
$selected_menu = 'qf_log';

/**
 * BEGIN - Include CSS
 */
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/datatables/datatables.min.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/bootstrap-select/css/bootstrap-select.min.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css';

include_once($GLOBALS['config']['cms']['design_path'].'base/header.inc.php');
/**
 * END - Include CSS
 */

/**
 * BEGIN - Include JS
 */
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'global/scripts/datatable.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/datatables/datatables.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/bootstrap-select/js/bootstrap-select.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'].'global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'].'js/qf_log.js';
/**
 * END - Include JS
 */

$envs = db_get_all("SELECT Distinct env_name FROM qf_log");

/**
 * BEGIN - HTML Output
 */
$users = get_all_users();
?>

	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption caption-md">
						<span class="caption-subject font-green-steel bold uppercase"><?php echo $GLOBALS['i18']['qf_log']; ?></span>
						<span class="caption-helper font-green-steel bold">These logs are updated every hour.</span>
					</div>
					<div class="actions">
						<a onclick="grid_filter();"><i class="fa fa-refresh"></i></a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Search</label>
								<div>
									<input type="text" name="filter_text" id="filter_text" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Env</label>
								<div>
									<select name="filter_env" id="filter_env" class="form-control bs-select" data-size="8" data-live-search="true">
										<option value="">All</option>
									<?php foreach($envs as $env) { ?>
										<option value="<?php echo $env['env_name'];?>"><?php echo $env['env_name'];?></option>
									<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Date Start</label>
								<div>
									<input type="text" name="filter_date_start" id="filter_date_start" class="form-control date-picker">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Date End</label>
								<div>
									<input type="text" name="filter_date_end" id="filter_date_end" class="form-control date-picker">
								</div>
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="control-label"></label>
								<div>
									<button class="btn theme-color" onclick="grid_filter();"><i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
					</div>
					<table class="table table-striped table-bordered table-hover" id="tbl_qf_log">
						<thead>
						<tr>
							<th>ID</th>
							<th>Env Name</th>
							<th>Type</th>
							<th>Portal</th>
							<th>Status</th>
							<th>Username</th>
							<th>Assignee</th>
							<th>Created</th>
							<th>Started</th>
							<th>Code Requested</th>
							<th>Code Received</th>
							<th>Responded</th>
							<th>Login Success</th>
							<th>Completed</th>
							<th></th>
						</tr>
						</thead>
					</table>
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
