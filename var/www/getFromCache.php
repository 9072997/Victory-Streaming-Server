<?php
	require_once('/usr/script/db.inc.php');
	require_once('/usr/script/smartRead.inc.php');
	$resourceId=intval($_GET['resource']);
	$format=intval($_GET['format']);
	$cacheId = db1('SELECT id FROM cache WHERE resource=? AND format=?', $resourceId, $format)->id;
	switch($format) {
		case 1:
			$mimeType = 'audio/mpeg';
			$extention = '.mp3';
			break;
		default:
			$mimeType = 'application/octet-stream';
			$extention = '';
	}
	smartReadFile('/var/cache/resources/' . $cacheId, $mimeType, $resourceId . $extention);
?>
