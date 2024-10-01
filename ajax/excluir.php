<?php 
require_once("../sistema/conexao.php");

$id = @$_POST['id'];

$query = $pdo->query("SELECT * FROM agendamentos where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$cliente = $resultado[0]['cliente'];
$usuario = $resultado[0]['funcionario'].'';
$data = $resultado[0]['data'];
$hora = $resultado[0]['hora'];
$servico = $resultado[0]['servico'];
$hash = $resultado[0]['hash'];

$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $resultado[0]['nome'];
$telefone = $resultado[0]['telefone'];

$pdo->query("DELETE FROM agendamentos where id = '$id'");
$pdo->query("DELETE FROM horarios_agd where agendamento = '$id'");

echo 'Cancelado com Sucesso';

if($not_sistema == 'Sim'){
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Agendamento Cancelado '.$dataFormatada.' - '.$horaFormatada;
	$id_usuario = $usuario;
	require('../api/notid.php');
} 



if($msg_agendamento == 'Api'){

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_funcionario = $resultado[0]['nome'];
$telefone_funcionario = $resultado[0]['telefone']; //tel_func

$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_serv = $resultado[0]['nome'];


$mensagem = '_Agendamento Cancelado_ %0A';
$mensagem .= 'Funcionário: *'.$nome_funcionario.'* %0A';
$mensagem .= 'Serviço: *'.$nome_serv.'* %0A';
$mensagem .= 'Data: *'.$dataFormatada.'* %0A';
$mensagem .= 'Hora: *'.$horaFormatada.'* %0A';
$mensagem .= 'Cliente: *'.$nome_cliente.'* %0A';

$telefone = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

require('api-texto.php');

if($telefone_funcionario != $whatsapp_sistema){  //tel_func
	$telefone = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_funcionario);  //tel_func
	require('api-texto.php');	
}

if($hash != ""){
	require('api-excluir.php');
}
}


?>