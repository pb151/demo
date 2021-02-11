<?php

//Set session lifetime
if(isset($GLOBALS['config']['session.gc_maxlifetime'])) {
    @ini_set('session.gc_maxlifetime', $GLOBALS['config']['session.gc_maxlifetime']);
    @ini_set('session.cookie_lifetime', $GLOBALS['config']['session.gc_maxlifetime']);
}

include_once($GLOBALS['config']['cms']['base_path'].'src/includes/database.inc.php');
include_once($GLOBALS['config']['cms']['base_path'].'src/includes/session.inc.php');
include_once($GLOBALS['config']['cms']['base_path'].'src/includes/io.inc.php');
include_once($GLOBALS['config']['cms']['base_path'].'src/includes/network.inc.php');
include_once($GLOBALS['config']['cms']['base_path'].'src/includes/logging_engine.inc.php');
include_once($GLOBALS['config']['cms']['base_path'].'src/includes/date_time.inc.php');
include_once($GLOBALS['config']['cms']['base_path'].'src/includes/database_management.inc.php');

init_session();

if(apply_user_language()) {
    if(!isset($_SESSION['cms']['language_code'])) {
        if(isset($_SESSION['cms']['user']['prim_uid'])) {
            $_SESSION['cms']['language_code'] = $_SESSION['i18']['language'];
        } else {
            $browser_language = network_http_get_language(true);
            if(in_array($browser_language, $GLOBALS['config']['cms']['avail_lang'])) {
                $_SESSION['cms']['language_code'] = $browser_language;
            } else {
                $_SESSION['cms']['language_code'] = 'en_us';
            }
        }
    }

    if(isset($_GET['lang'])) {
        if(in_array($_GET['lang'], $GLOBALS['config']['cms']['avail_lang'])) {
            $_SESSION['cms']['language_code'] = $_GET['lang'];
        }
    }
} else {
    $_SESSION['cms']['language_code'] = 'en_us';
}

$GLOBALS['config']['nice_username'] = false;
if(isset($_SESSION['user_prim_uid'])) {
    $GLOBALS['config']['cms']['nice_username_file'] = $GLOBALS['config']['cms']['cache_folder'].'tmp/nice_username_'.(int)$_SESSION['user_prim_uid'].'.txt';
    if(io_file_exists($GLOBALS['config']['cms']['nice_username_file'])) {
        $GLOBALS['config']['nice_username'] = true;
    }
}

$_SESSION['cms']['language'] = $_SESSION['cms']['language_code'] == 'en_us' ? 1 : 2;

include($GLOBALS['config']['cms']['base_path'].'src/lang/'.strtolower($_SESSION['cms']['language_code']).'.php');

$GLOBALS['config']['permission'] = array();
if(!empty($GLOBALS['config']['allowed_permissions'])) {
    foreach($GLOBALS['config']['allowed_permissions'] as $permission_key) {
        $GLOBALS['config']['permission'][$permission_key] = $GLOBALS['i18'][$permission_key];
    }
    natcasesort($GLOBALS['config']['permission']);
}

/**
 * function for number formatting.
 *
 * @param   string  value
 * @param   bool    $fordb
 * @param   int     $dec
 * @return string
 */
function format_number($value, $fordb=false, $dec=2) {
    if($fordb) {
        if( $_SESSION['cms']['language_code'] == 'de_de') {
            $value = str_replace(".", "", $value);
            $value = str_replace(",", ".", $value);
            return $value;
        } else {
            return str_replace(",", "", $value);
        }
    } else {
        return number_format($value, $dec, $GLOBALS['i18']['decimal_separator'],$GLOBALS['i18']['thousand_separator']);
    }
}

include($GLOBALS['config']['cms']['base_path'].'src/lang/'.$_SESSION['cms']['language_code'].'.php');

// Include helper files
$helper_dir = $GLOBALS['config']['cms']['base_path'].'src/helpers/';
$dirs = io_search_directory('',$helper_dir);
foreach($dirs as $dir) {
    if(io_get_file_extension($helper_dir.basename($dir)) == 'php') {
        include_once($helper_dir.basename($dir));
    }
}

/**
 * Function that strips HTML-tags,
 * JS-Code, CSS Styles and more.
 *
 * @param   string  $value
 *
 * @return  string
 */
function strip_tags_ext($value) {
    $value = strip_tags($value);
    /** @noinspection CssInvalidAtRule */
    $search = array(
        '@<script[^>]*?>.*?</script>@si',
        // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',
        // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',
        // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'
        // Strip multi-line comments including CDATA
    );
    return preg_replace($search, ' ', $value);
}


function apply_user_language() {
    return false;
}


/**
 * Orders a multi dimensional array on the base of a label-key
 *
 * @param   mixed   The array to be ordered
 * @param   string  The label/key identifying the field
 * @param   int     Sortorder, use PHP constants here
 * @param   string  The ordering function to be used, strnatcasecmp() by default
 * @param   string  An optional secondary sort key
 * @param   string  An optional secondary sortorder
 *
 * @return  bool
 */
function array_key_multi_sort(&$arr, $l, $o=SORT_ASC, $f='strnatcasecmp', $l2 = '', $o2='') {
    if(!empty($l2)) {
        if($o2 == '') {
            $o2 = $o;
        }
        $secondary = ($o2 == SORT_ASC) ? "$f(\$a['$l2'], \$b['$l2']" : "$f(\$b['$l2'], \$a['$l2']";

        if($o == SORT_ASC) {
            return usort($arr, create_function('$a, $b', "return \$a['$l'] != \$b['$l'] ? $f(\$a['$l'], \$b['$l']) : ".$secondary.") ;" ));
        } else {
            return usort($arr, create_function('$a, $b', "return \$a['$l'] != \$b['$l'] ? $f(\$b['$l'], \$a['$l']) : ".$secondary.") ;" ));
        }
    } else {
        if($o == SORT_ASC) {
            return usort($arr, create_function('$a, $b', "return $f(\$a['$l'], \$b['$l']);"));
        } else {
            return usort($arr, create_function('$a, $b', "return $f(\$b['$l'], \$a['$l']);"));
        }
    }
}

// function to parse the http auth header
function http_digest_parse($txt) {
    // protect against missing data
    $needed_parts = array(
        'nonce'    => 1,
        'nc'       => 1,
        'cnonce'   => 1,
        'qop'      => 1,
        'username' => 1,
        'uri'      => 1,
        'response' => 1
    );
    $data = array();
    $keys = implode('|', array_keys($needed_parts));
    
    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }
    
    return $needed_parts ? false : $data;
}

// Add scheme to url if not present.
function addScheme($url, $scheme = 'http://') {
    return parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
}

function doLogout() {
    unset($_SESSION['user_prim_uid']);
    unset($_SESSION['username']);
    unset($_SESSION['change_password_required']);
    $_SESSION['cms']['logged_in'] = false;
}

function check_login($username, $password) {
    if (empty($username) || empty($password)) {
        // Wrong credentials
        return array(
            'success' => false,
            'message' => $GLOBALS['i18']['wrong_credentials']
        );
    }

    $hashed_password = security_hash_password($password);
    $user = $GLOBALS['db']->GetRow("SELECT COUNT(*) as count, username FROM user WHERE username='".db_escape_string(trim($username))."'");

    if ($user['count'] < 1) {
        // Wrong credentials
        return array(
            'success' => false,
            'message' => $GLOBALS['i18']['wrong_credentials']
        );
    }
    $_SESSION['username'] = $user['username'];
    $user_data = $GLOBALS['db']->GetRow("SELECT * FROM user WHERE username='".db_escape_string(trim($username))."' AND password='".db_escape_string(trim($hashed_password))."'");

    if (empty($user_data)) {
        // Wrong credentials
        // 
        $logdata['ip'] = get_client_ip();
        $logdata['reason'] = "Password is Wrong";
        create_sys_log('Log','Login Attempt',$logdata);

        update_user_lock($user['username'], false);
        return array(
            'success' => false,
            'message' => $GLOBALS['i18']['wrong_credentials']
        );
    }

    if($user_data['status'] != 0) {
        return array(
            'success' => false,
            'message' => $GLOBALS['i18']['wrong_credentials']
        );
    }

    //Check is User Locked
    $userRecordCount = $GLOBALS['db']->GetOne("SELECT COUNT(*) FROM user_locking WHERE username='".$user_data['username']."'");
    if($userRecordCount > 0) {
        $user_locking_data = $GLOBALS['db']->GetRow("SELECT * FROM user_locking WHERE username='".$user_data['username']."'");
        if((int)$user_locking_data['user_locked'] == 1) {
            return array(
                'success' => false,
                'message' => $GLOBALS['i18']['login_user_locked']
            );
        }
    }

    //Check User ip address
    if(!check_ip($user_data['username'])) {

        $logdata['ip'] = get_client_ip();
        $logdata['reason'] = $GLOBALS['i18']['login_ip_not_allowed'];
        create_sys_log('Log','Login Attempt',$logdata);
        
        return array(
            'success' => false,
            'message' => $GLOBALS['i18']['login_ip_not_allowed']
        );
    }

    // clear expired
    db_execute('DELETE FROM core_sessions WHERE expiry<NOW()');

    // update last login time
    $record = array();
    $record['last_login_time'] = time();
    $GLOBALS['db']->autoexecute('user', $record, 'UPDATE', ' prim_uid='.(int)$user_data['prim_uid']);

    update_user_lock($user_data['username'], true);
    
    if($user_data['change_password_required'] == 1) {
        $_SESSION['change_password_required'] = true;
    } else {
        $_SESSION['change_password_required'] = false;
    }
    
    
    $_SESSION['user_prim_uid'] = (int)$user_data['prim_uid'];
    $_SESSION['cms']['logged_in'] = true;

    return array(
        'success' => true,
        'message' => $GLOBALS['i18']['login_success']
    );
}

function update_user_lock($username, $isLoginCorrect) {
    $userRecordCount = $GLOBALS['db']->GetOne("SELECT COUNT(*) FROM user_locking WHERE username='".trim($username)."'");

    if($userRecordCount > 0) {
        $user_data = $GLOBALS['db']->GetRow("SELECT * FROM user_locking WHERE username='".trim($username)."'");

        $record = array();
        if($isLoginCorrect) {
            if((int)$user_data['login_failure'] < 3 || (int)$user_data['user_locked'] == 0) {
                $record['user_locked'] = 0;
                $record['login_failure'] = 0;
                $record['locked_at'] = 0;
            } else {
                return;
            }
        } else {
            if((int)$user_data['login_failure'] < 2) {
                $record['login_failure'] = (int)$user_data['login_failure'] + 1;
            } else {
                if((int)$user_data['login_failure'] == 2) {
                    $record['user_locked'] = 1;
                    $record['login_failure'] = 3;
                    $record['locked_at'] = date_time_get_time();
                } else {
                    return;
                }
            }
        }

        $GLOBALS['db']->autoexecute('user_locking', $record, 'UPDATE', ' prim_uid='.(int)$user_data['prim_uid']);

    } else {
        $record = array();
        $record['username'] = $username;

        if($isLoginCorrect) {
            $record['user_locked'] = 0;
            $record['login_failure'] = 0;
            $record['locked_at'] = 0;
        } else {
            $record['user_locked'] = 0;
            $record['login_failure'] = 1;
            $record['locked_at'] = 0;
        }

        $GLOBALS['db']->autoexecute('user_locking', $record, 'INSERT');
    }
}

function verify_login() {
    if(!isset($_SESSION['cms']['logged_in']) || !$_SESSION['cms']['logged_in'] || !verify_not_locked() || !isset($_SESSION['username']) || !check_ip($_SESSION['username'])) {
        $_SESSION['cms']['redirect_after_login'] = network_get_script_url(true);
        doLogout();
        network_redirect('login.php');
        die();
    } else {
        if($_SESSION['change_password_required']) {
            $_SESSION['cms']['redirect_after_login'] = network_get_script_url(true);
            network_redirect('change_password.php');
            die();
        }
    }

    // Increase session cookie lifetime when user is active
    if($_SESSION['cms']['logged_in'] && !isset($_REQUEST['action'])) {
        $ck = session_get_cookie_params();
        setcookie(session_name(), session_id(), (time() + $GLOBALS['config']['session.gc_maxlifetime']), $ck['path'], $ck['domain'], $ck['secure']);

        $session_data = db_get_row("SELECT * FROM core_sessions WHERE sesskey='" . db_escape_string(session_id()) . "'");
        if (!empty($session_data)) {
            $session_record = array();
            $session_record['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $session_record['user_ip'] = network_get_client_ip();
            $session_record['user_name'] = $_SESSION['username'];

            db_autoexecute('core_sessions', $session_record, 'UPDATE', 'sesskey=\'' . db_escape_string(session_id()) . '\'');
        }
    }
    
    // clear expired
    db_execute('DELETE FROM core_sessions WHERE expiry<NOW()');
}

function verify_not_locked() {
    if(isset($_SESSION['user_prim_uid'])) {
        $status = $GLOBALS['db']->GetOne("SELECT status FROM user WHERE prim_uid=".$_SESSION['user_prim_uid']);
        return $status == 0;
    } else {
        return false;
    }
}

function change_password($old_password, $new_password) {
    $hashed_old_password = security_hash_password($old_password);
    $hashed_new_password = security_hash_password($new_password);
    
    $user_data = $GLOBALS['db']->GetRow("SELECT * FROM user WHERE username='".db_escape_string(trim($_SESSION['username']))."' AND password='".db_escape_string(trim($hashed_old_password))."'");
    
    if (empty($user_data)) {
        // Wrong credentials
        return array('success' => false);
    }
    
    $record = array();
    $record['password'] = $hashed_new_password;
    $record['change_password_required'] = 0;
    
    $GLOBALS['db']->autoexecute('user', $record, 'UPDATE', ' prim_uid='.(int)$user_data['prim_uid']);
    
    $_SESSION['change_password_required'] = false;
    
    return array(
        'success' => true,
        'message' => $GLOBALS['i18']['password_changed_success']
    );
}

//Check if user Ip is allowed
function check_ip($username) {
    $userIpAddresses = $GLOBALS['db']->GetOne("SELECT ip_addresses FROM user WHERE username='".db_escape_string($username)."'");
    if(!empty($userIpAddresses)) {
        $user_ips = explode(',', $userIpAddresses);
        
        return in_array(get_client_ip(), $user_ips);
    }
    
    return true;
}

function verify_has_access ($access_keys = array()) {
    foreach($access_keys as $access_key) {
        if(has_access($access_key)) {
            return;
        }
    }
    network_redirect('dashboard.php');
    die();
}

/**
 * Validate if user has access
 *
 * @param string $access_key
 *
 * @return bool
 */
function has_access($access_key) {
    if($_SESSION['cms']['logged_in']) {

        $user_permitted_menus = array();

        if(!isset($_SESSION['user_prim_uid'])) {
            $user_data = $GLOBALS['db']->GetRow("SELECT * FROM user WHERE username='".db_escape_string(trim($_SESSION['username']))."'");

            if(!empty($user_data)) {

                if((int)$user_data['prim_uid'] > 0) {
                    $_SESSION['user_prim_uid'] = (int)$user_data['prim_uid'];
                }
            }
        }

        if((int)$_SESSION['user_prim_uid'] > 0) {
            // write permission into $GLOBALS so we can save some database requests
            if(isset($GLOBALS['permission_cache'][(int)$_SESSION['user_prim_uid']])) {
                $user_permitted_menus = $GLOBALS['permission_cache'][(int)$_SESSION['user_prim_uid']];
            } else {
                $user_permitted_menus_db = $GLOBALS['db']->GetAll("SELECT permission FROM permission WHERE user_uid='".(int)$_SESSION['user_prim_uid']."'");
                foreach($user_permitted_menus_db as $permission) {
                    if(in_array($permission['permission'], $GLOBALS['config']['allowed_permissions'])) {
                        $user_permitted_menus[] = $permission['permission'];
                    }
                }
                $GLOBALS['permission_cache'][(int)$_SESSION['user_prim_uid']] = $user_permitted_menus;
            }
        }

        return in_array($access_key, $user_permitted_menus) ? true : false;
    }
    return false;
}

/**
 * Get all users (or all users that have one specific permission)
 *
 * @param array $limit_by_permission
 *
 * @return array
 */
function get_all_users($limit_by_permission = array()) {
    $users = array();

    if(empty($limit_by_permission)) {
        $db_users = $GLOBALS['db']->GetAll("SELECT * FROM user ORDER BY username ASC");
    } else {
        $permissions = "";
        foreach($limit_by_permission as $permission) {
            $permissions .= "'" . $permission . "',";
        }
        $permissions = rtrim($permissions, ',');
        $db_users = $GLOBALS['db']->GetAll("SELECT u.* FROM user u INNER JOIN permission p ON p.user_uid=u.prim_uid WHERE p.permission IN (".$permissions.") ORDER BY username ASC");
    }
    foreach($db_users as $db_user) {
        $users[$db_user['prim_uid']] = array(
            'username' => $db_user['username'],
            'nice_username' => ((!empty($db_user['nice_username'])) ? $db_user['nice_username'] : $db_user['username'])
        );
    }

    return $users;
}

// Hash password
function security_hash_password($password) {
    return md5($GLOBALS['config']['system_key'].$password);
}

// Encryption
function security_encrypt($string) {
    $result = '';
    $newpass = '';
    
    $pass = md5($GLOBALS['config']['system_key']);

    $str_arr = str_split($string);
    $add = 0;
    $div = strlen($string) / strlen($pass);
    while($add <= $div) {
        $newpass .= $pass;
        $add++;
    }

    $pass_arr = str_split($newpass);
    foreach($str_arr as $key => $asc){
        $pass_int = ord($pass_arr[$key]);
        $str_int = ord($asc);
        $int_add = $str_int + $pass_int;
        $result .= chr($int_add);
    }

    $result = base64_encode($result);

    return $result;
}

// decrypt
function security_decrypt($string) {
    $result = '';
    $newpass = '';
    $pass = md5($GLOBALS['config']['system_key']);
    $string = base64_decode($string);

    $enc_arr = str_split($string);
    $add = 0;
    $div = strlen($string) / strlen($pass);
    while($add <= $div) {
        $newpass .= $pass;
        $add++;
    }

    $pass_arr = str_split($newpass);
    foreach($enc_arr as $key => $asc){
        $pass_int = ord($pass_arr[$key]);
        $enc_int = ord($asc);
        $str_int = $enc_int - $pass_int;
        $result .= chr($str_int);
    }

    return $result;
}

/**
 * Create sys log
 *
 * @param   string  $module_name
 * @param   string  $log_desc
 * @param   array   $log_data
 *
 * @return  int
 */
function create_sys_log($module_name, $log_desc, $log_data=array()) {
    $log_uid = 0;
    $module_name = trim(strip_tags_ext($module_name));
    if(!empty($_SESSION['username']) && !empty($module_name) && !empty($log_desc)) {
        if(empty($log_data)) {
            $log_data = $_POST;
        }
        
        $record = array();
        $record['username'] = $_SESSION['username'];
        $record['module_name'] = $module_name;
        $record['log_desc'] = $log_desc;
        $record['log_data'] = json_encode($log_data);
        $record['created_on'] = time();
        
        db_autoexecute('core_syslog', $record, 'INSERT');
        $log_uid = db_insert_id();
    }
    
    return $log_uid;
}

/**
 * Returns a random alpha numeric string.
 *
 * @param   int     $length
 * @param   bool    $only_int
 * @return  string
 */
function get_random_string($length, $only_int=false) {
    $max_limit = 35;
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    if($only_int) {
        $pattern = '1234567890';
        $max_limit = 9;
    }

    $result = '';
    for($i = 0; $i < $length; $i++) {
        $result .= $pattern[mt_rand(0, $max_limit)];
    }

    return $result;
}

/**
 * Return Client IP
 *
 * @return  string
 */
function get_client_ip() {
    return isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
}


/**
 * Insert and update user statistics
 *
 * @param $userStatistics
 */
function handleUserStatisticsUpdate($userStatistics) {
    $from = time() - (86400 * 7); // only check last x days to limit db load
    if(!empty($userStatistics)) {
        foreach($userStatistics as $user => $statisticsType) {
            foreach($statisticsType as $type => $statistics) {
                foreach ($statistics as $statisticDate => $statisticValue) {
                    $statisticTime = strtotime($statisticDate);
                    if ($statisticTime >= $from) {
                        $sql = 'INSERT INTO user_statistics(username, statistics_key, statistics_date, statistics_value) VALUES("' . db_escape_string($user) . '", "' . db_escape_string($type) . '", "' . db_escape_string($statisticDate) . '", "' . (int)$statisticValue . '") ON DUPLICATE KEY UPDATE statistics_value = ' . (int)$statisticValue;
                        db_execute($sql);
                    }
                }
            }

            // track quick flow things
            $qfSql = 'SELECT COUNT(created) as count, DATE_FORMAT(created, "%Y-%m-%d") as date FROM qf_log WHERE `assignee`="'.db_escape_string($user).'" AND created>="'.date('Y-m-d', $from).'" GROUP BY date';
            $qfData = db_get_all($qfSql);
            foreach($qfData as $data) {
                $sql = 'INSERT INTO user_statistics(username, statistics_key, statistics_date, statistics_value) VALUES("' . db_escape_string($user) . '", "quick_feedback_by_day", "' . db_escape_string($data['date']) . '", "' . (int)$data['count'] . '") ON DUPLICATE KEY UPDATE statistics_value = ' . (int)$data['count'];
                db_execute($sql);
            }
        }
    }
}

function get_localized_date($date_str) {
    if(!empty($date_str) && $date_str != '0000-00-00 00:00:00') {
        return date("d.m.Y H:i:s", date_sys_to_local(date_to_ts($date_str), 'Europe/Berlin'));
    } else {
        return '';
    }
}
