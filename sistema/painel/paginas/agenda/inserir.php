<?php 
$tabela = 'agendamentos';
require_once("../../../conexao.php");

@session_start();
$usuario_logado = @$_SESSION['id'];

$cliente = $_POST['cliente'];
$data = $_POST['data'];
$hora = @$_POST['hora'];
$obs = $_POST['obs'];
$id = $_POST['id'];
$funcionario = @$_SESSION['id'];
$servico = $_POST['servico'];

if(@$hora == ""){
	echo 'Selecione um Funcionário antes de agendar';
	exit();
}

$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) == 0){
		echo 'Este Funcionário não trabalha neste Dia';
	exit();
}


if(@$hora == ""){
	echo 'Selecione um Funcionário antes de agendar';
	exit();
}


$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

//validar cpf
$query = $pdo->query("SELECT * FROM $tabela where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0 and $resultado[0]['id'] != $id){
	echo 'Este horário não está disponível';
	exit();
}


$query = $pdo->prepare("INSERT INTO $tabela SET funcionario = '$funcionario', cliente = '$cliente', hora = '$hora', data = '$data', usuario = '$usuario_logado', status = 'Agendado', obs = :obs, data_lancamento = curDate(), servico = '$servico'");

$query->bindValue(":obs", "$obs");
$query->execute();


echo 'Salvo com Sucesso'; 

?>