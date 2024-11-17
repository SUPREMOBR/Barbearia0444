<?php
@session_start(); // Inicia a sessão para acessar variáveis de sessão
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.
$data = date('Y-m-d'); // Obtém a data atual
$telefone = @$_POST['telefone']; //tel // Recebe o número de telefone enviado pelo formulário

// Verifica se o telefone está vazio
if ($telefone == "") { //tel
	exit();
}

@$_SESSION['telefone'] = $telefone; // Armazena o telefone na sessão

// Busca o cliente no banco de dados pelo número de telefone
$query = $pdo->query("SELECT * FROM clientes where telefone LIKE '$telefone' "); //tel
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	// Se um cliente for encontrado, obtém seu nome e ID
	$nome = $resultado[0]['nome'];
	$id_cliente = $resultado[0]['id'];
}

// Se o cliente foi encontrado, busca os agendamentos relacionados a ele
if (!empty($id_cliente)) {
	$query = $pdo->query("SELECT * FROM agendamentos where cliente = '$id_cliente' and status = 'Agendado' order by id desc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	if (@count($resultado) > 0) {
		// Obtém os detalhes do agendamento mais recente
		$data = $resultado[0]['data'];
		$funcionario = $resultado[0]['funcionario'];
		$id = $resultado[0]['id'];
		$hora = $resultado[0]['hora'];
		$servico = $resultado[0]['servico'];
		$obs = $resultado[0]['obs'];

		// Busca o nome do funcionário responsável pelo agendamento
		$query = $pdo->query("SELECT * FROM usuarios01 where id LIKE '$funcionario' ");
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado) > 0) {
			$nome_funcionario = $resultado[0]['nome'];
		}

		// Busca o nome do serviço agendado
		$query = $pdo->query("SELECT * FROM servicos where id LIKE '$servico' ");
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado) > 0) {
			$nome_serv = $resultado[0]['nome'];
		}

		// Formata a hora e a data
		$horaF = date("H:i", strtotime($hora));
		$dataF = implode('/', array_reverse(explode('-', $data)));
	}
	// Retorna as informações do cliente e do agendamento
	echo @$nome . '*' . @$data . '*' . @$funcionario . '*' . @$id . '*' . @$horaF . '*' . @$servico . '*' . @$obs . '*' . @$dataF . '*' . @$nome_funcionario . '*' . @$nome_serv;
} else {
	// Retorna apenas a data se o cliente não foi encontrado
	echo '*' . @$data;
}
