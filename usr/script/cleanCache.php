#!/usr/bin/env php
<?php
	require_once('/usr/script/db.inc.php');
	$cacheObjects = db('SELECT id FROM cache WHERE (strftime(\'%s\',\'now\')-strftime(\'%s\',ts))>86400');
	foreach($cacheObjects as $cacheObject) {
		db0('DELETE FROM cache WHERE id=?', $cacheObject->id);
		unlink('/var/cache/resources/' . intval($cacheObject->id));
	}
?>
