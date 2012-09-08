#!/usr/local/php5/bin/php -q

<?php

require_once 'config.php';

// This function grabs the last tweets we cached so we don't try to tweet them again... saves API hits
function getOldIDs () {

	$oldIDs = array();

	if (file_exists(CACHED_FILE_PATH)) {
		$handle = fopen(CACHED_FILE_PATH, 'r');
		$contents = fread($handle, filesize(CACHED_FILE_PATH));
		fclose($handle);

		$oldxml = simplexml_load_string($contents);

		foreach($oldxml->entry as $entry) {
			if ($entry->link->attributes()->href) {
				$urlstring = (string)$entry->link->attributes()->href;
				if (preg_match("/twitter.com\/[A-Z0-9_]+\/status\/([0-9]+)/i", $urlstring, $matches)) { 
					array_push($oldIDs, $matches[1]);
				}
			}
		}
	}
	return $oldIDs;
}

// This function looks for new tweets and retweets them out
function retweet() {
 
	$feed_url = 'http://stellar.io/' . STELLAR_USERNAME . '/flow/feed';
echo $feed_url;
	$toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	
	$ch = curl_init($feed_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$feedcontents = curl_exec($ch);
	curl_close($ch);

	$oldIDArray = getOldIDs();

	$newxml = simplexml_load_string($feedcontents);
	foreach($newxml->entry as $entry) {
		if ($entry->link->attributes()->href) {
			$isreply = preg_match("/^@/i", (string)$entry->title); 
			$urlstring = (string)$entry->link->attributes()->href;
			if (!$isreply && preg_match("/twitter.com\/[A-Z0-9_]+\/status\/([0-9]+)/i", $urlstring, $matches)) { 
				if (!in_array($matches[1], $oldIDArray)) {
					print_r($toa->post('statuses/retweet/'.$matches[1]));
				}
			}
		}
	}

	$handle = fopen(CACHED_FILE_PATH, 'w');
	fwrite($handle,$feedcontents);
	fclose($handle);	

}

retweet();

?>