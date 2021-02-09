<?php
/**
 * cron runs every 60 minutes
 *
 * @package        gmi
 * @subpackage    master-ds
 *
 * @copyright    GetMyInvoices
 */

include_once __DIR__ . '/configs/config.inc.php';

//  Cron start ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-cron/b');

// Clear up expired sessions
db_execute(db_prepare_statement('DELETE FROM core_sessions WHERE expiry < NOW()'));

// Clean up unused sessions after 1 hour
db_execute(db_prepare_statement('DELETE FROM core_sessions WHERE created < DATE_SUB(NOW(), INTERVAL 1 HOUR) AND user_name=""'));

// Clean 6months old QF logs
db_execute("DELETE FROM `qf_log` WHERE created <= CURDATE() - INTERVAL 180 DAY");

// Lock expired users (older than 90 days)
$expired_timestamp = time() - 90 * 24 * 60 * 60;
$users_data = $GLOBALS['db']->GetAll("SELECT * FROM user WHERE last_login_time < " . $expired_timestamp . " AND last_login_time > 0");

if (!empty($users_data)) {
    foreach ($users_data as $key => $user_data) {
        $user_record = array();
        $user_record['status'] = 1;
        $GLOBALS['db']->autoexecute('user', $user_record, 'UPDATE', ' prim_uid=' . (int)$user_data['prim_uid']);

        $user_locking_record = array();
        $user_locking_record['user_locked'] = 1;
        $user_locking_record['locked_at'] = date_time_get_time();
        $GLOBALS['db']->autoexecute("user_locking", $user_locking_record, "UPDATE",
            " username='" . trim($user_data['username']) . "' ");
    }
}

//  Cron end ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-cron/e');