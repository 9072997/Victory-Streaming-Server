#!/usr/bin/env php
<?php
	require_once('/usr/script/db.inc.php');
	db0('UPDATE configuration SET contents=\'true\' WHERE var=\'live\'');
?>
