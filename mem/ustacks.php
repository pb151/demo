<?php
/**
 * UStack List
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
verify_has_access(array('ustack'));
$selected_menu = 'ustack';

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['ustack'] . ' - ' . $GLOBALS['config']['cms']['title'];

/**
 * BEGIN - Include CSS
 */
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/uniform/css/uniform.default.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/datatables.min.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['design_path'] .'js/plugins/multi-select/multi-select.css';
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
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'] . 'js/plugins/multi-select/jquery.multi-select.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/bootstrap-select/js/bootstrap-select.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/select2/js/select2.full.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'] . 'js/ustacks.js';
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
                    <span class="caption-subject font-green-steel bold uppercase"><?php echo $GLOBALS['i18']['ustack']; ?></span>
                    <span class="caption-helper"><?php echo $GLOBALS['i18']['ustack_helptext']; ?></span>
                </div>
                <div class="actions">
                    <button data-container="body" data-placement="bottom" class="btn theme-color tooltips" title="<?php echo $GLOBALS['i18']['add_ustack']; ?>" onclick="add_ustack();"><i class="fa fa-plus"></i> </button>
                    <button class="btn theme-color" onclick="grid_filter();"><i class="fa fa-refresh"></i> </button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container" id="tbl_ustack_container">
                    <table class="table table-striped table-bordered table-hover" id="tbl_ustack">
                        <thead>
                            <tr>
                                <th><?php echo $GLOBALS['i18']['component_name']; ?></th>
                                <th><?php echo $GLOBALS['i18']['repository']; ?></th>
                                <th><?php echo $GLOBALS['i18']['related_gits']; ?></th>
                                <th width="25%">&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit_ustack" class="modal fade bs-modal-lg" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <input type="hidden" name="prim_uid" id="prim_uid" value="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $GLOBALS['i18']['add_ustack']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group mt10">
                    <div class="row">
                        <label class="control-label col-md-4"><?php echo $GLOBALS['i18']['component_name']; ?> </label>
                        <div class="col-md-6 col-sm-10 col-xs-10">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-font"></i></span>
                                <input type="text" class="form-control" name="component_name" id="component_name" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mt10">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
                            <button type="button" class="btn theme-color" onclick="add_related_git();"><i class="fa fa-plus"></i><?php echo $GLOBALS['i18']['new']; ?> </button>
                        </div>
                    </div>
                    <div class="row" id="div_related_gits">
                        <div class="col-md-12 col-sm-12 col-xs-12 mt10"><?php echo $GLOBALS['i18']['related_gits']; ?> : </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
                            <table class="table table-striped table-bordered table-hover" id="tbl_related_gits">
                                <thead>
                                    <tr> 
                                        <th><?php echo $GLOBALS['i18']['repository']; ?></th>
                                        <th><?php echo $GLOBALS['i18']['git_user']; ?></th>
                                        <th><?php echo $GLOBALS['i18']['pm_project']; ?></th>
                                        <th><?php echo $GLOBALS['i18']['pm_user']; ?></th>
                                        <th width="15%">&nbsp;</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn theme-color" id="edit_ustack_btn"><i class="fa fa-check"></i> <?php echo $GLOBALS['i18']['save']; ?></button>
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
