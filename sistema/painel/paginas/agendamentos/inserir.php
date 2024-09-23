<?php
$tabela = 'agendamentos';
require_once("../../../conexao.php");

@session_start();
$usuario_logado = @$_SESSION['id'] . '';

$cliente = $_POST['cliente'];
$data = $_POST['data'];
$hora = @$_POST['hora'];
$obs = $_POST['obs'];
$id = $_POST['id'];
$funcionario = $_POST['funcionario'];
$servico = $_POST['servico'];
$data_agd = $_POST['data'];
$hash = '';

if (@$hora == "") {
	echo 'Selecione um Horário antes de agendar!';
	exit();
}

$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Funcionário não trabalha neste Dia!';
	exit();
}



$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

//validar horario
$query = $pdo->query("SELECT * FROM $tabela where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0 and $resultado[0]['id'] != $id) {
	echo 'Este horário não está disponível!';
	exit();
}





//pegar nome do cliente
$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $resultado[0]['nome'];
$telefone = $resultado[0]['telefone'];

if ($not_sistema == 'Sim') {
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Novo Agendamento ' . $dataFormatada . ' - ' . $horaFormatada;
	$id_usuario = $funcionario;
	require('../../../../api/notid.php');
}


if ($msg_agendamento == 'Api') {



	//agendar o alerta de confirmação
	$hora_atual = date('H:i:s');
	$data_atual = date('Y-m-d');
	$hora_minutos = strtotime("-$minutos_aviso minutes", strtotime($hora));
	$nova_hora = date('H:i:s', $hora_minutos);


	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone);

	if (strtotime($hora_atual) < strtotime($nova_hora) or strtotime($data_atual) != strtotime($data_agd)) {
		$mensagem = '*Confirmação de Agendamento* %0A %0A';
		$mensagem .= 'Envie *Sim* para confirmar seu agendamento hoje às ' . $horaFormatada;
		$data_mensagem = $data_agd . ' ' . $nova_hora;
		if ($minutos_aviso > 0) {
			require('../../../../ajax/api-agendar.php');
		}
	}
}


$query = $pdo->prepare("INSERT INTO $tabela SET funcionario = '$funcionario', cliente = '$cliente', hora = '$hora', data = '$data_agd',
 usuario = '$usuario_logado', status = 'Agendado', obs = :obs, data_lancamento = curDate(), servico = '$servico', hash = '$hash'");

$query->bindValue(":obs", "$obs");
$query->execute();


echo 'Salvo com Sucesso';
