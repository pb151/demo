<?php
/**
 * Swap
 *
 * @package	gmi
 * @subpackage	ds
 *
 * @copyright	GetMyInvoices
 */

/**
 * Include configuration
 */
include_once 'configs/config.inc.php';
verify_login();

if(io_file_exists($GLOBALS['config']['cms']['nice_username_file'])) {
	io_delete_file($GLOBALS['config']['cms']['nice_username_file']);
} else {
	io_file_put_contents($GLOBALS['config']['cms']['nice_username_file'], time());
}

network_redirect('overview.php');
die;