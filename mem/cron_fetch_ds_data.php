<?php
/**
 * cron runs every 1 minutes to fetch all ds servers data
 *
 * @package		gmi
 * @subpackage	master-ds
 *
 * @copyright	GetMyInvoices
 */

include_once 'configs/config.inc.php';

//  Cron start ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-fetch-data/b');

if(!empty($GLOBALS['ds_systems'])) {
	$data = array();
    $userStatistics = array();
    $userStatisticKeys = array('manuals_by_day', 'mail_rules_by_day', 'problems_by_day', 'mail_exclusion_by_day', 'document_review_by_day', 'fintract_live_review_by_day', 'fintract_corrections_by_day');
	foreach($GLOBALS['ds_systems'] as $ds_system) {
		$content = network_http_request($ds_system['url'].'master_ds_data.php', array(), array(), false);
		$response = json_decode($content, true);
		if(isset($response['success']) && $response['success']) {
			if($ds_system['name'] == 'GMI Prod') {
				foreach($response['data'] as $user => $data) {
					if(isset($data['nice_username'])) {
						$query = 'UPDATE `user` SET `nice_username`="'.db_escape_string($data['nice_username']).'" WHERE `username`="'.$user.'"';
						db_execute($query);
					}
				}
			}

            foreach($response['data'] as $user => $udata) {
                if(isset($udata['statistics'])) {
                    foreach($userStatisticKeys as $statKey) {
                        if (isset($udata['statistics'][$statKey])) {
                            foreach ($udata['statistics'][$statKey] as $statDay => $statValue) {
                                if(!isset($userStatistics[$user])) {
                                    $userStatistics[$user] = array();
                                }
                                if(!isset($userStatistics[$user][$statKey])) {
                                    $userStatistics[$user][$statKey] = array();
                                }
                                if(!isset($userStatistics[$user][$statKey][$statDay])) {
                                    $userStatistics[$user][$statKey][$statDay] = 0;
                                }
                                $userStatistics[$user][$statKey][$statDay] += $statValue;
                            }
                        }
                    }
                }
            }

			$data[str_replace(' ', '_', $ds_system['name'])] = $response['data'];
		} else {
			$GLOBALS['log']->info('Failed to fetch data from DS : ' . $ds_system['name']);
		}
	}

	if(!io_is_dir($GLOBALS['config']['cms']['cache_folder'].'ds_systems_data')) {
		io_mkdir($GLOBALS['config']['cms']['cache_folder'].'ds_systems_data');
	}
	file_put_contents($GLOBALS['config']['cms']['cache_folder'].'ds_systems_data/data.txt', json_encode($data));

    handleUserStatisticsUpdate($userStatistics);
}

//  Cron end ping
network_http_request('https://gm-ds.cloudspace.work:18073/cm/master-ds-fetch-data/e');
