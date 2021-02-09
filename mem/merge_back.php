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
verify_has_access(array('ustack'));
$selected_menu = 'merge_back';

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['merge_back'] . ' - ' . $GLOBALS['config']['cms']['title'];

/**
 * BEGIN - Include CSS
 */
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/uniform/css/uniform.default.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/datatables.min.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css';
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['design_path'] .'js/plugins/multi-select/multi-select.css';
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
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/select2/js/select2.full.min.js';
$GLOBALS['cms']['includeJS'][] = $GLOBALS['config']['cms']['design_path'] . 'js/merge_backs.js';
/**
 * END - Include JS
 */
/**
 * BEGIN - Business Logic
 */

$where = " WHERE gc.prim_uid > 0 AND gc.merge_back_reviewed = 0";

$all_repos_sql = "SELECT DISTINCT gc.repository ";
$all_repos_sql .= "FROM git_commits gc ";
$all_repos_sql .= $where;
$all_repos_sql .= " ORDER BY gc.repository asc";
$git_repos = $GLOBALS['db']->GetAll($all_repos_sql);

$all_repos = array();
foreach ($git_repos as $key => $git_repo) {
    $all_repos[] = $git_repo['repository'];
}

if(!isset($_SESSION['filter_store']['merge_back'])) {
    $_SESSION['filter_store']['merge_back'] = array(
        'repository'       => ''
    );
}

if($_SESSION['filter_store']['merge_back']['repository'] != '') {
    $where .= " AND LOWER(gc.repository) LIKE '%".trim(strtolower(db_escape_string($_SESSION['filter_store']['merge_back']['repository'])))."%' ";
}

$merge_back_count = 0;
$merge_back_count_sql = "SELECT COUNT(gc.prim_uid) ";
$merge_back_count_sql .= "FROM git_commits gc ";
$merge_back_count_sql .= $where;
$merge_back_count = $GLOBALS['db']->GetOne($merge_back_count_sql);

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
                        <?php echo $GLOBALS['i18']['merge_back']; ?>
                        (<span id="merge_back_count"><?php echo $merge_back_count; ?></span>)
                    </span>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <a class="btn theme-color" href="#merge_back_setting_modal" data-toggle="modal"><i class="fa fa-gear"></i></a>
                    <button class="btn theme-color" onclick="grid_filter();"><i class="fa fa-refresh"></i> </button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label"><?php echo $GLOBALS['i18']['repository']; ?></label>
                            <select name="repository_filter" id="repository_filter" class="form-control bs-select" data-size="8" data-live-search="true">
                                <option value="">All</option>
                                <?php
                                    foreach ($all_repos as $key => $repo) {
                                ?>
                                        <option value="<?php echo $repo; ?>" <?php echo(($_SESSION['filter_store']['merge_back']['repository'] == $repo) ? ' selected' : ''); ?>>
                                            <?php echo $repo; ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-container" id="tbl_merge_back_container">
                    <table class="table table-striped table-bordered table-hover" id="tbl_merge_back">
                        <thead>
                            <tr>
                                <th><?php echo $GLOBALS['i18']['repository']; ?></th>
                                <th><?php echo $GLOBALS['i18']['message']; ?></th>
                                <th><?php echo $GLOBALS['i18']['commit_url']; ?></th>
                                <th><?php echo $GLOBALS['i18']['date']; ?></th>
                                <th><?php echo $GLOBALS['i18']['actions']; ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="merge_back_setting_modal" class="modal fade bs-modal-sm" role="dialog" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $GLOBALS['i18']['merge_back_setting']; ?></h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="tbl_merge_back">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="" class="merge-back-review-set-all"></th>
                            <th><?php echo $GLOBALS['i18']['project_name']; ?></th>
                            <th><?php echo $GLOBALS['i18']['component_name']; ?></th>
                            <th><?php echo $GLOBALS['i18']['repository']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $releases_data = $GLOBALS['config']['releases_data'];

                        $merge_back_review_contents = array();
                        $config_folder = $GLOBALS['config']['cms']['base_path'].'configs/';
                        $merge_back_review_json = $config_folder . 'merge_back_review.json';
                        if (file_exists($merge_back_review_json)) {
                            $string = file_get_contents($merge_back_review_json);
                            $merge_back_review_contents = json_decode($string, true);
                        }

                        foreach ($releases_data as $releases) {
                            if (!empty($releases['components'])) {
                                foreach ($releases['components'] as $component) {
                                    $checked = '';
                                    if (!empty($merge_back_review_contents)) {
                                        if (isset($merge_back_review_contents[$releases['projectName']])) {
                                            if (in_array($component['name'], $merge_back_review_contents[$releases['projectName']])) {
                                                $checked = 'checked';
                                            }
                                        }
                                    } else {
                                        if (isset($component['merge_back_review']) && $component['merge_back_review']) {
                                            $checked = 'checked';
                                        }
                                    }
                        ?>
                            <tr class="component-tr">
                                <td>
                                    <input type="checkbox" name="" class="merge-back-review-set" <?php echo $checked; ?>>
                                </td>
                                <td class="project-name">
                                    <?php echo $releases['projectName']; ?>
                                </td>
                                <td class="component-name">
                                    <?php echo $component['name']; ?>
                                </td>
                                <td class="component-repo">
                                    <?php echo $component['jenkinsJobId']; ?>
                                </td>
                            </tr>
                        <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn theme-color" onclick="merge_back_setting_save()"><i class="fa fa-check"></i> <?php echo $GLOBALS['i18']['save']; ?></button>
                <button type="button" class="btn default" data-dismiss="modal"><?php echo $GLOBALS['i18']['cancel']; ?></button>
            </div>
        </div>
    </div>
</div>

<div id="merge_back_publish_modal" class="modal fade" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <input type="hidden" name="git_uid" id="git_uid" value="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $GLOBALS['i18']['merge_back_publish'];?></h4>
            </div>
            <div class="modal-body">
                <div class="row mt10">
                    <div class="form-group">
                        <label class="control-label col-md-4 text-right"><?php echo $GLOBALS['i18']['username'];?> </label>
                        <div class="col-md-6">
                            <select name="git_username" id="git_username" class="form-control" data-live-search="true" data-size="8">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="form-group">
                        <label class="control-label col-md-4 text-right"><?php echo $GLOBALS['i18']['title'];?></label>
                        <div class="col-md-6">
                            <input type="text" name="git_title" id="git_title" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="form-group">
                        <label class="control-label col-md-4 text-right"><?php echo $GLOBALS['i18']['description'];?> </label>
                        <div class="col-md-6">
                            <textarea name="git_desc" id="git_desc" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn theme-color" onclick="merge_back_publish()"><i class="fa fa-check"></i> <?php echo $GLOBALS['i18']['save'];?></button>
                <button type="button" class="btn default" data-dismiss="modal"><?php echo $GLOBALS['i18']['cancel'];?></button>
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
