#!/usr/bin/env php
<?php
	require_once('/usr/script/db.inc.php');
	require_once('/usr/script/webDav.inc.php');
	$resources = db('SELECT id FROM resources WHERE backedup=0');
	foreach($resources as $resource) {
		webDavUpload('/var/resources/' . $resource->id);
		db0('UPDATE resources SET backedup=1 WHERE id=?', $resource->id);
	}
	$timestamp = time();
	copy('/var/db/victory.sqlite3', '/tmp/victory.' . $timestamp . '.sqlite3');
	$tmpdb = new PDO('sqlite:/tmp/victory.' . $timestamp . '.sqlite3');
	$tmpdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$tmpdb->exec('PRAGMA secure_delete = 1');
	$tmpdb->exec('DROP TABLE configuration'); // cointains too many passwords
	$tmpdb = null;
	webDavUpload('/tmp/victory.' . $timestamp . '.sqlite3');
	unlink('/tmp/victory.' . $timestamp . '.sqlite3');
?>
