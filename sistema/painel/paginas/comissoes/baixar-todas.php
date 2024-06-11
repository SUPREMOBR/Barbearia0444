<?php 
require_once("../../../conexao.php");
$tabela = 'pagar';
@session_start();
$id_usuario = $_SESSION['id'];

$dataInicial = @$_POST['data_inicial'];
$dataFinal = @$_POST['data_final'];
$funcionario = @$_POST['id_funcionario'];

$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where data_lancamento >= '$dataInicial' and data_lancamento <= '$dataFinal' and pago = 'Não' and funcionario LIKE '$funcionario' and tipo = 'Comissão'");

echo 'Baixado com Sucesso';
 ?>