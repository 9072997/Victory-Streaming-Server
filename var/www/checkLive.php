<?php
	require_once('/usr/script/noCache.inc.php');
	require_once('/usr/script/db.inc.php');
	echo db1('SELECT contents FROM configuration WHERE var=\'live\'')->contents;
?>
