( function(window) {
	function Main() {
		window.addEventListener("load", loadComplete);
	}

	function loadComplete() {
		var main = document.getElementById('main');
		var createView = document.getElementById('createView');
		var createLeftCol = document.getElementById('createLeftCol');
		var pageLabelHolder = document.getElementById('pageLabelHolder');
		var adminLabelCopy = document.getElementById('adminLabelCopy');
		var headers = document.getElementsByClassName('createHeader');
		//var nicEdit = document.getElementsByClassName('nicEdit-main');
		//var nicEditButtons = document.getElementsByClassName('nicEdit-panelContain');
		var cview = document.getElementById('createView');
		var createLeftCol = document.getElementById('createLeftCol');
		main.style.width = (stageWidth() - 200) + "px";
		createView.style.width = (stageWidth() - 200) + "px";
		//createView.style.width = (stageWidth() - 200) + "px";
		pageLabelHolder.style.width = (stageWidth() - 400) + "px";
		createLeftCol.style.width = (stageWidth() - 400) + "px";
		//nicEditButtons[0].style.width = (stageWidth() - 400) + "px";
		//nicEdit[0].style.width = (stageWidth() - 400) + "px";
		for(var i = 0; i < headers.length; i++) {
			headers[i].style.width = (stageWidth() - 400) + "px";
		}
		
	}

	Main()
}(window));
