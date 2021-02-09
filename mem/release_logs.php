<?php
/**
 * Merge Back List
 *
 * @package     gmi
 * @subpackage  master-ds
 *
 * @copyright   GetMyInvoices
 */

/**
 * Include configuration
 */
include_once('configs/config.inc.php');

verify_login();
verify_has_access(array('release_logs'));
$selected_menu = 'release_logs';

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['release_logs'] . ' - ' . $GLOBALS['config']['cms']['title'];

/**
 * BEGIN - Include CSS
 */
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/uniform/css/uniform.default.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/datatables.min.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/bootstrap-select/css/bootstrap-select.min.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/select2/css/select2.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/select2/css/select2-bootstrap.min.css';

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
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/bootstrap-select/js/bootstrap-select.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/select2/js/select2.full.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'] . 'js/release_logs.js';
/**
 * END - Include JS
 */
/**
 * BEGIN - Business Logic
 */

$all_projects_sql = "SELECT DISTINCT project ";
$all_projects_sql .= "FROM release_logs ";
$release_log_projects = $GLOBALS['db']->GetAll($all_projects_sql);

$all_projects = array();
foreach ($release_log_projects as $key => $release_log_project) {
    $all_projects[] = $release_log_project['project'];
}

$all_components_sql = "SELECT DISTINCT component ";
$all_components_sql .= "FROM release_logs ";
$release_log_components = $GLOBALS['db']->GetAll($all_components_sql);

$all_components = array();
foreach ($release_log_components as $key => $release_log_component) {
    $all_components[] = $release_log_component['component'];
}

$all_component_names_sql = "SELECT DISTINCT component_name ";
$all_component_names_sql .= "FROM release_logs ";
$release_log_component_names = $GLOBALS['db']->GetAll($all_component_names_sql);

$all_component_names = array();
foreach ($release_log_component_names as $key => $release_log_component_name) {
    $all_component_names[] = $release_log_component_name['component_name'];
}

if(!isset($_SESSION['filter_store']['release_logs'])) {
    $_SESSION['filter_store']['release_logs'] = array(
        'project'       => '',
        'component'       => '',
        'component_name'       => ''
    );
}

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
                    <span class="caption-subject font-green-steel bold uppercase">
                        <?php echo $GLOBALS['i18']['release_logs']; ?>
                    </span>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <button class="btn theme-color" onclick="grid_filter();"><i class="fa fa-refresh"></i> </button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo $GLOBALS['i18']['project']; ?></label>
                            <select name="project_filter" id="project_filter" class="form-control bs-select" data-size="8" data-live-search="true">
                                <option value="">All</option>
                                <?php
                                    foreach ($all_projects as $key => $individual_project) {
                                ?>
                                        <option value="<?php echo $individual_project; ?>" <?php echo(($_SESSION['filter_store']['release_logs']['project'] == $individual_project) ? ' selected' : ''); ?>>
                                            <?php echo $individual_project; ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo $GLOBALS['i18']['component']; ?></label>
                            <select name="component_filter" id="component_filter" class="form-control bs-select" data-size="8" data-live-search="true">
                                <option value="">All</option>
                                <?php
                                    foreach ($all_components as $key => $individual_component) {
                                ?>
                                        <option value="<?php echo $individual_component; ?>" <?php echo(($_SESSION['filter_store']['release_logs']['component'] == $individual_component) ? ' selected' : ''); ?>>
                                            <?php echo $individual_component; ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo $GLOBALS['i18']['component_name']; ?></label>
                            <select name="component_name_filter" id="component_name_filter" class="form-control bs-select" data-size="8" data-live-search="true">
                                <option value="">All</option>
                                <?php
                                    foreach ($all_component_names as $key => $individual_component_name) {
                                ?>
                                        <option value="<?php echo $individual_component_name; ?>" <?php echo(($_SESSION['filter_store']['release_logs']['component_name'] == $individual_component_name) ? ' selected' : ''); ?>>
                                            <?php echo $individual_component_name; ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-container" id="tbl_release_logs_container">
                    <table class="table table-striped table-bordered table-hover" id="tbl_release_logs">
                        <thead>
                            <tr>
                                <th><?php echo $GLOBALS['i18']['project']; ?></th>
                                <th><?php echo $GLOBALS['i18']['component']; ?></th>
		                        <th><?php echo $GLOBALS['i18']['component_name']; ?></th>
                                <th><?php echo $GLOBALS['i18']['actions']; ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="release_log_details" class="modal fade bs-modal-lg" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $GLOBALS['i18']['release_log_details']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group mt10">
                    <div class="row">
                        <div class="col-md-10">
                            <label class="control-label"><?php echo $GLOBALS['i18']['log_text']; ?>: </label>
                            <input type="text" name="log_content_filter" id="log_content_filter">
                        </div>
                    </div>
                </div>
                <div class="form-group mt10">
                    <div class="row" id="div_related_gits">
                        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
                            <table class="table table-striped table-bordered table-hover" id="tbl_release_log_details">
                                <thead>
                                    <tr> 
                                        <th><?php echo $GLOBALS['i18']['project']; ?></th>
                                        <th><?php echo $GLOBALS['i18']['component']; ?></th>
		                                <th><?php echo $GLOBALS['i18']['component_name']; ?></th>
		                                <th><?php echo $GLOBALS['i18']['log_text']; ?></th>
		                                <th><?php echo $GLOBALS['i18']['timestamp']; ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default cancel-btn" data-dismiss="modal"><?php echo $GLOBALS['i18']['cancel']; ?></button>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * END - HTML Output
 */
include_once($GLOBALS['config']['cms']['design_path'] . 'base/footer.inc.php');
?>
