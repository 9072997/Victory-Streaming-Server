<?php
	require_once('/usr/script/db.inc.php');
	function webDavUpload($file) {
		//////////////////////stolen from blog.sebastiaandejonge.com//////////////////////
		// The user credentials I will use to login to the WebDav host
		$credentials = array(
			db1('SELECT contents FROM configuration WHERE var=\'webDavUser\'')->contents,
			db1('SELECT contents FROM configuration WHERE var=\'webDavPassword\'')->contents
		);

		// Prepare the file we are going to upload
		$filename = basename($file);
		$filesize = filesize($file);
		$fh = fopen($file, 'r');

		// The URL where we will upload to, this should be the exact path where the file
		// is going to be placed
		$remoteUrl = 'https://dav.box.com/dav/sermons/';

		// Initialize cURL and set the options required for the upload. We use the remote
		// path we specified together with the filename. This will be the result of the
		// upload.
		$ch = curl_init($remoteUrl . $filename);

		// I'm setting each option individually so it's easier to debug them when
		// something goes wrong. When your configuration is done and working well
		// you can choose to use curl_setopt_array() instead.

		// Set the authentication mode and login credentials
		curl_setopt($ch, CURLOPT_USERPWD, implode(':', $credentials));

		// Define that we are going to upload a file, by setting CURLOPT_PUT we are
		// forced to set CURLOPT_INFILE and CURLOPT_INFILESIZE as well.
		curl_setopt($ch, CURLOPT_PUT, true);
		curl_setopt($ch, CURLOPT_INFILE, $fh);
		curl_setopt($ch, CURLOPT_INFILESIZE, $filesize);

		// Execute the request, upload the file
		curl_exec($ch);

		// Close the file handle
		fclose($fh);
		///////////////////////////////////////////////////////////////////////////////////////
	}
?>
