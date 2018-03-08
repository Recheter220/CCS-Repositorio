jQuery(function($){
	$(".mask-rg").mask("99.999.999-9");
	$(".mask-cpf").mask("999.999.999-99");
	$(".mask-cnpj").mask("00.000.000/0000-00");
	$(".mask-tel").mask("(00) 0000-00009");
	$(".mask-cep").mask("00000-000")
});

function masktel() {
	jQuery(function($){
		$(".mask-tel").mask("(00) 0000-00009");
		
		$(".mask-tel").keyup(function(event) {
			if($(this).val().length == 15) { // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
				$(this).mask("(00) 00000-0000");
			} else {
				$(this).mask("(00) 0000-00009");
			}
		});
	});
}
function maskmoney() {
	jQuery(function($){
		$(".mask-money").keyup(function(event) {
			$(".mask-money").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
		});
	});
}

function TestaCPF(strCPF) {
	var Soma;
	var Resto;
	Soma = 0;
	if (strCPF == "00000000000") return false;

	for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
	Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))  Resto = 0;
	if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

	Soma = 0;
	for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
	Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))  Resto = 0;
	if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
	return true;
}