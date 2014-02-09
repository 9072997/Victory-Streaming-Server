#!/usr/bin/env php
<?php
	require_once('/usr/script/db.inc.php');
	if(filesize('/tmp/dump.ogg') > 1024) {
		db0('INSERT INTO services DEFAULT VALUES');
		$serviceId = db1('SELECT last_insert_rowid() AS id')->id;
		db0('INSERT INTO resources (service, format, name) VALUES (?, 1, \'Sermon Recording\')', $serviceId);
		$resourceId = db1('SELECT last_insert_rowid() AS id')->id;
		rename('/tmp/dump.ogg', '/var/resources/' . $resourceId);
	} else {
		unlink('/tmp/dump.ogg');
	}
?>
