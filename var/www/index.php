<?php
	require_once('/usr/script/noCache.inc.php');
?><!DOCTYPE html>
<html>
	<head>
		<title>Victory Service Archives</title>
		<style>
			.service {
				margin: 10px;
				padding: 10px;
				border-style: solid;
				border-color: #444;
				background-color: #eee;
			}
			.resources {
				margin: 0px 10px;
			}
			body {
				color: #444;
				font-family:"Arial", Arial, sans-serif;
			}
		</style>
	</head>
	<body>
		<div class="services">
			<?php
				require_once('/usr/script/db.inc.php');
				$services = db('SELECT id,name,ts FROM services ORDER BY strftime(\'%s\',ts) DESC');
				foreach($services as $service) {
					if(isset($service->name) && strlen($service->name)) {
						$serviceDisplayName = $service->name;
					} elseif(isset($service->ts) && strlen($service->ts)) {
						$serviceDisplayName = 'Service At ' . $service->ts;
					} else {
						$serviceDisplayName = 'Service ' . $service->id;
					}
					echo '<div class="service"><span class="sermonName">' . $serviceDisplayName . '</span><div class="resources">';
					$resources = db('SELECT id,name,format FROM resources WHERE service=?', $service->id);
					foreach($resources as $resource) {
						echo '<div class="resource">';
						if(isset($resource->name) && strlen($resource->name)) {
							$resourceDisplayName = $resource->name;
						} elseif(isset($resource->format) && $resource->format==1) {
							$resourceDisplayName = 'Audio Resource ' . $resource->id;
						} else {
							$resourceDisplayName = 'Resource ' . $resource->id;
						}
						
						if(isset($resource->format) && $resource->format==1) {
							echo '<a href="play.php?resource=' . $resource->id . '">Play ' . $resourceDisplayName . '</a>';
						} else {
							echo '<a href="getResource.php?id=' . $resource->id . '">Download ' . $resourceDisplayName . '</a>';
						}
						echo '</div>';
					}
					echo '</div></div>';
				}
			?>
		</div>
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
						if(request.responseText == 'true') {
							if(confirm('LIVE stream is avalible. OK to load?')) {
								window.location = 'http://' + window.location.host + '/playLive.php';
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
