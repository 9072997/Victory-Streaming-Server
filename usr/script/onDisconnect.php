#!/usr/bin/env php
<?php
	require_once('/usr/script/db.inc.php');
	db0('UPDATE configuration SET contents=\'false\' WHERE var=\'live\'');
	if(filesize('/tmp/dump.ogg') > 2 * 1024 * 1024) {
		date_default_timezone_set('America/Chicago');
		db0('INSERT INTO services(name) values (?)', date('F j, Y A'));
		$serviceId = db1('SELECT last_insert_rowid() AS id')->id;
		db0('INSERT INTO resources (service, format, name) VALUES (?, 1, \'Sermon Recording\')', $serviceId);
		$resourceId = db1('SELECT last_insert_rowid() AS id')->id;
		rename('/tmp/dump.ogg', '/var/resources/' . $resourceId);
	} else {
		unlink('/tmp/dump.ogg');
	}
?>
