<?php
	require_once('/usr/script/auth.inc.php');
	require_once('/usr/script/noCache.inc.php');
?><!DOCTYPE html>
<html>
	<head>
		<title>Edit Victory Service Archives</title>
	</head>
	<body>
		<?php
			require_once('/usr/script/db.inc.php');
			$serviceId = intval($_GET['service']);
			
			if(isset($_POST['unlink']) && intval($_POST['unlink'])) {
				$resourceId = intval($_POST['unlink']);
				db0('UPDATE resources SET service=NULL WHERE id=?', $resourceId);
			}
			if(isset($_POST['resource']) && isset($_POST['name']) && intval($_POST['resource']) && strlen($_POST['name'])) {
				$resourceId = intval($_POST['resource']);
				$name = strip_tags($_POST['name']);
				db0('UPDATE resources SET name=? WHERE id=?', $name, $resourceId);
			}
			if(isset($_FILES['file']) && isset($_POST['format'])) {
				if ($_FILES['file']['error'] > 0) {
					echo 'Error: ' . $_FILES['file']['error'] . '<br>';
				} else {
					db0('INSERT INTO resources (service,format) VALUES (?,?)', $serviceId, intval($_POST['format']));
					$resourceId = db1('SELECT last_insert_rowid() AS id')->id;
					rename($_FILES['file']['tmp_name'], '/var/resources/' . $resourceId);
				}
			}
			
			$resources = db('SELECT id,name,format FROM resources WHERE service=?', $serviceId);
			foreach($resources as $resource) {
				echo '<form method="post">';
				if(isset($resource->name) && strlen($resource->name)) {
					$resourceDisplayName = $resource->name;
				} elseif(isset($resource->format) && $resource->format==1) {
					$resourceDisplayName = 'Audio Resource ' . $resource->id;
				} else {
					$resourceDisplayName = 'Resource ' . $resource->id;
				}
				echo '<input type="text" name="name" value="' . $resourceDisplayName . '" />';
				echo '<input type="hidden" name="resource" value="' . $resource->id . '" />';
				echo '<input type="submit" /></form>';
				echo '<form method="post">';
				echo '<input name="unlink" type="hidden" value="' . $resource->id . '" />';
				echo '<input type="submit" value="Unlink (delete) ' . $resourceDisplayName . '" />';
				echo '</form><br />';
			}
		?>
		<form method="post" enctype="multipart/form-data">
			<label for="file">New Resource:</label>
			<input type="file" name="file" id="file" />
			<label for="format">Format:</label>
			<select name="format" id="format">
				<option value="0">Other</option>
				<option value="1">Audio (OGG)</option>
			</select>
			<input type="submit" />
		</form>
		<a href="edit.php"> &lt;-Back</a>
	</body>
</html>
