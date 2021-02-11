<?php
include_once('configs/config.inc.php');

// check for POST request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $GLOBALS['log']->error('FAILED - not POST - ' . $_SERVER['REQUEST_METHOD']);
    exit();
}

// get content type
$content_type = isset($_SERVER['CONTENT_TYPE']) ? strtolower(trim($_SERVER['CONTENT_TYPE'])) : '';

if ($content_type != 'application/json') {
    $GLOBALS['log']->error('FAILED - not application/json - ' . $content_type);
    exit();
}

// get payload
$payload = trim(file_get_contents("php://input"));

if (empty($payload)) {
    $GLOBALS['log']->error('FAILED - no payload');
    exit();
}

// get header signature
$header_signature = isset($_SERVER['HTTP_X_GITEA_SIGNATURE']) ? $_SERVER['HTTP_X_GITEA_SIGNATURE'] : '';

if (empty($header_signature)) {
    $GLOBALS['log']->error('FAILED - header signature missing');
    exit();
}

// calculate payload signature
$payload_signature = hash_hmac('sha256', $payload, $GLOBALS['config']['gitea']['secret_key'], false);

// check payload signature against header signature
if ($header_signature != $payload_signature) {
    $GLOBALS['log']->error('FAILED - payload signature');
    exit();
}

// convert json to array
$decoded = json_decode($payload, true);

// check for json decode errors
if (json_last_error() !== JSON_ERROR_NONE) {
    $GLOBALS['log']->error('FAILED - json decode - ' . json_last_error());
    exit();
}

// insert git event info into "git_commits" table

if(isset($decoded['ref'])) {
    $ref_branch = '';
    $tmp = explode('/', $decoded['ref']);
    $ref_branch = trim(strtolower($tmp[2]));

    if ($ref_branch == 'master') {

        $versionFile = $GLOBALS['config']['cms']['cache_folder'] . 'releaseVersions.json';
        if (file_exists($versionFile)) {
            $versions = json_decode(io_file_get_contents($versionFile), true);
        } else {
            $versions = array();
        }
        $version = '1.0.' . time(); // always changing version so it does not stop releases

        if (!empty($decoded['commits'])) {

            foreach ($decoded['commits'] as $commit_key => $commit) {

                if (empty($commit['added']) && empty($commit['removed']) && empty($commit['modified']) && (
                        stripos($commit['message'], 'merge commit') !== false ||
                        stripos($commit['message'], 'merge pull') !== false
                    )) {
                    continue;
                }

                $record = array();
                $record['commit_message'] = trim($commit['message']);
                $record['commit_url'] = trim($commit['url']);
                $record['timestamp'] = trim($commit['timestamp']);
                $record['author'] = trim($commit['author']['name']);

                if (empty($record['author']) || $record['author'] === 'unknown') {
                    $record['author'] = trim($commit['committer']['name']);
                    if (empty($record['author']) || $record['author'] === 'unknown') {
                        $record['author'] = trim($commit['author']['username']);
                        if (empty($record['author']) || $record['author'] === 'unknown') {
                            $record['author'] = trim($commit['committer']['username']);
                        }
                    }
                }

                if ($record['author'] == 'jenkins') {
                    continue;
                }

                if (!empty($decoded['repository'])) {
                    $record['repository'] = trim($decoded['repository']['full_name']);
                    $record['branch'] = trim($decoded['repository']['default_branch']);

                    $config_folder = $GLOBALS['config']['cms']['base_path'] . 'configs/';
                    $merge_back_review_json = $config_folder . 'merge_back_review.json';

                    if (file_exists($merge_back_review_json)) {
                        $string = file_get_contents($merge_back_review_json);
                        $merge_back_review_contents = json_decode($string, true);

                        if (!empty($merge_back_review_contents)) {
                            foreach ($GLOBALS['config']['releases_data'] as $project_key => $project) {
                                foreach ($project['components'] as $component_key => $component) {
                                    if (isset($merge_back_review_contents[$project['projectName']]) && is_array($merge_back_review_contents[$project['projectName']]) && in_array($component['name'],
                                            $merge_back_review_contents[$project['projectName']], false)) {
                                        $GLOBALS['config']['releases_data'][$project_key]['components'][$component_key]['merge_back_review'] = true;
                                    }
                                }
                            }
                        }
                    }

                    foreach ($GLOBALS['config']['releases_data'] as $releases) {
                        if (!empty($releases['components'])) {
                            foreach ($releases['components'] as $component) {
                                if ($component['jenkinsJobId'] == $record['repository']) {
                                    $prodKey = lcfirst(str_replace(' ', '',
                                        ucwords(strtolower($releases['projectName']))));
                                    if (isset($versions[$prodKey]['all'])) {
                                        $version = $versions[$prodKey]['all'];
                                    }
                                    $tmp = explode('/', '/' . $component['jenkinsJobId']);
                                    if (isset($versions[$prodKey][$tmp[2]])) {
                                        $version = $versions[$prodKey][$tmp[2]];
                                    }

                                    if (isset($component['merge_back_review']) && $component['merge_back_review']) {
                                        $record['merge_back_reviewed'] = 0;
                                    }

                                    $record['version'] = trim($version);
                                    $record['project'] = $releases['projectName'];
                                    $record['published'] = 0;
                                    $GLOBALS['db']->autoexecute('git_commits', $record, 'INSERT');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}