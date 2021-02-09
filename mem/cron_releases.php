<?php
/**
 * cron runs every 30 minutes to send release message to slack
 *
 * @package        gmi
 * @subpackage    master-ds
 *
 * @copyright    GetMyInvoices
 */

include_once 'configs/config.inc.php';

//  Cron start ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-releases/b');

if (!empty($GLOBALS['config']['releases_data'])) {
    $payload = array();
    $payload['icon_emoji'] = $GLOBALS['config']['release_slack']['icon'];
    $payload['username'] = $GLOBALS['config']['release_slack']['username'];
    $payload['as_user'] = "false";
    $payload['blocks'] = array();

    $versions = '';
    $versionFile = $GLOBALS['config']['cms']['cache_folder'] . 'releaseVersions.json';
    if (file_exists($versionFile)) {
        $versions = json_decode(io_file_get_contents($versionFile), true);
    }

    $available_git_commit_ids = array();

    foreach ($GLOBALS['config']['releases_data'] as $releases) {

        $project_section = array();
        $project_valid = false;
        $project_el_section = array();
        $send_to_slack_flag = false;

        if (!empty($releases['components'])) {
            foreach ($releases['components'] as $component) {

                $component_valid = false;
                $component_section = array();

                $git_commits = $GLOBALS['db']->GetAll("SELECT * FROM git_commits WHERE repository='" . $component['jenkinsJobId'] . "' AND project='" . $releases['projectName'] . "' AND published='0' ORDER BY timestamp DESC");

                $commit_message = '';
                $commit_version = '';
                $version = '';
                $version_valid = false;

                if ($versions != '') {
                    $prodKey = lcfirst(str_replace(' ', '', ucwords(strtolower($releases['projectName']))));
                    if (isset($versions[$prodKey]['all'])) {
                        $version = $versions[$prodKey]['all'];
                    }
                    $tmp = explode('/', '/' . $component['jenkinsJobId']);
                    if (isset($versions[$prodKey][$tmp[2]])) {
                        $version = $versions[$prodKey][$tmp[2]];
                    }
                }

                if (count($git_commits) > 0) {
                    foreach ($git_commits as $git_commit) {
                        $commit_message .= $git_commit['commit_message'] . ' (' . $git_commit['author'] . ')' . "\n";
                        $commit_version = $git_commit['version'];

                        if ($releases['autoRelease'] || $commit_version != $version) {
                            $available_git_commit_ids[] = $git_commit['prim_uid'];
                        }
                    }
                    if ($releases['autoRelease'] || $commit_version != $version) {
                        $send_to_slack_flag = true;
                        $project_valid = true;
                        $component_valid = true;

                        if (!$releases['autoRelease'] && $commit_version != $version) {
                            $version_valid = true;
                        }
                    }
                }

                if ($component_valid) {
                    $component_section['type'] = 'section';
                    $component_section['text'] = array();
                    $component_section['text']['type'] = 'mrkdwn';
                    $component_title_suffix = '';
                    if ($version_valid) {
                        $component_title_suffix = ' - ' . $version;
                    }
                    $component_text = '*' . trim($component['name']) . $component_title_suffix . " - Changes:* \n" . trim($commit_message);
                    $component_section['text']['text'] = $component_text;
                }

                if ($project_valid) {
                    $project_el_section[] = $component_section;
                }

            }

            if ($project_valid) {
                $divider_section = array();
                $divider_section['type'] = 'divider';
                $project_title = array();
                $project_title['type'] = 'section';
                $project_title['text'] = array();
                $project_title['text']['type'] = 'mrkdwn';
                $project_title['text']['text'] = '*' . trim($releases['projectName']) . '*';
                $project_section[] = $divider_section;
                $project_section[] = $project_title;

                foreach ($project_el_section as $key => $project_el) {
                    if (!empty($project_el)) {
                        $project_section[] = $project_el;
                    }
                }

                $payload['blocks'] = $project_section;
            }
        }

        if ($send_to_slack_flag) {
            $additional_headers = array();
            $additional_headers[] = "Content-Type: " . $GLOBALS['config']['release_slack']['content_type'];
            $response = network_http_request($GLOBALS['config']['release_slack']['url'], $additional_headers,
                json_encode($payload));

            $array = explode("\n", $response);
            if (strtolower(end($array)) == 'ok') {
                foreach ($available_git_commit_ids as $key => $available_git_commit_id) {
                    $_record = array();
                    $_record['published'] = 1;
                    $GLOBALS['db']->autoexecute('git_commits', $_record, 'UPDATE',
                        'prim_uid = ' . $available_git_commit_id);
                }
            }
        }
    }
}

//  Cron end ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-releases/e');