<?php
$GLOBALS['config']['is_api_request'] = 1;

include_once('configs/config.inc.php');

header('Pragma: no-cache');
header('Cache-Control: max-age=1, s-maxage=1, no-store, no-cache, post-check=0, pre-check=0, must-revalidate, proxy-revalidate');

$output = array();
$requested_ip = get_client_ip();
$access_token = @$_POST['access_token'];

if(md5($access_token) == md5($GLOBALS['config']['cms']['access_token'])) {
	if(isset($_POST['api']) && trim($_POST['api']) != '' && is_string($_POST['api'])) {

		if(@$_POST['debug'] == 1) {
			print_r($_POST);
			exit;
		}

		$param = strtolower(trim(strip_tags_ext($_POST['api'])));

		if($param != '') {
			$available_actions = io_search_directory('[(.*)\.inc\.php]', 'src/actions');
			foreach($available_actions as $idx=>$item) {
				$available_actions[$idx] = basename($item);
			}

			if(in_array($param.'.inc.php', $available_actions)) {
				$action_file = 'src/actions/'.$param.'.inc.php';
				if(file_exists($action_file) ) {

					include_once($action_file);

				} else {
					$output = array(
						'Error' => true,
						'error_message' => $GLOBALS['i18']['error']['api_not_found']
					);
				}
			} else {
				$datavalid = false;
				$output = array(
					'Error' => true,
					'error_message' => $GLOBALS['i18']['error']['api_not_found']
				);

			}
		}

	} else {
		$datavalid = false;
		$output = array(
			'Error' => true,
			'error_message' => $GLOBALS['i18']['error']['api_not_correct']
		);

	}
} else {
	$datavalid = false;
	$output = array(
		'Error' => true,
		'error_message' => $GLOBALS['i18']['error']['ip_not_allowed']
	);
}


echo json_encode($output);
?>