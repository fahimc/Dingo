<?php

include ("template/basic/index.html");
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
	var upadteCopyButton = document.getElementById('upadteCopyButton');
	var labelName = document.getElementById('labelName');
	var pageLabel = document.getElementById('pageLabel');
	var adminLabelCopy = document.getElementById('adminLabelCopy');
	upadteCopyButton.addEventListener('click', onCreateLabelClick);
	pageIdDropdown.addEventListener('change', onCreateDropdownChange);
	
	var xml;
	var page;
	var pages;
	

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
			pages = xml.getElementsByTagName('page');
			var label ="";
			for(var i = 0; i < pages.length; i++) {
				label = pages[i].getAttribute("name");
				if(pages[i].getAttribute("label")) 
				{
					label = pages[i].getAttribute("label");
				}
				updateSideBar(label,pages[i].getAttribute("location"));
			}
		} else {
			
		}
		//alert(new XMLSerializer().serializeToString(xml));
		
	}
	function updateSideBar(str,id)
	{
	var a = document.createElement('a');
		
		a.onclick=onCreatePageClick;
		a.setAttribute('alt', id);
		a.title = str;
		a.innerHTML = str+"<br/>";
		sidebar.appendChild(a);
	}
	function onCreatePageClick(event) {
		updateTextArea('');
		console.log(event.target.getAttribute("alt"));
		page = getPageById(event.target.getAttribute("alt"));
		if(page)
		{
		pageName.innerHTML = "Web Page Name: " + event.target.innerHTML;
		createView.style.visibility = "visible";
		welcomeView.style.visibility = "hidden";
		currentPage.location = event.target.getAttribute("alt");
		currentPage.name = event.target.innerHTML;
		updateRightLabels();
		updateDropDown();
		}
	}
	function updateDropDown()
	{
		pageIdDropdown.innerHTML = "";
		page = getPageById(currentPage.location);
		var labels = page.getElementsByTagName('label');
		if(page && labels)
		{
			var opt = document.createElement('option');
			opt.text = "please select";
			opt.value = '-1';
			pageIdDropdown.add(opt);
	
			for(var a = 0; a < labels.length; a++) {
				opt = document.createElement('option');
				opt.text = labels[a].getAttribute("name");
				opt.value = labels[a].getAttribute("id");
				pageIdDropdown.add(opt);
			}
		}
	}
	function clearAll()
	{
		labelName.value="";
		pageIdDropdown.innerHTML = "";
		createLabelList.innerHTML = "";
	}
	function getPageById(id) {
		pages = xml.getElementsByTagName('page');
		page;
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
		updateTextArea('');
		page = getPageById(currentPage.location);
		if(page)
		{
			var labelID = pageIdDropdown.options[pageIdDropdown.selectedIndex].value;
			var label = getLabelById(labelID, page);
			if(label) 
			{
				
				getContentById(currentPage.location,label.getAttribute("id"));
			}
		}
	}
	function onCreateLabelClick(event) {
		
		page = getPageById(currentPage.location);
		if(page)
		{
			var labelID = pageIdDropdown.options[pageIdDropdown.selectedIndex].value;
			var label = getLabelById(labelID, page);
			if(label) 
			{
				
				setContentById(currentPage.location,label.getAttribute("id"),tinyMCE.get('adminLabelCopy').getContent());
			}
		}
		// updateXML();
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
		//updateRightLabels();
	}

	getXML();
	function updateRightLabels()
	{
		createLabelList.innerHTML="";
		
		page = getPageById(currentPage.location);
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
					
					createLabelList.appendChild(div);
			}
		}
	}
	function removeLabelData(event)
	{
		page = getPageById(currentPage.location);
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
	function getContentById(url,id)
	{
		
		document.getElementById('php').src = "lib/com/cms/getContentByTagId.php?url=" + encodeURIComponent(url) + "&host=" + encodeURIComponent(document.URL)+"&id="+id;
	}
	function setContentById(url,id,copy)
	{
		var xloader = new xURLLoader();
		xloader.load('lib/com/cms/setContentByTagId.php', onSetContentComplete, "POST", "url=" + encodeURIComponent(url) + "&host=" + encodeURIComponent(document.URL)+"&id="+id+"&copy="+escape(copy));
	//	console.log('updateXML');
		//document.getElementById('php').src = "lib/com/cms/setContentByTagId.php?url=" + encodeURIComponent(url) + "&host=" + encodeURIComponent(document.URL)+"&id="+id+"&copy="+escape(copy);
	}
	function onGetContent(content)
	{
		updateTextArea(decodeURI(content));
	}
	function onSetContentComplete()
	{
		
	}
	function updateTextArea(txt)
	{
		tinyMCE.getInstanceById('adminLabelCopy').setContent(txt);
	}
</script>

<?php

	
}
catch (Exception $ex)
{
	echo "I HANDLED THIS ERROR: " . $ex->getMessage();
}
 
ExceptionThrower::Stop();
?>