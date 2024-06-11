<?php 
require_once("../../../conexao.php");
$tabela = 'receber';
@session_start();
$id_usuario = $_SESSION['id'];


$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$funcionario = $resultado[0]['funcionario'];
$servico = $resultado[0]['servico'];
$cliente = $resultado[0]['pessoa'];
$descricao = 'Comissão - '.$resultado[0]['descricao'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor'];
$comissao = $resultado[0]['comissao'];

if($tipo_comissao == 'Porcentagem'){
	$valor_comissao = ($comissao * $valor) / 100;
}else{
	$valor_comissao = $comissao;
}


$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where id = '$id'");


//lançar a conta a pagar para a comissão do funcionário
$pdo->query("INSERT INTO pagar SET descricao = '$descricao', tipo = 'Comissão', valor = '$valor_comissao', data_lanc = curDate(), data_vencamento = curDate(), usuario_lancou = '$id_usuario', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', servico = '$servico', cliente = '$cliente'");

echo 'Baixado com Sucesso';
 ?>