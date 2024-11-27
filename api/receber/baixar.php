<?php
require_once("../../sistema/conexao.php");
$tabela = 'receber';

$id_usuario = $_POST['id_usuario'];
$id = $_POST['id'];



$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$funcionario = $resultado[0]['funcionario'];
$servico = $resultado[0]['servico'];
$cliente = $resultado[0]['pessoa'];
$descricao = 'Comissão - ' . $resultado[0]['descricao'];
$tipo = $resultado[0]['tipo'];

if ($tipo == 'Serviço') {
	$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$valor = $resultado[0]['valor'];
	$comissao = $resultado[0]['comissao'];

	if ($tipo_comissao == 'Porcentagem') {
		$valor_comissao = ($comissao * $valor) / 100;
	} else {
		$valor_comissao = $comissao;
	}

	//lançar a conta a pagar para a comissão do funcionário
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = curDate(), data_vencimento = curDate(), usuario_lancou = '$id_usuario', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', servico = '$servico', cliente = '$cliente'");
}



$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where id = '$id'");

echo 'Baixado com Sucesso';
