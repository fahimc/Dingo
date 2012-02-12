<?php
$page =urldecode ($_GET["url"]);
$host =urldecode ($_GET["host"]);
$page = str_replace("..//dingo", "", $page);
$hostArray = split("index.php", $host );
$file = file_get_contents( "../../../".$page, true);
//echo $file;
$doc = new DOMDocument();
$doc->loadHTML($file);
$xpath = new DOMXPath($doc);
$tags = $xpath->query('//@id');
$result="";
foreach ($tags as $tag) {
	
    $result.=(trim($tag->nodeValue))."||";
}
?>
<script type="text/javascript">
parent.divIdList("<?php echo $result;?>");</script>
<?php?>