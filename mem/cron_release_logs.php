<?php
/**
 * cron runs every 15 minutes
 *
 * @package        gmi
 * @subpackage    master-ds
 *
 * @copyright    GetMyInvoices
 */

include_once __DIR__ . '/configs/config.inc.php';

//  Cron start ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-release-logs/b');

// Clean release logs for not needed data
$max = time() - (86400 * 7);
db_execute("DELETE FROM `release_logs` WHERE timestamp <= " . $max);

// send modified files into slack
if (!empty($GLOBALS['config']['releases_data'])) {
    $payload = array();
    $payload['icon_emoji'] = $GLOBALS['config']['alert_slack']['icon'];
    $payload['username'] = $GLOBALS['config']['alert_slack']['username'];
    $payload['as_user'] = "false";
    $payload['blocks'] = array();

    $setPublished = array();

    foreach ($GLOBALS['config']['releases_data'] as $releases) {
        $project_section = array();
        $project_el_section = array();
        $send_to_slack_flag = false;
        $record_prim_uids = array();

        if (!isset($releases['hook_url'], $releases['alert']) || (isset($releases['alert']) && !$releases['alert'])) {
            continue;
        }

        if (!empty($releases['components'])) {
            foreach ($releases['components'] as $component) {
                $component_valid = false;
                $component_section = array();

                if (isset($component['alert']) && $component['alert']) {
                    $component_section['type'] = 'section';
                    $component_section['text'] = array();
                    $component_section['text']['type'] = 'mrkdwn';

                    $release_logs = db_get_all("SELECT * FROM release_logs WHERE project = '" . trim($releases['projectName']) . "' AND component = '" . trim($component['name']) . "' AND published != 1 LIMIT 1");
                    $modified_files = '';

                    foreach ($release_logs as $key => $release_log) {
                        if (strlen($release_log['log_text']) > 15 && strpos(trim($release_log['log_text']),
                                'File changes:') === 0) {
                            $modified_files = $release_log['log_text'];

                            if (!empty($modified_files)) {
                                $component_text = '*' . trim($component['name']) . ' - ' . trim($release_log['component_name']) . "*\n" . trim($modified_files);
                                $component_text = rtrim($component_text, '/');
                                $component_section['text']['text'] = $component_text;
                                $project_el_section[] = $component_section;
                            }

                            $record_prim_uids[] = $release_log['prim_uid'];
                        }

                        if (trim($release_log['log_text']) === 'File changes:') {
                            $setPublished[] = (int)$release_log['prim_uid'];
                        }
                    }

                    if(!empty($project_el_section)) {
                        break;
                    }
                }
            }

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

        if (isset($releases['hook_url']) && !empty($releases['hook_url']) && isset($releases['alert']) && $releases['alert'] && !empty($project_el_section)) {
            $send_to_slack_flag = true;
        }

        if ($send_to_slack_flag) {
            $additional_headers = array();
            $additional_headers[] = "Content-Type: " . $GLOBALS['config']['release_slack']['content_type'];
            $response = network_http_request($releases['hook_url'], $additional_headers,
                json_encode($payload));

            $array = explode("\n", $response);
            if (!empty($record_prim_uids) && (strtolower(end($array)) === 'ok' || strtolower(end($array)) === 'invalid_blocks')) {
                foreach ($record_prim_uids as $key => $record_prim_uid) {
                    $_record = array();
                    $_record['published'] = 1;
                    $GLOBALS['db']->autoexecute('release_logs', $_record, 'UPDATE', 'prim_uid = ' . $record_prim_uid);
                }
            }
        }
    }

    if (!empty($setPublished)) {
        db_execute('UPDATE release_logs SET published=1 WHERE prim_uid IN (' . implode(',', $setPublished) . ')');
    }
}

//  Cron end ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-release-logs/e');
