<?php

// Insert absolute filepath to your twitteroauth.php script here - grab the twitteroauth folder from http://github.com/abraham/twitteroauth
require_once 'twitteroauth/twitteroauth/twitteroauth.php';

// Insert absolute filepath where your cached feed file will go - can be the same directory as this script
define('CACHED_FILE_PATH', 'feed.xml');

// Insert your Stellar.io username
define('STELLAR_USERNAME', '');

// Insert your Twitter app credentials... you can create and get these at https://dev.twitter.com/apps
define('CONSUMER_KEY','');
define('CONSUMER_SECRET', '');
define('ACCESS_TOKEN', '');
define('ACCESS_TOKEN_SECRET', '');

?>