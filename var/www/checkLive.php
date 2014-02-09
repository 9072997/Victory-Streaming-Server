<?php
	require_once('/usr/script/noCache.inc.php');
	require_once('/usr/script/db.inc.php');
	echo db1('SELECT value FROM configuration WHERE key=\'live\'')->value;
?>
