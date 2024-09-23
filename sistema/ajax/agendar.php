<?php 
require_once("../sistema/conexao.php");
@session_start();
$telefone = $_POST['telefone'];
$nome = $_POST['nome'];
$funcionario = $_POST['funcionario'];
$hora = @$_POST['hora'];
$servico = $_POST['servico'];
$obs = $_POST['obs'];
$data = $_POST['data'];
$data_agd = $_POST['data'];
$id = @$_POST['id'];

$hash = "";

@$_SESSION['telefone'] = $telefone;

if($hora == ""){
	echo 'Escolha um Horário para Agendar!';
	exit();
}

if($data < date('Y-m-d')){
	echo 'Escolha uma data igual ou maior que Hoje!';
	exit();
}

//validar horario
$query = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0 and $resultado[0]['id'] != $id){
	echo 'Este horário não está disponível!';
	exit();
}

//Cadastrar o cliente caso não tenha cadastro
$query = $pdo->query("SELECT * FROM clientes where telefone LIKE '$telefone' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) == 0){
	$query = $pdo->prepare("INSERT INTO clientes SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), alertado = 'Não'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":telefone", "$telefone");	
	$query->execute();
	$id_cliente = $pdo->lastInsertId();

}else{
	$id_cliente = $resultado[0]['id'];
}






$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

if($not_sistema == 'Sim'){
	$mensagem_not = $nome;
	$titulo_not = 'Novo Agendamento '.$dataFormatada.' - '.$horaFormatada;
	$id_usuario = $funcionario;
	require('../api/notid.php');
} 


if($msg_agendamento == 'Api'){

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_funcionario = $resultado[0]['nome'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_serv = $resultado[0]['nome'];

$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

$mensagem = '_Novo Agendamento_ %0A';
$mensagem .= 'Funcionário: *'.$nome_funcionario.'* %0A';
$mensagem .= 'Serviço: *'.$nome_serv.'* %0A';
$mensagem .= 'Data: *'.$dataFormatada.'* %0A';
$mensagem .= 'Hora: *'.$horaFormatada.'* %0A';
$mensagem .= 'Cliente: *'.$nome.'* %0A';
if($obs != ""){
	$mensagem .= 'Obs: *'.$obs.'* %0A';
}

$telefone = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

require('api-texto.php');


//agendar o alerta de confirmação
$hora_atual = date('H:i:s');
$data_atual = date('Y-m-d');
$hora_minutos = strtotime("-$minutos_aviso minutes", strtotime($hora));
$nova_hora = date('H:i:s', $hora_minutos);

if(strtotime($hora_atual) < strtotime($nova_hora) or strtotime($data_atual) != strtotime($data_agd)){
	$mensagem = '*Confirmação de Agendamento* %0A %0A';
	$mensagem .= 'Envie *Sim* para confirmar seu agendamento hoje às '.$horaF;
	$data_mensagem = $data_agd.' '.$nova_hora;
	if($minutos_aviso > 0){
		require('api-agendar.php');
	}
	
}


}


//marcar o agendamento
$query = $pdo->prepare("INSERT INTO agendamentos SET funcionario = '$funcionario', cliente = '$id_cliente', hora = '$hora', data = '$data_agd',
 usuario = '0', status = 'Agendado', obs = :obs, data_lancamento = curDate(), servico = '$servico', hash = '$hash'");

echo 'Agendado com Sucesso';
	

$query->bindValue(":obs", "$obs");
$query->execute();

?>