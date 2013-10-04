<?php

!isset($_REQUEST["url"]) && die("Missing url param");

include_once("Autoloader.php");
new AutoLoader();

$url = $_REQUEST["url"];

$db = new PDO("mysql:dbname=cache_test;host=localhost", "cache_test", "buS9jpH9me48b2QM");

$twitter = new TwitterFollowers($db, $url);
echo sprintf('"%s" has %d followers.', $url, $twitter->getFollowers());
if ($db->lastInsertId() == 0)
	echo "\nAnd the result was retrieved from the cache!";
