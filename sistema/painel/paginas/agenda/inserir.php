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
$funcionario = @$_SESSION['id'];
$servico = $_POST['servico'];
$data_agd = $_POST['data'];
$hash = '';

if (@$hora == "") {
	echo 'Selecione um Hora antes de agendar!';
	exit();
}


$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$tempo = $resultado[0]['tempo'];


$hora_minutos = strtotime("+$tempo minutes", strtotime($hora));
$hora_final_servico = date('H:i:s', $hora_minutos);

$nova_hora = $hora;

$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Funcionário não trabalha neste Dia!';
	exit();
} else {
	$inicio = $res[0]['inicio'];
	$final = $res[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}


$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));




while (strtotime($nova_hora) < strtotime($hora_final_servico)) {

	$hora_minutos = strtotime("+$intervalo minutes", strtotime($nova_hora));
	$nova_hora = date('H:i:s', $hora_minutos);

	//VERIFICAR NA TABELA HORARIOS AGD SE TEM O HORARIO NESSA DATA
	$query_agd = $pdo->query("SELECT * FROM horarios_agd where data = '$data' and funcionario = '$funcionario' and horario = '$nova_hora'");
	$resultado_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
	if (@count($resultado_agd) > 0) {
		echo 'Este serviço demora cerca de ' . $tempo . ' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
		exit();
	}



	//VERIFICAR NA TABELA AGENDAMENTOS SE TEM O HORARIO NESSA DATA e se tem um intervalo entre o horario marcado e o proximo agendado nessa tabela
	$query_agd = $pdo->query("SELECT * FROM agendamentos where data = '$data' and funcionario = '$funcionario' and hora = '$nova_hora'");
	$resultado_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
	if (@count($resultado_agd) > 0) {
		if ($tempo <= $intervalo) {
		} else {
			if ($hora_final_servico == $resultado_agd[0]['hora']) {
			} else {
				echo 'Este serviço demora cerca de ' . $tempo . ' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
				exit();
			}
		}
	}


	if (strtotime($nova_hora) > strtotime($inicio_almoco) and strtotime($nova_hora) < strtotime($final_almoco)) {
		echo 'Este serviço demora cerca de ' . $tempo . ' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido ao horário de almoço!';
		exit();
	}
}


//validar horario
$query = $pdo->query("SELECT * FROM $tabela where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0 and $resultado[0]['id'] != $id) {
	echo 'Este horário não está disponível!';
	exit();
}





echo 'Salvo com Sucesso';


//pegar nome do cliente
$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $resultado[0]['nome'];
$telefone = $resultado[0]['telefone'];

if ($not_sistema == 'Sim') {
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Novo Agendamento ' . $dataFormatada . ' - ' . $horaFormatada;
	$id_usuario = $usuario_logado;
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


$ult_id = $pdo->lastInsertId();

while (strtotime($hora) < strtotime($hora_final_servico)) {

	$hora_minutos = strtotime("+$intervalo minutes", strtotime($hora));
	$hora = date('H:i:s', $hora_minutos);

	if (strtotime($hora) < strtotime($hora_final_servico)) {
		$query = $pdo->query("INSERT INTO horarios_agd SET agendamento = '$ult_id', horario = '$hora', funcionario = '$funcionario', data = '$data_agd'");
	}
}
