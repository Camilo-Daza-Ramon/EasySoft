function formato_dinero(valor){
	valor = parseFloat((valor == null || valor == "null")? 0 : valor);
	return valor.toLocaleString('es-CO',{style:'currency', currency:'COP', minimumFractionDigits:2});

}