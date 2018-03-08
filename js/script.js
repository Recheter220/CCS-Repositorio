function alteraAno() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			window.location.reload();
		}
	};
	xhttp.open("POST", "ajax/alteraAno.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("proc=" + $("#anops").val());
}

function confirm(titulo, corpo, callbackTrue = null, paramsTrue = null, callbackFalse = null, paramsFalse = null) {
	swal({
		title: titulo, // "Confirmar aprovação",
		html: corpo, // "Você tem certeza de que deseja alterar o status do documento selecionado para <strong>aprovado</strong>?",
		type: "question", 
		confirmButtonText: "Sim", 
		showCancelButton: true, 
		cancelButtonText: "Não", 
		cancelButtonColor: "#d9534f" 
	}).then(
		function() {
			if (callbackTrue) {
				callbackTrue.apply(null, paramsTrue);
			}
		},
		function() {
			if (callbackFalse) {
				callbackFalse.apply(null, paramsFalse);
			}
		}
	);
}

function prompt(titulo, corpo, placeholder = "", callbackTrue = null, paramsTrue = null, callbackFalse = null, paramsFalse = null) {
	swal({
		title: titulo, 
		html: corpo, 
		input: "text", 
		type: "info", 
		allowOutsideClick: false, 
		confirmButtonText: "OK", 
		showCancelButton: true, 
		cancelButtonText: "Cancelar", 
		cancelButtonColor: "#d9534f", 
		closeOnConfirm: false, 
		animation: "slide-from-top", 
		inputPlaceholder: placeholder, 
		preConfirm: function (texto) {
			return new Promise(function (resolve, reject) {
				if (!texto) {
					reject('O campo acima é de preenchimento obrigatório.');
				} else {
					resolve();
				}
			})
		}
	}).then(
		function(inputValue) {
			if (callbackTrue) {
				paramsTrue.push(inputValue);
				callbackTrue.apply(null, paramsTrue);
			}
		},
		function(inputValue) {
			if (callbackFalse) {
				paramsFalse.push(inputValue);
				callbackFalse.apply(null, paramsFalse);
			}
		}
	);
}

function atualizaStatusArtigo(id, estado, botao, motivo = "") {
	if (!(motivo)) {
		motivo = "";
	}
	if (!botao.getAttribute("class").includes("disabled")) {
		var td = botao.parentElement;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				if (xhttp.responseText.startsWith("<button")) {
					swal({
						title: "Sucesso",
						html: "Status modificado com sucesso!",
						type: "success"
					});
					td.innerHTML = xhttp.responseText;
				}
				else {
					swal({
						title: "Ocorreu um erro",
						html: "Não foi possível alterar o status do documento." + 
							" A seguinte mensagem foi retornada: <br /><strong>" + 
							xhttp.responseText + 
							"</strong>. <br />Caso o problema persista, entre em contato com o administrador. ",
						type: "error"
					});
				}
			}
		};
		xhttp.open("POST", "ajax/atualizaStatusArtigo.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("&id=" + id + "&estado=" + estado + "&motivo=" + motivo);
	}
}
	
var carregando = 0;
function removeCurso(curso, botao) {
	if (carregando == 0) {
		carregando = 1;
		var linha = botao.parentElement.parentElement.rowIndex;
		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				if (xhttp.responseText == "Sucesso") {
					document.getElementById("tabela-cursos").deleteRow(linha);
					swal({
						title: "Sucesso",
						html: "Curso removido com sucesso!",
						type: "success"
					});
				}
				else {
					swal({
						title: "Ocorreu um erro",
						html: "Não foi possível remover o curso selecionado." + 
							" A seguinte mensagem foi retornada: <br /><strong>" + 
							xhttp.responseText + 
							"</strong>. <br />Caso o problema persista, entre em contato com o administrador. ",
						type: "error"
					});
				}
				carregando = 0;
			}
		};
		xhttp.open("GET", ("ajax/removeCurso.php?curso=" + curso), true);
		xhttp.send();
	}
}