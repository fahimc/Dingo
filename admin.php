<?php
include ("template/basic/admin.html");
include ("lib/com/cms/loadXML.php");
include ("lib/com/cms/errorHandler.php");
ExceptionThrower::Start();
 
try
{

?>
<script type="text/javascript">
	var sidebar = document.getElementById('sidebar');
	var createView = document.getElementById('createView');
	var welcomeView = document.getElementById('welcomeScreen');
	var pageName = document.getElementById('pageName');
	var pageLocation = document.getElementById('pageLocation');
	var createLabelList = document.getElementById('createLabelList');
	var pageIdDropdown = document.getElementById('pageIdDropdown');
	var createLabelButton = document.getElementById('createLabelButton');
	var labelName = document.getElementById('labelName');
	var pageLabel = document.getElementById('pageLabel');
	createLabelButton.addEventListener('click', onCreateLabelClick);
	pageIdDropdown.addEventListener('change', onCreateDropdownChange);
	var pageDivs;
	var xml;

	function divIdList(list) {
		pageDivs = list.split("||");

		var opt = document.createElement('option');
		opt.text = "please select";
		opt.value = '-1';
		pageIdDropdown.add(opt);

		for(var a = 0; a < pageDivs.length; a++) {
			opt = document.createElement('option');
			opt.text = pageDivs[a];
			opt.value = pageDivs[a];
			pageIdDropdown.add(opt);
		}
	}

	function getXML() {
		var xloader = new xURLLoader();
		var fileExists = "<?php  if(file_exists('data.xml'))
		{
			echo 'true';
		}else{
			echo 'false';	
		}  ?>";
		
		//alert(fileExists);
		if(fileExists=='true')
		{
			xloader.load('data.xml', xmlLoadComplete);
		}else{
			xmlLoadComplete(null,null);
		}
	}

	function xmlLoadComplete(t, x) {
		//alert(x);
		if(x) {
			xml = x;
		} else {
			var oParser = new DOMParser();
			xml= oParser.parseFromString('<data></data>', "text/xml");
		}
		//alert(new XMLSerializer().serializeToString(xml));
		
	}

	function getPageById(id) {
		var pages = xml.getElementsByTagName('page');
		var page;
		for(var i = 0; i < pages.length; i++) {
			if(pages[i].getAttribute("location") == id) {
				page = pages[i];
			}
		}
		return page;
	}

	function getLabelById(id, page) {
		var labels = page.getElementsByTagName('label');
		var label;
		for(var i = 0; i < labels.length; i++) {
			if(labels[i].getAttribute("id") == id) {
				label = labels[i];
			}
		}
		return label;
	}
	function onCreateDropdownChange(event)
	{
		labelName.value="";
		var page = getPageById(currentPage.location);
		if(page)
		{
			var labelID = pageIdDropdown.options[pageIdDropdown.selectedIndex].value;
			var label = getLabelById(labelID, page);
			if(label) 
			{
				labelName.value=label.getAttribute("name");
			}
		}
	}
	function onCreateLabelClick(event) {
		
		var page = getPageById(currentPage.location);
		if(!page) {
			page = xml.createElement('page');
			page.setAttribute('location', currentPage.location);
			page.setAttribute('name', currentPage.name);
			page.setAttribute('realpath', currentPage.realpath);
			xml.getElementsByTagName('data')[0].appendChild(page);
		}
		if(pageLabel.value && pageLabel.value!=page.getAttribute("label"))
		{
			page.setAttribute('label', pageLabel.value);
		}
		if(labelName.value)
		{
			var labelID = pageIdDropdown.options[pageIdDropdown.selectedIndex].value;
			var label = getLabelById(labelID, page);
			if(!label) {
				label = xml.createElement('label');
				page.appendChild(label);
			}
			label.setAttribute('id', labelID);
			label.setAttribute('name', labelName.value);
			//alert(new XMLSerializer().serializeToString(xml));
		}
		
		updateXML();
	}
	function showLabelData(event)
	{
		for(var a=0;a<pageIdDropdown.options.length;a++)
		{
			if(pageIdDropdown.options[a].value==event.target.title)
			{
				pageIdDropdown.selectedIndex=a;
				onCreateDropdownChange();
				a=pageIdDropdown.options.length+1;
			}
		}
		
	}
	function updateXML() {
		var xloader = new xURLLoader();
		xloader.load('lib/com/cms/saveXML.php', xmlUpdateComplete, "POST", 'xml=' + encodeURIComponent(new XMLSerializer().serializeToString(xml)));
		console.log('updateXML');
	}

	function xmlUpdateComplete(t, x) {
		updateRightLabels();
	}

	getXML();
	function updateRightLabels()
	{
		createLabelList.innerHTML="";
		
		var page = getPageById(currentPage.location);
		if(page)
		{
			var labels = page.getElementsByTagName('label');
			var label;
			for(var i = 0; i < labels.length; i++) {
					var div = document.createElement('div');
					div.className="rightLabelHolder";
					var a = document.createElement('a');
					
					a.title = labels[i].getAttribute("id");
					a.innerHTML = labels[i].getAttribute("name");
					a.addEventListener('click',showLabelData);
					div.appendChild(a);
					var close = document.createElement('a');
					close.setAttribute('alt',labels[i].getAttribute("id"));
					close.setAttribute('class','removeLabel');
					close.innerHTML = 'x';
					close.addEventListener('click',removeLabelData);
					div.appendChild(close);
					createLabelList.appendChild(div);
			}
		}
	}
	function removeLabelData(event)
	{
		var page = getPageById(currentPage.location);
		var id = event.target.getAttribute('alt');
		var label = getLabelById(id, page);
		if(label) {
		page.removeChild(label);
		var labels = page.getElementsByTagName('label');
		if(labels.length<1)xml.documentElement.removeChild(page);
		updateRightLabels();
		updateXML();
		}
	}
</script>
<?php
function getDirectory( $path = '.', $level = 0 ){

$ignore = array( 'cgi-bin', '.', '..' );
// Directories to ignore when listing output. Many hosts
// will deny PHP access to the cgi-bin.

$dh = @opendir( $path );
// Open the directory to the handle $dh

while( false !== ( $file = readdir( $dh ) ) ){
// Loop through the directory

if( !in_array( $file, $ignore ) ){
// Check that this file is not to be ignored

$spaces = str_repeat( '&nbsp;', ( $level * 4 ) );
// Just to add spacing to the list, to better
// show the directory tree.

if( is_dir( "$path/$file" ) && substr($file,0, 1)!="."){
// Its a directory, so we need to keep reading down...

if(!strstr($path,"..//dingo") && !strstr($file,"dingo") )
updateSideBar("$file","$path/$file",$level * 5, realpath($path.'/'.$file));

getDirectory( "$path/$file", ($level+1));
// Re-call this same function but on a new directory.
// this is what makes function recursive.

} else {
if(!strstr($path,"..//dingo") && !strstr($file,"dingo") && substr($file,0, 1)!=".")
updateSideBar("$file","$path/$file",$level * 5,realpath($path.'/'.$file));
	

// Just print out the filename

}

}

}

closedir( $dh );
// Close the directory handle
}
function updateSideBar($str,$path,$padding,$realpath)
{
	
?>
<script type="text/javascript">

	var a = document.createElement('a');
		
		a.onclick=onCreatePageClick;
		a.id = "<?php echo $path;?>";
		a.title = "<?php echo $str;?>";
		var path = "<?php echo rawurlencode($realpath); ?>";
		a.setAttribute('alt',path);
		a.innerHTML = "<?php echo $str;?><br/>";
		sidebar.appendChild(a);
		a.style.paddingLeft = "<?php echo $padding;?>px";</script>
<?php
}
?>
<script type="text/javascript">
	function onCreatePageClick(event) {
		clearAll();
		pageLabel.value="";
		
		document.getElementById('php').src = "lib/com/cms/getPageTagNames.php?url=" + encodeURIComponent(event.target.id) + "&host=" + encodeURIComponent(document.URL);
		pageName.innerHTML = "Page: " + event.target.title;
		pageLocation.innerHTML = "Loaction: " + event.target.id;
		createView.style.visibility = "visible";
		welcomeView.style.visibility = "hidden";
		currentPage.location = event.target.id;
		currentPage.name = event.target.title;
		currentPage.realpath = event.target.getAttribute('alt');
		var page = getPageById(currentPage.location);
		if(page)
		{
			if(page.getAttribute("label"))pageLabel.value=page.getAttribute("label");
		}
		updateRightLabels();
	}
	function clearAll()
	{
		labelName.value="";
		pageIdDropdown.innerHTML = "";
		createLabelList.innerHTML = "";
	}
</script>
<?php

getDirectory("../");
}
catch (Exception $ex)
{
	echo "I HANDLED THIS ERROR: " . $ex->getMessage();
}
 
ExceptionThrower::Stop();
?>