<?php
	require_once('/usr/script/db.inc.php');
	require_once('/usr/script/smartRead.inc.php');
	$resourceId=intval($_GET['id']);
	$resource=db1('SELECT id,name,format FROM resources WHERE id=?', $resourceId);
	
	if(isset($resource->name) && strlen($resource->name)) {
		$resourceDisplayName = $resource->name;
	} elseif(isset($resource->format) && $resource->format==1) {
		$resourceDisplayName = 'Audio Resource ' . $resource->id;
	} else {
		$resourceDisplayName = 'Resource ' . $resource->id;
	}
	
	switch($resource->format) {
		case 1:
			$mimeType = 'audio/ogg';
			$resourceDisplayName.='.ogg'; //append extention for oggs
			break;
		default:
			$mimeType = 'application/octet-stream';
	}
	smartReadFile('/var/resources/' . $resourceId, $mimeType, $resourceDisplayName);
?>
