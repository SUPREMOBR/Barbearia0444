<?php
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.

// Recebe o ID do agendamento via formulário
$id = @$_POST['id'];

// Busca os detalhes do agendamento com base no ID
$query = $pdo->query("SELECT * FROM agendamentos where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

// Extrai os dados do agendamento
$cliente = $resultado[0]['cliente'];
$usuario = $resultado[0]['funcionario'] . ''; // Certifica de que o valor é uma string
$data = $resultado[0]['data'];
$hora = $resultado[0]['hora'];
$servico = $resultado[0]['servico'];
$hash = $resultado[0]['hash'];

$dataF = implode('/', array_reverse(explode('-', $data)));
$horaF = date("H:i", strtotime($hora));

// Busca os dados do cliente com base no ID obtido anteriormente
$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $resultado[0]['nome'];
$telefone = $resultado[0]['telefone'];
// Exclui o agendamento da tabela 'agendamentos'
$pdo->query("DELETE FROM agendamentos where id = '$id'");
// Exclui o registro relacionado ao horário do agendamento
$pdo->query("DELETE FROM horarios_agd where agendamento = '$id'");

echo 'Cancelado com Sucesso';

// Notifica o sistema, caso esteja ativado
if ($not_sistema == 'Sim') {
	// Define os parâmetros da notificação
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Agendamento Cancelado ' . $dataF . ' - ' . $horaF;
	$id_usuario1 = $usuario;
	require('../api/notid.php'); // Envia a notificação
}

// Verifica se a configuração de mensagens está ativada para envio via API
if ($msg_agendamento == 'Api') {
	// Busca os dados do funcionário responsável pelo agendamento
	$query = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario' ");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_funcionario = $resultado[0]['nome'];
	$telefone_funcionario = $resultado[0]['telefone'];

	// Busca os dados do serviço associado ao agendamento
	$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_serv = $resultado[0]['nome'];

	// Prepara a mensagem de cancelamento para o cliente e o profissional
	$mensagem = '_Agendamento Cancelado_ %0A';
	$mensagem .= 'Profissional: *' . $nome_func . '* %0A';
	$mensagem .= 'Serviço: *' . $nome_serv . '* %0A';
	$mensagem .= 'Data: *' . $dataF . '* %0A';
	$mensagem .= 'Hora: *' . $horaF . '* %0A';
	$mensagem .= 'Cliente: *' . $nome_cliente . '* %0A';

	// Remove caracteres indesejados do telefone do cliente e adiciona o código do país
	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone);

	require('api-texto.php'); // Envia a mensagem via API 

	// Envia a mensagem para o profissional, caso o número de telefone seja diferente do padrão do sistema
	if ($telefone_funcionario != $whatsapp_sistema) {
		$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone_funcionario);
		require('api-texto.php');
	}
	// Remove o agendamento vinculado ao hash
	if ($hash != "") {
		require('agendar-delete.php');
	}
}
