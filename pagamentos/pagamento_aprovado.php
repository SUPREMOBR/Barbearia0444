<?php

$id_conta = @$_GET['id_agd'];
if ($id_conta != "") {
	if (@$porc_servico > 0) {
		echo 'Faça o pagamento antes de ir para o agendamento';
		exit();
	}
	require("../sistema/conexao.php");
	$valor_pago = '0';
	$query = $pdo->query("SELECT * FROM agendamentos_temp where id = '$id_conta'");
} else {
	$query = $pdo->query("SELECT * FROM agendamentos_temp where ref_pix = '$ref_pix'");
}
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$cliente = $resultado[0]['cliente'];
$servico = $resultado[0]['servico'];
$funcionario = $resultado[0]['funcionario'];
$data = $resultado[0]['data'];
$hora = $resultado[0]['hora'];
$obs = $resultado[0]['obs'];
$data_lancamento = $resultado[0]['data_lancamento'];
$usuario = $resultado[0]['usuario'];
$status = $resultado[0]['status'];
$hash = $resultado[0]['hash'];
$ref_pix = $resultado[0]['ref_pix'];
$data_agd = $resultado[0]['data'];
$hora_do_agd = $resultado[0]['hora'];

if (@$forma_pagamento == "pix") {
	$forma_pagamento = "Pix";
} else {
	$forma_pagamento = "Cartão de Crédito";
}

$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_serv = @$resultado[0]['nome'];
$tempo = @$resultado[0]['tempo'];

$servico_conc = $nome_serv . " (Site)";





$query = $pdo->query("INSERT INTO agendamentos SET funcionario = '$funcionario', cliente = '$cliente', hora = '$hora', data = '$data', 
usuario = '0', status = 'Agendado', obs = '$obs', data_lancamento = curDate(), servico = '$servico', hash = '$hash', ref_pix = '$ref_pix', 
valor_pago = '$valor_pago'");

$ult_id = $pdo->lastInsertId();

if ($id_conta == "") {
	$pdo->query("INSERT INTO receber SET descricao = '$servico_conc', tipo = 'Serviço', valor = '$valor_pago', data_lancamento = curDate(), 
	data_vencimento = curDate(), data_pagamento = curDate(), usuario_lancou = '0', usuario_baixa = '0', foto = 'sem-foto.jpg', 
	pessoa = '$cliente', pago = 'Sim', servico = '$servico', funcionario = '$funcionario', obs = '', pagamento = '$forma_pagamento', referencia = '$ult_id'");
}




$dataF = implode('/', array_reverse(explode('-', $data)));
$horaF = date("H:i", @strtotime($hora));



$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = @$resultado[0]['intervalo'];
$nome_funcionario = @$resultado[0]['nome'];
$telefone_funcionario = @$resultado[0]['telefone'];

$hora_minutos = @strtotime("+$tempo minutes", @strtotime($hora));
$hora_final_servico = date('H:i:s', $hora_minutos);


if ($msg_agendamento == 'Api') {


	$query = $pdo->query("SELECT * FROM clientes where id = '$cliente' ");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome = $resultado[0]['nome'];
	$telefone = $resultado[0]['telefone'];
	$telefone_cliente = $resultado[0]['telefone'];




	$dataF = implode('/', array_reverse(explode('-', $data)));
	$horaF = date("H:i", @strtotime($hora));

	$mensagem = '_Novo Agendamento_ %0A';
	$mensagem .= 'Profissional: *' . $nome_funcionario . '* %0A';
	$mensagem .= 'Serviço: *' . $nome_serv . '* %0A';
	$mensagem .= 'Data: *' . $dataF . '* %0A';
	$mensagem .= 'Hora: *' . $horaF . '* %0A';
	$mensagem .= 'Cliente: *' . $nome . '* %0A';
	if ($obs != "") {
		$mensagem .= 'Obs: *' . $obs . '* %0A';
	}

	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone);

	require('../ajax/api-texto.php');

	if ($telefone_funcionario != $whatsapp_sistema) {
		$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone_funcionario);
		require('../ajax/api-texto.php');
	}


	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);
	//agendar o alerta de confirmação
	$hora_atual = date('H:i:s');
	$data_atual = date('Y-m-d');
	$hora_minutos = @strtotime("-$minutos_aviso hours", @strtotime($hora));
	$nova_hora = date('H:i:s', $hora_minutos);



	if (@strtotime($hora_atual) < @strtotime($nova_hora) or @strtotime($data_atual) != @strtotime($data_agd)) {

		$mensagem = '*Confirmação de Agendamento* ';
		$mensagem .= '                              Profissional: *' . $nome_funcionario . '*';
		$mensagem .= '                                         Serviço: *' . $nome_serv . '*';
		$mensagem .= '                                               	       Data: *' . $dataF . '*';
		$mensagem .= '                                               	       Hora: *' . $horaF . '*';
		$mensagem .= '                                                             ';
		$mensagem .= '                                 _(Digite o número com a opção desejada)_';
		$mensagem .= '                                 1.  Digite 1️⃣ para confirmar ✅';
		$mensagem .= '                                 2.  Digite 2️⃣ para Cancelar ❌';

		$id_envio = $ult_id;
		$data_envio = $data_agd . ' ' . $hora_do_agd;

		if ($minutos_aviso > 0) {
			require("../ajax/confirmacao.php");
			$id_hash = $id;
			$pdo->query("UPDATE agendamentos SET hash = '$id_hash' WHERE id = '$ult_id'");
		}
	}
}



while (@strtotime($hora) < @strtotime($hora_final_servico)) {

	$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($hora));
	$hora = date('H:i:s', $hora_minutos);

	if (@strtotime($hora) < @strtotime($hora_final_servico)) {
		$query = $pdo->query("INSERT INTO horarios_agd SET agendamento = '$ult_id', horario = '$hora', funcionario = '$funcionario', data = '$data_agd'");
	}
}


if ($id_conta != "") {
	echo "<script>window.location='../meus-agendamentos.php'</script>";
}
