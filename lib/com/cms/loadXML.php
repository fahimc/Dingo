<?php
function loadData() {
	$filename = 'data.xml';
	if (file_exists($filename)) {
		$xml = new DOMDocument();
		$xml->load($filename);
		//$xml = file_get_contents($filename);
		if ($xml) {
			//$xml = trim($xml);
			//preg_replace('/\r','',$xml);
			//preg_replace('/\n','',$xml);
			echo $xml;
		} else {
			echo "none";
		}
	} else {
		echo "no file";
	}
}
?>