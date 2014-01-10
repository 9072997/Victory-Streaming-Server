<?php
	require_once('/usr/script/db.inc.php');
	$resourceId=intval($_GET['resource']);
	$format=intval($_GET['format']);
	db0('INSERT INTO cache (resource, format) SELECT ?, ? WHERE NOT EXISTS (SELECT 1 FROM cache WHERE resource=? AND format=?)', $resourceId, $format, $resourceId, $format);
	db0('UPDATE cache SET ts=datetime(\'now\') WHERE resource=? AND format=? LIMIT 1', $resourceId, $format);
	$status = db1('SELECT COUNT(*) AS count FROM cache WHERE ((progress>=0 AND progress<1000) OR progress IS NULL) AND (strftime(\'%s\',\'now\')-strftime(\'%s\',ts))<60 AND id<=(SELECT id FROM cache WHERE resource=? AND format=? LIMIT 1)', $resourceId, $format)->count;
	echo $status;
	if($status == 0) {
		echo db1('SELECT id FROM cache WHERE resource=? AND format=?', $resourceId, $format)->id;
	} else {
		touch('/var/lock/encodeMaster');
	}
?>
