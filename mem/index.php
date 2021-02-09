<?php
include_once('configs/config.inc.php');

verify_login();

network_redirect('overview.php');
die();