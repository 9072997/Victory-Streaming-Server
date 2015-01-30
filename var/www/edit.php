<?php
	require_once('/usr/script/auth.inc.php');
	require_once('/usr/script/noCache.inc.php');
?><!DOCTYPE html>
<html>
	<head>
		<title>Edit Victory Service Archives</title>
	</head>
	<body>
		<form method="post">
			<label for="name">New Service:</label>
			<input type="name" name="name" id="name" />
			<input type="submit" />
		</form>
		<?php
			require_once('/usr/script/db.inc.php');
			if(isset($_POST['service']) && isset($_POST['name']) && intval($_POST['service']) && strlen($_POST['name'])) {
				$serviceId = intval($_POST['service']);
				$name = strip_tags($_POST['name']);
				db0('UPDATE services SET name=? WHERE id=?', $name, $serviceId);
			} elseif (isset($_POST['name']) && strlen($_POST['name'])) {
				$name = strip_tags($_POST['name']);
				db0('INSERT INTO services (name) VALUES (?)', $name);
			} elseif (isset($_POST['delete']) && intval($_POST['delete'])) {
				$serviceId = intval($_POST['delete']);
				db0('DELETE FROM services WHERE id=?', $serviceId);
				db0('UPDATE resources SET service=NULL WHERE service=?', $serviceId);
			}
			$services = db('SELECT id,name,ts FROM services ORDER BY strftime(\'%s\',ts) DESC');
			foreach($services as $service) {
				echo '<hr />';
				echo '<form method="post">';
				if(isset($service->name) && strlen($service->name)) {
					$serviceDisplayName = $service->name;
				} elseif(isset($service->ts) && strlen($service->ts)) {
					$serviceDisplayName = 'Service At ' . $service->ts;
				} else {
					$serviceDisplayName = 'Service ' . $service->id;
				}
				echo 'Date:' . $service->ts;
				echo '<input type="text" name="name" value="' . $serviceDisplayName . '" />';
				echo '<input type="hidden" name="service" value="' . $service->id . '" />';
				echo '<input type="submit" value="Update" />';
				echo '</form>';
				echo '<form method="post">';
				echo '<a href="editResources.php?service=' . $service->id . '">edit resources</a>';
				echo '<input type="hidden" name="delete" value="' . $service->id . '" />';
				echo '<input type="submit" value="Delete"/>';
				echo '</form>';
			}
		?>
	</body>
</html>
