<?php
/**
 * Migration File
 *
 * @package     uwa
 * @subpackage	kernel
 *
 * @author      Bjoern Kahle
 * @copyright	GetMyInvoices
 *
 * @version	$Id$
 */

/**
 * !!!
 * Please use migration files from sub folder migrations now. Do not change anything in this file anymore.
 * !!!
 */
include_once __DIR__.'/../configs/config.inc.php';

if(!db_table_exists('user_statistics')) {
    $sql = 'CREATE TABLE IF NOT EXISTS `user_statistics` (
        `prim_uid` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(255) NOT NULL,
        `statistics_key` varchar(255) NOT NULL,
        `statistics_date` varchar(255) NOT NULL,
        `statistics_value` int(11) NOT NULL DEFAULT \'0\',
        PRIMARY KEY (`prim_uid`),
        UNIQUE KEY `unique` (`username`,`statistics_key`,`statistics_date`),
        KEY `username` (`username`),
        KEY `statistics_key` (`statistics_key`),
        KEY `statistics_date` (`statistics_date`),
        KEY `statistics_value` (`statistics_value`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

    db_execute($sql);
}

// ICRM-16
if(!db_table_exists('git_commits')) {
    $sql = 'CREATE TABLE IF NOT EXISTS `git_commits` (
        `prim_uid` int(11) NOT NULL AUTO_INCREMENT,
        `repository` varchar(255) NOT NULL,
        `commit_message` varchar(255) NOT NULL,
        `timestamp` varchar(255) NOT NULL,
        `branch` varchar(255) NOT NULL,
        `version` varchar(255) NOT NULL,
        `published` smallint(1) NOT NULL DEFAULT \'0\',
        PRIMARY KEY (`prim_uid`),
        KEY `repository` (`repository`),
        KEY `commit_message` (`commit_message`),
        KEY `branch` (`branch`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

    db_execute($sql);
}

if(!db_column_exists('git_commits', 'project')) {
    db_add_column('git_commits', 'project', 'varchar', 255, '', 'INDEX');
}

if(!db_column_exists('git_commits', 'author')) {
    db_add_column('git_commits', 'author', 'varchar', 255, '', 'INDEX');
}

// GMI-219
if(!db_table_exists('ustack_components')) {
    $sql = 'CREATE TABLE IF NOT EXISTS `ustack_components` (
        `prim_uid` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        PRIMARY KEY (`prim_uid`),
        KEY `name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

    db_execute($sql);
}

if(!db_table_exists('ustack_related_gits')) {
    $sql = 'CREATE TABLE IF NOT EXISTS `ustack_related_gits` (
        `prim_uid` int(11) NOT NULL AUTO_INCREMENT,
        `component_uid` int(11) NOT NULL DEFAULT \'0\',
        `git_repository` text NOT NULL,
        `git_user` text NOT NULL,
        `pm_project` text NOT NULL,
        `pm_user` text NOT NULL,
        PRIMARY KEY (`prim_uid`),
        KEY `component_uid` (`component_uid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

    db_execute($sql);
}


// GMI-253
if(db_table_exists('git_commits') && !db_column_exists('git_commits', 'commit_url')) {
    db_execute("ALTER TABLE `git_commits` ADD `commit_url` VARCHAR(255) NULL AFTER `version`;");
}

if(db_table_exists('git_commits') && !db_column_exists('git_commits', 'merge_back_reviewed')) {
    db_execute("ALTER TABLE `git_commits` ADD `merge_back_reviewed` TINYINT(11) NOT NULL DEFAULT '1' AFTER `published`;");
}


/**
 * Read migration path and execute migrations
 */
$migrationPath = __DIR__.'/migrations/';
$migrations = scandir($migrationPath, SCANDIR_SORT_ASCENDING);
foreach($migrations as $migrate) {
    if (strpos($migrate, 'migrate') !== false && pathinfo($migrate, PATHINFO_EXTENSION) === 'php') {
        $migrationFile = $migrationPath . $migrate;
        if (!file_exists($migrationFile)) {
            die('CRITICAL: File ' . $migrationFile . ' not found!');
        }

        // Load the migrations file
        include $migrationFile;

        // Get class name and run migration from it
        $className = basename(substr($migrate, strpos($migrate, 'migrate_')), '.php');
        if (method_exists($className, 'migrate')) {
            $className::migrate();
        }
    }
}
