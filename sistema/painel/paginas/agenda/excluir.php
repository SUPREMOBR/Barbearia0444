<?php
$tabela = 'agendamentos'; // Define o nome da tabela no banco de dado
require_once("../../../conexao.php"); // Conecta ao banco de dados.

// Recebe o ID do agendamento enviado via POST
$id = $_POST['id'];

// Consulta o banco de dados para obter os detalhes do agendamento pelo ID
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Armazena os dados do agendamento
$cliente = $resultado[0]['cliente'];
$usuario = $resultado[0]['funcionario'] . '';
$data = $resultado[0]['data'];
$hora = $resultado[0]['hora'];
$servico = $resultado[0]['servico'];
$hash = $resultado[0]['hash'];

// Formata a data e a hora para exibição
$dataF = implode('/', array_reverse(explode('-', $data)));
$horaF = date("H:i", strtotime($hora));

// Consulta o banco para obter os detalhes do cliente
$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $resultado[0]['nome'];
$telefone = $resultado[0]['telefone'];

// Exclui o agendamento da tabela principal e da tabela de horários associados
$pdo->query("DELETE FROM $tabela where id = '$id'");
$pdo->query("DELETE FROM horarios_agd where agendamento = '$id'");

echo 'Excluído com Sucesso';

// Verifica se existe um hash associado ao agendamento e chama um script adicional se necessário
if ($hash != "") {
	require('../../../../ajax/agendar-delete.php');
}

// Caso a variável $msg_agendamento seja "Api", envia uma mensagem de notificação do cancelamento do agendamento
if ($msg_agendamento == 'Api') {

	// Consulta o banco para obter detalhes do funcionário
	$query = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario' ");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_funcionario = $resultado[0]['nome'];
	$telefone_funcionario = $resultado[0]['telefone'];

	// Consulta o banco para obter detalhes do serviço
	$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$nome_serv = $resultado[0]['nome'];

	// Monta a mensagem de cancelamento do agendamento
	$mensagem = '_Agendamento Cancelado_ %0A';
	$mensagem .= 'Profissional: *' . $nome_funcionario . '* %0A';
	$mensagem .= 'Serviço: *' . $nome_serv . '* %0A';
	$mensagem .= 'Data: *' . $dataF . '* %0A';
	$mensagem .= 'Hora: *' . $horaF . '* %0A';
	$mensagem .= 'Cliente: *' . $nome_cliente . '* %0A';

	// Remove caracteres especiais do telefone para envio da mensagem
	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone);

	// Chama um script para enviar a mensagem ao cliente
	require('../../../../ajax/api-texto.php');

	// Altera o telefone para o do profissional e envia a mesma mensagem
	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone_funcionario);
	require('../../../../ajax/api-texto.php');
}
// Se a variável $not_sistema for "Sim", envia uma notificação interna do sistema
if ($not_sistema == 'Sim') {
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Agendamento Cancelado ' . $dataF . ' - ' . $horaF;
	$id_usuario1 = $usuario;
	require('../../../../api/notid.php'); // Chama um script para enviar a notificação interna
}
