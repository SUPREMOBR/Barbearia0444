<?php
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.
@session_start(); // Inicia a sessão para acessar variáveis de sessão
$telefone = $_POST['telefone'];
$nome = $_POST['nome'];
$funcionario = $_POST['funcionario'];
$hora = @$_POST['hora'];
$servico = $_POST['servico'];
$obs = $_POST['obs'];
$data = @$_POST['data'];
$data_agd = @$_POST['data'];
$hora_do_agd = @$_POST['hora'];
$id = @$_POST['id'];

$hash = "";

$telefone_cliente = $_POST['telefone'];

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$tempo = $resultado[0]['tempo'];


$hora_minutos = @strtotime("+$tempo minutes", @strtotime($hora));
$hora_final_servico = date('H:i:s', $hora_minutos);

$nova_hora = $hora;

$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', @strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Profissional não trabalha neste Dia!';
	exit();
} else {
	$inicio = $resultado[0]['inicio'];
	$final = $resultado[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}

//verificar se possui essa data nos dias bloqueio geral
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '0' and data = '$data_agd'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Não estaremos funcionando nesta Data!';
	exit();
}

//verificar se possui essa data nos dias bloqueio func
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '$funcionario'  and data = '$data_agd'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Este Profissional não irá trabalhar nesta Data, selecione outra data ou escolhar outro Profissional!';
	exit();
}

while (@strtotime($nova_hora) < @strtotime($hora_final_servico)) {

	$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($nova_hora));
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

	if (@strtotime($nova_hora) > @strtotime($inicio_almoco) and @strtotime($nova_hora) < @strtotime($final_almoco)) {
		echo 'Este serviço demora cerca de ' . $tempo . ' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido ao horário de almoço!';
		exit();
	}
}

@$_SESSION['telefone'] = $telefone;

if ($hora == "") {
	echo 'Escolha um Horário para Agendar!';
	exit();
}

if ($data < date('Y-m-d')) {
	echo 'Escolha uma data igual ou maior que Hoje!';
	exit();
}

//validar horario
$query = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0 and $resultado[0]['id'] != $id) {
	echo 'Este horário não está disponível!';
	exit();
}

//Cadastrar o cliente caso não tenha cadastro
$query = $pdo->query("SELECT * FROM clientes where telefone LIKE '$telefone' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	$query = $pdo->prepare("INSERT INTO clientes SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), alertado = 'Não'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":telefone", "$telefone");
	$query->execute();
	$id_cliente = $pdo->lastInsertId();
} else {
	$id_cliente = $resultado[0]['id'];
}


//excluir agendamentos temporarios deste cliente
$pdo->query("DELETE FROM agendamentos_temp where cliente = '$id_cliente'");

//marcar o agendamento
$query = $pdo->prepare("INSERT INTO agendamentos_temp SET funcionario = '$funcionario', cliente = '$id_cliente', hora = '$hora', 
data = '$data_agd', usuario = '0', status = 'Agendado', obs = :obs, data_lancamento = curDate(), servico = '$servico', hash = '$hash'");




$query->bindValue(":obs", "$obs");
$query->execute();

$ult_id = $pdo->lastInsertId();
echo 'Pré Agendado*' . $ult_id;
