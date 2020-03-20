var myRequest = getXMLHTTPRequest();
var host = 'https://gestorlopdfc.addecuo.es';
function getXMLHTTPRequest(){
	var request = false;
	
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	}else{
		if(window.ActiveXObject){
			try{
				request = new ActiveXObject("Msml2.XMLHTTP");
			}catch(err1){
				try{
					request = new ActiveXObject("Microsoft.XMLHTTP");
				}catch(err2){
					request = false;
				}
			} // fin 1er try & catch
		} // fin if
	} // fin if
	return request;
}

function respuestaAJAX() {
	// solo entra cuando se completa la peticion
	if(myRequest.readyState == 4) {
		// si la respuesta HTTP es OK
		if(myRequest.status == 200) {
			document.getElementById('muestraAjax').innerHTML= myRequest.responseText;
			//console.log(' 4 y 200 ok ' + myRequest.responseText);
		} else {
			document.getElementById('muestraAjax').innerHTML= myRequest.status;
			//console.log(myRequest.status);
		}
	}else{
		
		document.getElementById('muestraAjax').innerHTML="<div style='width: 8px; margin: 0 auto;'><img style='width:50px; ' src ='http://62.43.188.94:65351/vtigercrm63/layouts/vlayout/modules/Campaigns/resources/carga.gif'/></div>";
		//console.log('STATUS: '+ myRequest.status + ' -> READYSTATE '+ myRequest.readyState + ' -> ERROR  ' + myRequest.statusText);
	}
   
}
function respuestaAJAX2() {
	// solo entra cuando se completa la peticion
	if(myRequest.readyState == 4) {
		// si la respuesta HTTP es OK
		if(myRequest.status == 200) {
			document.getElementById('resultadoBusquedaProveedor').innerHTML= myRequest.responseText;
			//console.log(' 4 y 200 ok ' + myRequest.responseText);
		} else {
			document.getElementById('resultadoBusquedaProveedor').innerHTML= myRequest.status;
			//console.log(myRequest.status);
		}
	}else{
		
		document.getElementById('resultadoBusquedaProveedor').innerHTML="<div style='width: 8px; margin: 0 auto;'><img style='width:50px; ' src ='http://62.43.188.94:65351/vtigercrm63/layouts/vlayout/modules/Campaigns/resources/carga.gif'/></div>";
		//console.log('STATUS: '+ myRequest.status + ' -> READYSTATE '+ myRequest.readyState + ' -> ERROR  ' + myRequest.statusText);
	}
   
}
function respuestaAJAX3() {
	// solo entra cuando se completa la peticion
	if(myRequest.readyState == 4) {
		// si la respuesta HTTP es OK
		if(myRequest.status == 200) {
			document.getElementById('resultadoBusquedaComunidad').innerHTML= myRequest.responseText;
			//console.log(' 4 y 200 ok ' + myRequest.responseText);
		} else {
			document.getElementById('resultadoBusquedaComunidad').innerHTML= myRequest.status;
			//console.log(myRequest.status);
		}
	}else{
		
		document.getElementById('resultadoBusquedaComunidad').innerHTML="<div style='width: 8px; margin: 0 auto;'><img style='width:50px; ' src =''/></div>";
		//console.log('STATUS: '+ myRequest.status + ' -> READYSTATE '+ myRequest.readyState + ' -> ERROR  ' + myRequest.statusText);
	}
   
}

function limpiarRespuesta(){
	document.getElementById("muestraAjax").innerHTML = "";
}			
				
function buscarProveedor(actual){
	//console.log(actual.value);
	var valor = actual.value
	//console.log(valor);
	var rand =parseInt(Math.random()*99999999)+new Date().getTime();
	var url = host+"/funciones/buscarProveedor.php?valor="+valor+"&rand="+ rand;
	//var url = "././funciones/buscarProveedor.php?valor="+valor+"&rand="+ rand;
	//console.log(' url ->' + url);
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respuestaAJAX;
	myRequest.send(null);
}
function buscarProveedorAsignar(actual, id){
	//console.log(actual.value);
	var valor = actual.value
	//console.log(valor);
	var rand =parseInt(Math.random()*99999999)+new Date().getTime();
	var url = host+"/funciones/buscarProveedorAsignar.php?valor="+valor+"&rand="+ rand;
	//var url = "././funciones/buscarProveedor.php?valor="+valor+"&rand="+ rand;
	//console.log(' url ->' + url);
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respuestaAJAX2;
	myRequest.send(null);
}
function buscarComunidad(actual, id, rol, nombreAdmin){
	console.log(actual.value);
	console.log(id);
	console.log(rol);
	console.log(nombreAdmin);
	var valor = actual.value
	//console.log(valor);
	var rand =parseInt(Math.random()*99999999)+new Date().getTime();
	var url = host+"/funciones/buscarAsociacion.php?id="+id+"&nombreAdmin="+nombreAdmin+"&rol="+rol+"&valor="+valor+"&rand="+ rand;
	//var url = "././funciones/buscarProveedor.php?valor="+valor+"&rand="+ rand;
	console.log(' url ->' + url);
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respuestaAJAX3;
	myRequest.send(null);
}
