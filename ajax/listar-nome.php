<?php 
require_once("../sistema/conexao.php");
$data = date('Y-m-d');
$telefone = @$_POST['telefone'];

if($telefone == ""){
	exit();
}

$query = $pdo->query("SELECT * FROM clientes where telefone LIKE '$telefone' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0){
	$nome = $resultado[0]['nome'];
	$id_cliente = $resultado[0]['id'];
	
}

//buscar agendamento
if(@$id_cliente != ""){
	$query = $pdo->query("SELECT * FROM agendamentos where cliente = '$id_cliente' and status = 'Agendado' order by id desc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($resultado) > 0){
		$data = $resultado[0]['data'];
		$funcionario = $resultado[0]['funcionario'];
		$id = $resultado[0]['id'];
		$hora = $resultado[0]['hora'];
		$servico = $resultado[0]['servico'];
		$obs = $resultado[0]['obs'];


		$query = $pdo->query("SELECT * FROM usuarios01 where id LIKE '$funcionario' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0){
	$nome_funcionario = $resultado[0]['nome'];
	}


		$query = $pdo->query("SELECT * FROM servicos where id LIKE '$servico' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0){
	$nome_serv = $resultado[0]['nome'];
	}



		$horaFormatada = date("H:i", strtotime($hora));

		$dataFormatada = implode('/', array_reverse(explode('-', $data)));

	}

	echo @$nome.'*'.@$data.'*'.@$funcionario.'*'.@$id.'*'.@$horaFormatada.'*'.@$servico.'*'.@$obs.'*'.@$dataFormatada.'*'.@$nome_funcionario.'*'.@$nome_serv;
}else{
	echo '*'.@$data;
}




?>

