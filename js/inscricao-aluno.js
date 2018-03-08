function addTelefone(botao) {
	var pai = botao.parentElement.parentElement.parentElement;

	var div0 = document.createElement("DIV");
	var div1 = document.createElement("DIV");
	var input = document.createElement("INPUT");
	var div2 = document.createElement("DIV");
	var button = document.createElement("BUTTON");
	var texto = document.createTextNode("-");

	div1.setAttribute("class", "col-xs-10");
	input.setAttribute("class", "form-control mask-tel");
	input.setAttribute("name", "telefone[]");
	div2.setAttribute("class", "col-xs-2");
	button.setAttribute("type", "button");
	button.setAttribute("class", "form-control btn btn-danger");
	button.setAttribute("onclick", "rmvTelefone(this)");
	//button.setAttribute()
	button.appendChild(texto);

	div0.appendChild(div1);
	div0.appendChild(div2);
	div1.appendChild(input);
	div2.appendChild(button);
	pai.appendChild(div0);

	masktel();
}

function rmvTelefone(botao) {
	var pai = botao.parentElement.parentElement.parentElement;
	var filho = botao.parentElement.parentElement;
	pai.removeChild(filho);
}

function addMembroFamilia(botao) {
	var pai = botao.parentElement.parentElement.parentElement;

	var div0 = document.createElement("DIV");
	var div1 = document.createElement("DIV");
	var div2 = document.createElement("DIV");
	var div3 = document.createElement("DIV");
	var div4 = document.createElement("DIV");

	var input1 = document.createElement("INPUT");
	var input2 = document.createElement("SELECT");
	var input3 = document.createElement("INPUT");
	var input4 = document.createElement("INPUT");

	var label1 = document.createElement("LABEL");
	var label2 = document.createElement("LABEL");
	var label3 = document.createElement("LABEL");
	var label4 = document.createElement("LABEL");

	var txtLabel1 = document.createTextNode("Nome Completo");
	var txtLabel2 = document.createTextNode("Grau de Parentesco");
	var txtLabel3 = document.createTextNode("Data de Nascimento");
	var txtLabel4 = document.createTextNode("Renda Mensal");

	var option1 = document.createElement("OPTION");
	var option2 = document.createElement("OPTION");
	var option3 = document.createElement("OPTION");
	var option4 = document.createElement("OPTION");
	var option5 = document.createElement("OPTION");
	var option6 = document.createElement("OPTION");
	var option7 = document.createElement("OPTION");
	var option8 = document.createElement("OPTION");
	var option9 = document.createElement("OPTION");
	var option10 = document.createElement("OPTION");
	var option11 = document.createElement("OPTION");
	var option12 = document.createElement("OPTION");
	option1.setAttribute("value", "1");
	option1.innerHTML = "Cônjuge";
	input2.appendChild(option1);
	option2.setAttribute("value", "2");
	option2.innerHTML = "Filho/Filha";
	input2.appendChild(option2);
	option3.setAttribute("value", "3");
	option3.innerHTML = "Pai/Mãe";
	input2.appendChild(option3);
	option4.setAttribute("value", "4");
	option4.innerHTML = "Padrasto/Madrasta";
	input2.appendChild(option4);
	option5.setAttribute("value", "5");
	option5.innerHTML = "Avô/Avó";
	input2.appendChild(option5);
	option6.setAttribute("value", "6");
	option6.innerHTML = "Irmão/Irmã";
	input2.appendChild(option6);
	option7.setAttribute("value", "7");
	option7.innerHTML = "Tio/Tia";
	input2.appendChild(option7);
	option8.setAttribute("value", "8");
	option8.innerHTML = "Primo/Prima";
	input2.appendChild(option8);
	option9.setAttribute("value", "9");
	option9.innerHTML = "Sobrinho/Sobrinha";
	input2.appendChild(option9);
	option10.setAttribute("value", "10");
	option10.innerHTML = "Enteado/Enteada";
	input2.appendChild(option10);
	option11.setAttribute("value", "11");
	option11.innerHTML = "Cunhado/Cunhada";
	input2.appendChild(option11);
	option12.setAttribute("value", "12");
	option12.innerHTML = "Outros";
	input2.appendChild(option12);

	var h4 = document.createElement("H4");
	var button = document.createElement("BUTTON");
	var texto = document.createTextNode(" Dados do membro do grupo familiar ");
	var menos = document.createTextNode(" - ");

	div0.setAttribute("class", "form-group div-frame col-xs-12");
	div1.setAttribute("class", "col-xs-6");
	div2.setAttribute("class", "col-xs-6");
	div3.setAttribute("class", "col-xs-6");
	div4.setAttribute("class", "col-xs-6");

	input1.setAttribute("class", "form-control");
	input1.setAttribute("type", "text");
	input1.setAttribute("name", "grupo-familiar-nome[]");
	input2.setAttribute("class", "form-control");
	input2.setAttribute("name", "grupo-familiar-parentesco[]");
	input3.setAttribute("class", "form-control");
	input3.setAttribute("type", "date");
	input3.setAttribute("name", "grupo-familiar-data-nasc[]");
	input4.setAttribute("class", "form-control mask-money");
	input4.setAttribute("type", "text");
	input4.setAttribute("name", "grupo-familiar-renda[]");
	input4.setAttribute("onchange", "somaRendimentos()");

	label1.appendChild(txtLabel1);
	label2.appendChild(txtLabel2);
	label3.appendChild(txtLabel3);
	label4.appendChild(txtLabel4);

	h4.appendChild(texto);
	button.setAttribute("type", "button");
	button.setAttribute("class", "btn btn-danger");
	button.setAttribute("onclick", "rmvMembroFamilia(this)");
	//button.setAttribute()
	button.appendChild(menos);
	h4.appendChild(button);

	div0.appendChild(h4);
	div0.appendChild(div1);
	div0.appendChild(div2);
	div0.appendChild(div3);
	div0.appendChild(div4);

	div1.appendChild(label1);
	div1.appendChild(input1);
	div2.appendChild(label2);
	div2.appendChild(input2);
	div3.appendChild(label3);
	div3.appendChild(input3);
	div4.appendChild(label4);
	div4.appendChild(input4);
	pai.appendChild(div0);

	maskmoney();
}

function rmvMembroFamilia(botao) {
	var pai = botao.parentElement.parentElement.parentElement;
	var filho = botao.parentElement.parentElement;
	pai.removeChild(filho);
}

function somaRendimentos() {
	var rendas = document.getElementsByName("grupo-familiar-renda[]");
	var soma = 0;
	for (var i = 0; i < rendas.length; i++) {
		var num = $(rendas[i]).maskMoney('unmasked')[0];
		soma = parseFloat(soma) + parseFloat(num);
	}
	$("#rendimentos").text("R$ " + soma.toFixed(2));
}

function carregaCursos(selected = 0) {
	var inst = $("#instituicao").val();
	var cursos = document.getElementById("curso");

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			cursos.innerHTML = xhttp.responseText;
		}
	};
	xhttp.open("GET", ("ajax/carregaCursos.php?inst=" + inst + "&selected=" + selected), true);
	xhttp.send();
}

$(document).ready(function() {
	function limpa_formulário_cep() {
		// Limpa valores do formulário de cep.
		$("#end-cep").val("");
		$("#end-rua").val("");
		$("#end-bairro").val("");
		$("#end-cidade").val("");
		$("#end-complemento").val("");
	}

	//Quando o campo cep perde o foco.
	$("#end-cep").blur(function() {

		//Nova variável "cep" somente com dígitos.
		var cep = $(this).val().replace(/\D/g, '');

		//Verifica se campo cep possui valor informado.
		if (cep != "") {

			//Expressão regular para validar o CEP.
			var validacep = /^[0-9]{8}$/;

			//Valida o formato do CEP.
			if(validacep.test(cep)) {

				//Preenche os campos com "..." enquanto consulta webservice.
				$("#end-rua").val("...");
				$("#end-bairro").val("...");
				$("#end-cidade").val("...");
				$("#end-complemento").val("...");
					

				//Consulta o webservice viacep.com.br/
				$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

					if (!("erro" in dados)) {
						//Atualiza os campos com os valores da consulta.
						$("#end-rua").val(dados.logradouro);
						$("#end-bairro").val(dados.bairro);
						$("#end-cidade").val(dados.localidade);
						$("#end-complemento").val(dados.complemento);
					} //end if.
					else {
						//CEP pesquisado não foi encontrado.
						limpa_formulário_cep();
						alert("CEP não encontrado.");
					}
				});
			} //end if.
			else {
				//cep é inválido.
				limpa_formulário_cep();
				alert("Formato de CEP inválido.");
			}
		} //end if.
		else {
			//cep sem valor, limpa formulário.
			limpa_formulário_cep();
		}
	});
	});