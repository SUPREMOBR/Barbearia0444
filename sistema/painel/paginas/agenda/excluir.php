<?php 
$tabela = 'agendamentos';
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$cliente = $resultado[0]['cliente'];
$usuario = $resultado[0]['funcionario'].'';
$data = $resultado[0]['data'];
$hora = $resultado[0]['hora'];
$hash = $resultado[0]['hash'];

$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $resultado[0]['nome'];

$pdo->query("DELETE FROM $tabela where id = '$id'");
$pdo->query("DELETE FROM horarios_agd where agendamento = '$id'");

echo 'Excluído com Sucesso';

if($hash != ""){
	require('../../../../ajax/api-excluir.php');
}

if($not_sistema == 'Sim'){
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Agendamento Cancelado '.$dataFormatada.' - '.$horaFormatada;
	$id_usuario = $usuario;
	require('../../../../api/notid.php');
}



?>