<!DOCTYPE html>
<?php
	require_once('/usr/script/db.inc.php');
	$resourceId=intval($_GET['resource']);
	if(db1('SELECT format FROM resources WHERE id=?', $resourceId)->format == 1) {
?>
		<html>
			<head>
				<title>Play Audio Resource</title>
			</head>
			<body>
				<div id="player"></div>
				<script>
					function oggSupport() {
						var a = document.createElement('audio');
						return !!(a.canPlayType && a.canPlayType('audio/ogg;').replace(/no/, ''));
					}
					
					function mp3Support() {
						var a = document.createElement('audio');
						return !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''));
					}
					
					function checkMp3Status() {
						var request
						if (window.XMLHttpRequest) {
							// code for IE7+, Firefox, Chrome, Opera, Safari
							request = new XMLHttpRequest();
						} else {
							// code for IE6, IE5
							request = new ActiveXObject("Microsoft.XMLHTTP");
						}
						request.onreadystatechange=function() {
							if (request.readyState==4 && request.status==200) {
								if(request.responseText.charAt(0) == '0') {
									if(mp3Support()) {
										// HTML5 MP3
										// resource ID = request.responseText.substring(1)
										document.getElementById('player').innerHTML = '<audio controls><source src="getFromCache.php?resource=<?php echo $resourceId; ?>&format=1" type="audio/mpeg" /></audio>'
									} else {
										// FLASH
										document.getElementById('player').innerHTML = '<object type="application/x-shockwave-flash" data="player_mp3_mini.swf" width="200" height="20"><param name="movie" value="player_mp3_mini.swf" /><param name="bgcolor" value="#ff0000" /><param name="FlashVars" value="mp3=getFromCache.php?resource=<?php echo $resourceId; ?>%26format=1" /></object>'
									}
								} else {
									document.getElementById('player').innerText = 'Your browser doesn\'t support playback of OGG audio files, but that\'s ok, we are converting it for you. There are ' + request.responseText + ' Jobs in front of you.';
									setTimeout(checkMp3Status, 5000); // check again in 5 secs
								}
							}
						}
						request.open('GET', 'transcode.php?resource=<?php echo $resourceId; ?>&format=1', true);
						request.send();
					}
					
					
					if(oggSupport()) {
						// HTML5 OGG
						document.getElementById('player').innerHTML = '<audio controls><source src="getResource.php?id=<?php echo $resourceId; ?>" type="audio/ogg" /></audio>'
					} else {
						checkMp3Status();
					}
				</script>
			</body>
		</html>
<?php
	}
?>
