<?php
/**
 * Portal Queue
 *
 * @package		gmi
 * @subpackage	master-ds
 *
 * @copyright	GetMyInvoices
 */

$defaultVersion = '1.0.'.time(); // always changing version so it does not stop releases
if(isset($_GET['releaseVersionOf'])) {
    $versionFile = __DIR__.'/cache/releaseVersions.json';
    if(file_exists($versionFile)) {
        $versions = json_decode(file_get_contents($versionFile), true);
        if(isset($versions[$_GET['releaseVersionOf']])) {
            $defaultVersion = $versions[$_GET['releaseVersionOf']];
            if(isset($_GET['component'], $defaultVersion[$_GET['component']])) {
                $defaultVersion = $defaultVersion[$_GET['component']];
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array('version' => $defaultVersion));
    die;
}

/**
 * Include configuration
 */
include_once('configs/config.inc.php');

verify_login();
verify_has_access(array('releases'));
$selected_menu = 'releases';

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['releases'] . ' - ' . $GLOBALS['config']['cms']['title'];

if(isset($_POST, $_GET['release']) && !empty($_POST)) {
    $prodKey = lcfirst(str_replace(' ', '', ucwords($_GET['release'])));
    $url = 'releases.php?release=' . $_GET['release'] . '&saved=1';

    $versionFile = $GLOBALS['config']['cms']['cache_folder'] . 'releaseVersions.json';
    if (file_exists($versionFile)) {
        $versions = json_decode(io_file_get_contents($versionFile), true);
    } else {
        $versions = array();
    }

    foreach ($GLOBALS['config']['releases_data'] as $releases) {
        if (has_access('releases_' . strtolower($releases['projectName'])) && !empty($releases['components']) && strtolower($releases['projectName']) == $_GET['release']) {
            if (!isset($versions[$prodKey])) {
                $versions[$prodKey] = array();
            }
            foreach ($releases['components'] as $component) {
                $tmp = explode('/', '/' . $component['jenkinsJobId']);

                if (isset($_POST[$prodKey]['all'])) {
                    $versions[$prodKey][$tmp[2]] = trim($_POST[$prodKey]['all']);
                    $versions[$prodKey]['all'] = trim($_POST[$prodKey]['all']);
                } else {
                    $versions[$prodKey][$tmp[2]] = trim($_POST[$prodKey][$tmp[2]]);
                }
            }
        }
    }

    if (!empty($versions)) {
        io_file_put_contents($versionFile, json_encode($versions));
    }

    network_redirect($url);
    die;
}

/**
 * BEGIN - Include CSS
 */
$GLOBALS['cms']['includeCSS'][] = $GLOBALS['config']['cms']['theme_path'] . 'global/plugins/uniform/css/uniform.default.css';

include_once($GLOBALS['config']['cms']['design_path'] . 'base/header.inc.php');
/**
 * END - Include CSS
 */

/**
 * Start - HTML Output
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <span class="caption-subject font-green-steel bold uppercase"><?php echo $GLOBALS['i18']['releases']; ?></span>
                    <span class="caption-helper"><?php echo $GLOBALS['i18']['releases_help_text']; ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <?php
                if(isset($_GET['release'])) {
                    if(isset($_GET['saved'])) {
                        echo '<div class="alert alert-warning">'.$GLOBALS['i18']['release_saved_info'].'</div><br>';
                    }

                    $prodKey = lcfirst(str_replace(' ', '', ucwords($_GET['release'])));
                    $url = 'releases.php?release=' . $_GET['release'];

                    $versionFile = $GLOBALS['config']['cms']['cache_folder'].'releaseVersions.json';
                    $versions = array();
                    if(file_exists($versionFile)) {
                        $versions = json_decode(io_file_get_contents($versionFile), true);
                    }

                    foreach($GLOBALS['config']['releases_data'] as $releases) {
                        if(has_access('releases_'.strtolower($releases['projectName'])) && !empty($releases['components']) && strtolower($releases['projectName']) == $_GET['release']) {
                            echo '<b>' . $releases['projectName'] . ':</b><br />';

                            $version = $defaultVersion;
                            if(isset($versions[$prodKey]['all'])) {
                                $version = $versions[$prodKey]['all'];
                            }

                            echo '<form class="form-horizontal" role="form" method="post" action="'.$url.'">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">'.$GLOBALS['i18']['release_set_version_for_all'].':</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control input-inline input-medium" placeholder="'.$version.'" value="'.$version.'" name="'.$prodKey.'[all]">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green">'.$GLOBALS['i18']['save'].'</button>
                                            <button type="button" class="btn default" onClick="window.location.href=\'releases.php\'">'.$GLOBALS['i18']['cancel'].'</button>
                                        </div>
                                    </div>
                                </div>
                            </form><hr>';


                            echo '<form class="form-horizontal" role="form" method="post" action="'.$url.'">
                                        <div class="form-body">';
                            array_key_multi_sort($releases['components'], 'name'); // Sort components by name
                            foreach ($releases['components'] as $component) {
                                $tmp = explode('/', '/'.$component['jenkinsJobId']);
                                $id = implode('/job/', $tmp);
                                $imageUrl = 'https://fino-jenkins.cloudspace.work/buildStatus/icon?job='.$component['jenkinsJobId'].'&style=flat-square&subject='.$component['name'].urlencode(' ${displayName} @ ${startTime} ago');

                                $version = $defaultVersion;
                                if(isset($versions[$prodKey][$tmp[2]])) {
                                    $version = $versions[$prodKey][$tmp[2]];
                                }

                                echo '<div class="form-group">
                                    <label class="col-md-3 control-label">'.$GLOBALS['i18']['release_set_version_for'].' "'.$tmp[2].'":</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control input-inline input-medium" placeholder="'.$version.'" value="'.$version.'" name="'.$prodKey.'['.$tmp[2].']">
                                        <span class="help-inline"> <a href="https://fino-jenkins.cloudspace.work'.$id.'" target="_blank"><img src="'.$imageUrl.'"></a> </span>
                                    </div>
                                </div>';
                            }
                            echo '</div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green">'.$GLOBALS['i18']['save'].'</button>
                                            <button type="button" class="btn default" onClick="window.location.href=\'releases.php\'">'.$GLOBALS['i18']['cancel'].'</button>
                                        </div>
                                    </div>
                                </div>
                            </form>';
                        }
                    }
                } else {
                    foreach ($GLOBALS['config']['releases_data'] as $releases) {
                        if (has_access('releases_' . strtolower($releases['projectName'])) && !empty($releases['components'])) {
                            $componentsText = '';
                            array_key_multi_sort($releases['components'], 'name'); // Sort components by name
                            foreach ($releases['components'] as $component) {
                                $tmp = explode('/', '/' . $component['jenkinsJobId']);
                                $id = implode('/job/', $tmp);

                                $imageUrl = 'https://fino-jenkins.cloudspace.work/buildStatus/icon?job=' . $component['jenkinsJobId'] . '&style=flat-square&subject=' . $component['name'] . urlencode(' ${displayName} @ ${startTime} ago');

                                $componentsText .= ((!empty($componentsText)) ? ' - ' : '') . '<a href="https://fino-jenkins.cloudspace.work' . $id . '" target="_blank"><img src="' . $imageUrl . '"></a>';
                            }

                            if (!empty($componentsText)) {
                                $extra = '';
                                if (isset($releases['autoRelease']) && !$releases['autoRelease']) {
                                    $extra = ' - <a href="releases.php?release=' . strtolower($releases['projectName']) . '">'.$GLOBALS['i18']['release_configure_version'].'</a>';
                                }

                                echo '<b>' . $releases['projectName'] . ':</b> ' . $extra . '<br />';
                                echo $componentsText . '<br /><br />';
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * END - HTML Output
 */
include_once($GLOBALS['config']['cms']['design_path'] . 'base/footer.inc.php');