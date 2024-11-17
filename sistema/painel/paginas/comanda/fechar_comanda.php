<?php
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

@session_start();
$usuario_logado = @$_SESSION['id'];

$id = @$_POST['id'];
$cliente = @$_POST['cliente'];
$data_pagamento = $_POST['data_pagamento'];
$valor_serv = $_POST['valor'];
$valor_serv = str_replace(',', '.', $valor_serv);
$funcionario = $usuario_logado;

$pagamento = @$_POST['pagamento'];


$valor_serv_restante = @$_POST['valor_restante'];
$valor_serv_restante = str_replace(',', '.', $valor_serv_restante);
$pagamento_restante = @$_POST['pagamento_restante'];
$data_pagamento_restante = @$_POST['data_pagamento_restante'];

if ($valor_serv_restante == "") {
	$valor_serv_restante = 0;
}

$valor_total_servico = $valor_serv + $valor_serv_restante;


if (@$cliente == "") {
	echo 'Selecione um Cliente!';
	exit();
}


$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = $resultado[0]['taxa'];

if ($valor_taxa > 0 and strtotime($data_pagamento) <=  strtotime($data_atual)) {
	if ($taxa_sistema == 'Cliente') {
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100);
	} else {
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100);
	}
}





$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento_restante'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa'];

if ($valor_serv_restante > 0) {
	if ($valor_taxa > 0 and strtotime($data_pagamento_restante) <=  strtotime($data_atual)) {
		if ($taxa_sistema == 'Cliente') {
			$valor_serv_restante = $valor_serv_restante + $valor_serv_restante * ($valor_taxa / 100);
		} else {
			$valor_serv_restante = $valor_serv_restante - $valor_serv_restante * ($valor_taxa / 100);
		}
	}
}


if (strtotime($data_pagamento) <=  strtotime($data_atual)) {
	$pago = 'Sim';
	$data_pagamento2 = $data_pagamento;
	$usuario_baixa = $usuario_logado;
} else {
	$pago = 'Não';
	$data_pagamento2 = '';
	$usuario_baixa = 0;
}


//dados do cliente
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente' order by id desc limit 2");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone'];
$nome_cliente = $resultado2[0]['nome'];


$descricao = 'Comanda ' . $nome_cliente;

$pdo->query("UPDATE receber SET valor = '0', pago = 'Sim', data_pagamento = curDate(), usuario_baixa = '$usuario_logado', pagamento = '$pagamento' 
where comanda = '$id'");
$pdo->query("UPDATE comandas SET valor = '$valor_total_servico', status = 'Fechada' where id = '$id'");



if ($valor_serv_restante > 0) {
	if (strtotime($data_pagamento_restante) <=  strtotime($data_atual)) {
		$pago_restante = 'Sim';
		$data_pagamento2_restante = $data_pagamento_restante;
		$usuario_baixa_restante = $usuario_logado;
	} else {
		$pago_restante = 'Não';
		$data_pagamento2_restante = '';
		$usuario_baixa_restante = 0;
	}


	//lançar o restante
	$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Comanda', valor = '$valor_serv_restante', data_lancamento = curDate(), 
	data_vencimento = '$data_pagamento_restante', data_pagamento = '$data_pagamento2_restante', usuario_lancou = '$usuario_logado', 
	usuario_baixa = '$usuario_baixa', foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago_restante', pagamento = '$pagamento_restante', 
	func_comanda = '$usuario_logado', comanda = '$id'");
}




$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Comanda', valor = '$valor_serv', data_lancamento = curDate(), 
data_vencimento = '$data_pagamento', data_pagamento = '$data_pagamento2', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa', 
foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago', pagamento = '$pagamento', func_comanda = '$usuario_logado', comanda = '$id'");


echo 'Salvo com Sucesso';
