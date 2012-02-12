<?php
$page =urldecode ($_POST["url"]);
$host =urldecode ($_POST["host"]);
$id = $_POST["id"];
$copy = urldecode($_POST["copy"]);
$page = str_replace("..//", "/", $page);
$page = str_replace("//", "/", $page);
$hostArray = split("/dingo/", $host );

$path= getRelativePath(curPageURL(),$hostArray[0].$page);
$file = file_get_contents( $path, true);
$doc = new DOMDocument();
$doc->loadHTML($file);
$xpath = new DOMXPath($doc);
$res = $xpath->query("//*[@id = '".$id."']"); 
$res->item(0)->nodeValue=$copy;
 $doc->saveHTMLFile($path);
//echo $doc->save("../../../".$page) . "\n";
//echo file_put_contents($path,$file);

?>
<script type="text/javascript">
parent.onSetContentComplete();

</script>
<?php
function getRelativePath($from, $to)
{
    $from     = explode('/', $from);
    $to       = explode('/', $to);
    $relPath  = $to;

    foreach($from as $depth => $dir) {
        // find first non-matching dir
        if($dir === $to[$depth]) {
            // ignore this directory
            array_shift($relPath);
        } else {
            // get number of remaining dirs to $from
            $remaining = count($from) - $depth;
            if($remaining > 1) {
                // add traversals up to first matching dir
                $padLength = (count($relPath) + $remaining - 1) * -1;
                $relPath = array_pad($relPath, $padLength, '..');
                break;
            } else {
                $relPath[0] = './' . $relPath[0];
            }
        }
    }
    return implode('/', $relPath);
}
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>