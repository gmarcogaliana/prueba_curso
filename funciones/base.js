function fValidaText(texto){var rETel=/^\S/;return rETel.test(texto);}
function fValidaTel(telefono){var rETel=/^\d{9}$/g;return rETel.test(telefono);}
function fValidaCp(telefono){var rETel=/^\d{5}$/g;return rETel.test(telefono);}
function fValidaMail(email){var reEmail=/^(\w+\.?)*\w+@(\w+\.)+\w+$/;return reEmail.test(email);}
function fvalidaDNI(actual){
	var letras=['T','R','W','A','G','M','Y','F','P','D','X','B','N','J','Z','S','Q','V','H','L','C','K','E','T'];
	var dni=actual.value;
	var letra=dni.substring(dni.length-1);
	letra=letra.toUpperCase();
	var numero=dni.substring(0,dni.length-1)
	}


function alertaCitas(mensaje){
	document.getElementById('mensajeErrorFormulario').innerHTML = '';
	mensaje = '<p>'+mensaje+'</p>';
	document.getElementById('mensajeErrorFormulario').innerHTML = mensaje;
	}
function verificarCampo(nombre){
	document.getElementById(nombre+'NoVerificado').style.display='none';
	document.getElementById(nombre+'Verificado').style.display='none';
	document.getElementById(nombre+'Verificado').style.display='inline';
	document.getElementById(nombre+'NoVerificado').style.display='none';
	}
	
function mensajeErrorCampos(actual,alerta){
	alertaCitas(alerta);
	document.getElementById("mensajeErrorFormulario").style.display= "block";
	actual.value='';
}

function fcomprobarMail(actual){
	if(actual.value=="")
	{
		verificarCampo(actual.name);
		mailVerificado=true;
	}else{
		if(!fValidaMail(actual.value)){
			alerta = "Correo no válido. Debe escribir una direccion de correo valida";
			mensajeErrorCampos(actual,alerta);
			document.getElementById(actual.name+'NoVerificado').style.display='inline';
		}else{
			verificarCampo(actual.name);
			mailVerificado=true;
			document.getElementById("mensajeErrorFormulario").style.display= "none";
		}
	}
}
function fcompruebaTexto(actual){
	//console.log(actual.name);
	if(!fValidaText(actual.value)){
		console.log(actual.value);
		alerta = "El campo "+actual.name+" debe estar cumplimentado.";
		mensajeErrorCampos(actual,alerta);
		apellidosVerificado=false;
		document.getElementById(actual.name+'NoVerificado').style.display='inline';
		}else{
			console.log(actual.value);
			verificarCampo(actual.name);
			apellidosVerificado=true;
			document.getElementById("mensajeErrorFormulario").style.display= "none";
			}
}
function fcompruebaTel(actual){if(actual.value==""){
	alerta ="El campo "+actual.name+" debe estar cumplimentado.";
	mensajeErrorCampos(actual,alerta);
	numVerificado=false;
	}else{
		if(!fValidaTel(actual.value)){
			alerta = "Número de teléfono no válido. Debe escribir 9 cifras";
			mensajeErrorCampos(actual,alerta);
			numVerificado=false;
			}else{
				verificarCampo(actual.name);
				numVerificado=true;
				document.getElementById("mensajeErrorFormulario").style.display= "none";
				}
		}
}
function fcompruebaCp(actual){if(actual.value==""){
	alerta ="El campo "+actual.name+" debe estar cumplimentado.";
	mensajeErrorCampos(actual,alerta);
	numVerificado=false;
	}else{
		if(!fValidaCp(actual.value)){
			alerta = "Codigo Postal no válido. Debe escribir 5 cifras";
			mensajeErrorCampos(actual,alerta);
			numVerificado=false;
			}else{
				verificarCampo(actual.name);
				numVerificado=true;
				document.getElementById("mensajeErrorFormulario").style.display= "none";
				}
		}
}

function mostrarDiv(contenedor){
	console.log(contenedor);
	$(contenedor).show();
	 $(".oculto").hide();
	  if ($(contenedor).is(":visible")){
               $(contenedor).hide();
               return false;
          }else{
        $(".oculto").hide();                             
        $(contenedor).fadeToggle("slow");
        return false;
          }
	//console.log(contenedor);
	//$(contenedor).show();
	
}
var idTrans =  []; 

function capturarCheck(actual){
	console.log(actual.checked);
	if(actual.checked === true){
		console.log("check el valor "+ actual.value);
		idTrans.push(actual.value);
	}else{
		console.log("No check el valor "+ actual.value);
		var index = idTrans.indexOf(actual.value);
		if (index > -1) {
			idTrans.splice(index, 1);
			}
		}
	// Hay que borrar si se desmarca...
	
	console.log(idTrans.toString());
	document.getElementById('idsEncargados').value = idTrans.toString();
	
}

function capturarEncargados(){
	
	
}

function confirmarFormulario (nombreForm, mensaje){
		var formulario = document.getElementById(nombreForm);	
		console.log(nombreForm);
	
	return confirm(mensaje);
}

function desasignarAdmin(idComunidad, IdAdministrador, razonSocial ){
	if(confirm("estas seguro que deseas desasignar el administrador de esta somunidad") == true){
		alert(idComunidad + ' ' + IdAdministrador+ ' ' + razonSocial);
	}
	else{
		
	}
}
