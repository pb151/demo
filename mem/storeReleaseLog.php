<?php
include_once('configs/config.inc.php');

if (isset($_POST['access_token']) && $_POST['access_token'] == $GLOBALS['config']['cms']['access_token']) {
    $record = array();
    $record['project'] = str_ireplace('-Release', '', $_POST['project']);
    $record['component'] = $_POST['component'];
    $record['component_name'] = $_POST['component_name'];
    $record['log_text'] = trim($_POST['log_text']);
    $record['timestamp'] = time();
    $record['published'] = stripos($record['log_text'], 'File changes:') === false ? 1 : 0;
    if (trim($record['log_text']) === 'File changes:') {
        $record['published'] = 1;
    }
    $logs = trim(str_replace(array('File changes:', '-', 'files.json'), '', $record['log_text']));
    if(empty($logs)) {
        $record['published'] = 1;
    }
    db_autoexecute('release_logs', $record, 'INSERT');
    $id = db_insert_id();

    header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'id' => $id));
    die;
}
