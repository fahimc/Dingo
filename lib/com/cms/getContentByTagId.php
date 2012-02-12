<?php
$page =urldecode ($_GET["url"]);
$host =urldecode ($_GET["host"]);
$id = $_GET["id"];
$page = str_replace("..//dingo", "", $page);
$hostArray = split("index.php", $host );
$file = file_get_contents( $hostArray[0] .$page, true);
//echo $file;
$doc = new DOMDocument();
$doc->loadHTML($file);
$xpath = new DOMXPath($doc);
$res = $xpath->query("//*[@id = '".$id."']"); 
$result= $res->item(0)->nodeValue;
?>
<script type="text/javascript">
parent.onGetContent("<?php echo rawurlencode($result);?>");

</script>
<?php
?>