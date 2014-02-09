<html>
	<head>
		<title>Victory Service Live</title>
		<style>
			body {
				color: #444;
				font-family:"Arial", Arial, sans-serif;
			}
		</style>
	</head>
	<body>
		LIVE:
		<!-- BEGINS: AUTO-GENERATED MUSES RADIO PLAYER CODE -->
		<script type="text/javascript" src="http://hosted.musesradioplayer.com/mrp.js"></script>
		<script type="text/javascript">
		MRP.insert({
		'url':'http://icecastserver.victoryar.net/live.ogg',
		'codec':'ogg',
		'volume':100,
		'autoplay':true,
		'buffering':5,
		'title':'Live',
		'bgcolor':'#FFFFFF',
		'skin':'mcclean',
		'width':180,
		'height':60
		});
		</script>
		<!-- ENDS: AUTO-GENERATED MUSES RADIO PLAYER CODE -->
		<script>
			var request;
			if(window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				request = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				request = new ActiveXObject('Microsoft.XMLHTTP');
			}
			
			request.onreadystatechange = function() {
				if(request.readyState == 4) {
					if(request.status == 200) {
						if(request.responseText == 'false') {
							if(confirm('LIVE stream is over. OK to return?')) {
								window.location = 'http://' + window.location.host;
							}
						} else {
							setTimeout(function() {
								request.open('GET', 'checkLive.php', true);
								request.send();
							}, 30000);
						}
					}
				}
			}
			request.open('GET', 'checkLive.php',true);
			request.send();
		</script>
	</body>
</html>
