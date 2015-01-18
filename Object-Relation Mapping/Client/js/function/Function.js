/**
 * 
 */

window.onload = function(){
	//alert('OK');
	ajax();
}

function ajax() {
	var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			console.log(xmlhttp.responseText);
			var provincia = parseJsonToObject(xmlhttp.responseText);
			console.log(provincia);
		}
	}
	xmlhttp.open("GET","http://localhost/git/Object-Relation%20Mapping/Object-Relation%20Mapping/Server/Services/Api.php?q=getProvincia/1/2", true);
	xmlhttp.send();
}