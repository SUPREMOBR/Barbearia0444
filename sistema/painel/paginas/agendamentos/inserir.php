<?php
$tabela = 'agendamentos'; // Define o nome da tabela no banco de dado
require_once("../../../conexao.php"); // Conecta ao banco de dados.

@session_start();  // Inicia a sessão para acessar variáveis de sessão
$usuario_logado = @$_SESSION['id'] . '';  // Obtém o ID do usuário logado da sessão

// Recebe os dados do formulário enviados por POST
$cliente = $_POST['cliente']; // ID do cliente
$data = $_POST['data']; // Data do agendamento
$hora = @$_POST['hora']; // Hora do agendamento
$obs = $_POST['obs']; // Observações adicionais
$id = $_POST['id']; // ID do agendamento (caso seja uma edição)
$funcionario = @$_SESSION['id']; // ID do funcionário (usuário logado)
$servico = $_POST['servico']; // ID do serviço a ser agendado
$data_agd = $_POST['data']; // Data do agendamento para verificação de disponibilidade
$hora_do_agd = @$_POST['hora']; // Hora do agendamento para verificação de disponibilidade
$hash = ''; // Variável para armazenar um hash de identificação

// Valida se a hora foi selecionada
if (@$hora == "") {
	echo 'Selecione um Hora antes de agendar!';
	exit();
}

// Consulta o intervalo de tempo entre atendimentos do funcionário
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo']; // Intervalo entre atendimentos
$nome_funcionario = @$resultado[0]['nome']; // Nome do funcionário

// Consulta a duração do serviço a ser agendado
$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$tempo = $resultado[0]['tempo']; // Tempo estimado para o serviço
$nome_serv = @$resultado[0]['nome']; // Nome do serviço

// Calcula o horário final estimado para o serviço
$hora_minutos = strtotime("+$tempo minutes", strtotime($hora));
$hora_final_servico = date('H:i:s', $hora_minutos);

$nova_hora = $hora; // Inicializa a variável de horário para o loop de validação

// Verifica o dia da semana do agendamento
$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data)); // Obtém o número do dia da semana
$dia_procurado = $diasemana[$diasemana_numero]; // Converte para nome do dia da semana

// Verifica se o funcionário trabalha nesse dia da semana
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Funcionário não trabalha neste Dia!';
	exit();
} else {
	// Define horários de trabalho e intervalo de almoço do funcionário
	$inicio = $resultado[0]['inicio'];
	$final = $resultado[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}

// Formatação de data e hora para exibição em notificações
$dataF = implode('/', array_reverse(explode('-', $data)));
$horaF = date("H:i", strtotime($hora));

// Loop para verificar disponibilidade do funcionário durante o período do serviço
while (strtotime($nova_hora) < strtotime($hora_final_servico)) {

	$hora_minutos = strtotime("+$intervalo minutes", strtotime($nova_hora));
	$nova_hora = date('H:i:s', $hora_minutos);

	// Verifica se há outro agendamento no mesmo horário para o funcionário
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

	// Verifica se o horário do serviço coincide com o horário de almoço do funcionário
	if (strtotime($nova_hora) > strtotime($inicio_almoco) and strtotime($nova_hora) < strtotime($final_almoco)) {
		echo 'Este serviço demora cerca de ' . $tempo . ' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido ao horário de almoço!';
		exit();
	}
}

// Valida se o horário está disponível para o agendamento atual
$query = $pdo->query("SELECT * FROM $tabela where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0 and $resultado[0]['id'] != $id) {
	echo 'Este horário não está disponível!';
	exit();
}

// Consulta o nome e telefone do cliente para notificações
$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $resultado[0]['nome'];
$telefone = $resultado[0]['telefone'];

// Notificação interna no sistema
if ($not_sistema == 'Sim') {
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Novo Agendamento ' . $dataF . ' - ' . $horaF;
	$id_usuario1 = $funcionario;
	require('../../../../api/notid.php'); // Chama arquivo para enviar notificação
}

// Notificação via API de mensagem
if ($msg_agendamento == 'Api') {

	//agendar o alerta de confirmação
	$hora_atual = date('H:i:s');
	$data_atual = date('Y-m-d');
	$hora_minutos = strtotime("-$minutos_aviso minutes", strtotime($hora));
	$nova_hora = date('H:i:s', $hora_minutos);

	// Formatação do número de telefone
	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone);
}

// Insere o novo agendamento no banco de dados
$query = $pdo->prepare("INSERT INTO $tabela SET funcionario = '$funcionario', cliente = '$cliente', hora = '$hora', data = '$data_agd', 
usuario = '$usuario_logado', status = 'Agendado', obs = :obs, data_lancamento = curDate(), servico = '$servico', hash = '$hash'");

$query->bindValue(":obs", "$obs");
$query->execute();

// Obtém o ID do último agendamento inserido
$ult_id = $pdo->lastInsertId();

// Envio de confirmação de agendamento via API
if ($msg_agendamento == 'Api') {
	if (strtotime($hora_atual) < strtotime($nova_hora) or strtotime($data_atual) != strtotime($data_agd)) {

		$mensagem = '*Confirmação de Agendamento* ';
		$mensagem .= '                              Profissional: *' . $nome_funcionario . '*';
		$mensagem .= '                                         Serviço: *' . $nome_serv . '*';
		$mensagem .= '                                               	       Data: *' . $dataF . '*';
		$mensagem .= '                                               	       Hora: *' . $horaF . '*';
		$mensagem .= '                                                             ';
		$mensagem .= '                                 _(Digite o número com a opção desejada)_';
		$mensagem .= '                                 1.  Digite 1️⃣ para confirmar ✅';
		$mensagem .= '                                 2.  Digite 2️⃣ para Cancelar ❌';

		$id_envio = $ult_id;
		$data_envio = $data_agd . ' ' . $hora_do_agd;

		if ($minutos_aviso > 0) {
			require("../../../../ajax/confirmacao.php");
			$id_hash = $id;
			$pdo->query("UPDATE agendamentos SET hash = '$id_hash' WHERE id = '$ult_id'");
		}
	}
}
// Inserir o intervalo de atendimento para cada horário durante o período do serviço
while (strtotime($hora) < strtotime($hora_final_servico)) {

	$hora_minutos = strtotime("+$intervalo minutes", strtotime($hora));
	$hora = date('H:i:s', $hora_minutos);

	if (strtotime($hora) < strtotime($hora_final_servico)) {
		$query = $pdo->query("INSERT INTO horarios_agd SET agendamento = '$ult_id', horario = '$hora', funcionario = '$funcionario', data = '$data_agd'");
	}
}


echo 'Salvo com Sucesso';
