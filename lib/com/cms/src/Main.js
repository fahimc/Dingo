function xURLLoader() {
	var xhttp;
	var cb;
	this.load = function(url, callback, method, params) {
		cb = callback;
		if(window.XMLHttpRequest) {
			xhttp = new XMLHttpRequest();
		} else// IE 5/6
		{
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if(!method)
			method = "GET";
		if(method == "GET" && params) {
			url += "?" + params;

		}
		xhttp.onreadystatechange = this.onStatus;
		xhttp.open(method, url, true);
		if(method == "POST") {
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.setRequestHeader("Content-length", params.length);
			xhttp.setRequestHeader("Connection", "close");
		}
		try {

			xhttp.send(params);
		} catch(e) {

			cb(null, null);
		}
	}

	this.onStatus = function(e) {
		if(xhttp.readyState == 4) {
			if(xhttp.status == 200 || window.location.href.indexOf("http") == -1) {

				//public.xml=xhttp.responseText;

				cb(xhttp.responseText, xhttp.responseXML);

			} else {
				console.log("error 2");

			}
		} else {

			console.log("error 1");
		}

	}
}

var currentPage = {
	location : "",
	name : "",
	realpath:""
};
