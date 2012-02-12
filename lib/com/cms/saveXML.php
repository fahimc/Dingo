<?php
$text = urldecode($_POST['xml']);
file_put_contents('../../../data.xml', $text);
?>