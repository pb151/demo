<?php
/**
 * Scaling logs
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

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['scaling_logs'].' - '.$GLOBALS['config']['cms']['title'];

verify_login();
verify_has_access(array('scaling_logs'));
$selected_menu = 'scaling_logs';

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
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'].'js/scaling_logs.js';
/**
 * END - Include JS
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
						<span class="caption-subject font-green-steel bold uppercase"><?php echo $GLOBALS['i18']['scaling_logs']; ?></span>
					</div>
					<div class="actions">
						<a onclick="grid_filter();"><i class="fa fa-refresh"></i></a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Search</label>
								<div>
									<input type="text" name="filter_txt" id="filter_txt" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Env</label>
								<div>
									<select name="filter_env" id="filter_env" class="form-control bs-select" data-size="8" data-live-search="true">
                                        <?php foreach($GLOBALS['config']['worker_scaling'] as $env=>$item) { ?>
											<option value="<?php echo $env;?>"><?php echo $env;?></option>
                                        <?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Date Start</label>
								<div>
									<input type="text" name="filter_date_start" id="filter_date_start"
										   class="form-control date-picker">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label">Date End</label>
								<div>
									<input type="text" name="filter_date_end" id="filter_date_end"
										   class="form-control date-picker">
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
					<table class="table table-striped table-bordered table-hover" id="tbl_scaling_log">
						<thead>
						<tr>
							<th>Log Time</th>
							<th>Description</th>
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
