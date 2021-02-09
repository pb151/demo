<?php
// Try cURL
if(!function_exists('curl_init')) {
	echo '[Master-DS] cURL not available please enable!'."\n";
} else {
	echo '[Master-DS] Requesting git whitelist'."\n";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://ip.simplessus.com/add-git.php');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, false); // no output output
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);

	$content = curl_exec($ch);

	curl_close($ch);

	if(stripos($content, 'added') !== false) {
        echo '[Master-DS] Waiting 60 seconds for git whitelist' . "\n";
        sleep(60); // wait for git to whitelist ip
    }
}

echo '[Master-DS] Detecting branch: ';
$branch = system('git rev-parse --abbrev-ref HEAD 2>&1');

echo '[Master-DS] Performing git pull: ';
$git_pull = system('git pull origin '.$branch.' 2>&1');

$upToDate = stripos($git_pull, 'Already up-to-date');
if($upToDate === false) {
    include __DIR__.'/update.php';
}

echo '[Master-DS] Deploy completed.'."\n";