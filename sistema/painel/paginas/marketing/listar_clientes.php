<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.

$dataMes = Date('m'); // Obtém o mês atual
$dataDia = Date('d'); // Obtém o dia atual
$dataAno = Date('Y'); // Obtém o ano atual
$data_atual = date('Y-m-d'); // Data atual formatada como ano-mês-dia

// Calcula a data de uma semana atrás a partir da data atual
$data_semana = date('Y/m/d', strtotime("-7 days", strtotime($data_atual)));

@session_start(); // Inicia a sessão
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário da sessão atual

$clientes = $_POST['cli'];

$clientes = $_POST['cli']; // Obtém a seleção de clientes a partir do formulário via POST

// Busca os contatos de clientes com base na seleção especificada
if ($clientes == "Teste") {
	// Se for para teste, seleciona o telefone dos administradores que possuem telefone registrado
	$resultad = $pdo->query("SELECT telefone FROM usuarios01 where nivel = 'Administrador' and telefone != ''");
} else if ($clientes == "Aniversáriantes Mês") {
	// Seleciona telefones dos clientes que fazem aniversário no mês atual
	$resultad  = $pdo->query("SELECT telefone FROM clientes where month(data_nascimento) = '$dataMes'  and telefone != ''");
} else if ($clientes == "Aniversáriantes Dia") {
	// Seleciona telefones dos clientes que fazem aniversário no dia e mês atuais
	$resultad  = $pdo->query("SELECT telefone FROM clientes where month(data_nascimento) = '$dataMes' and day(data_nascimento) = '$dataDia' and telefone != ''");
} else if ($clientes == "Clientes Mês") {
	// Seleciona telefones dos clientes cadastrados no mês e ano atuais
	$resultad  = $pdo->query("SELECT telefone FROM clientes where month(data_cadastro) = '$dataMes' and year(data_cadastro) = '$dataAno' and telefone != ''");
} else if ($clientes == "Clientes Semana") {
	// Seleciona telefones dos clientes cadastrados na última semana
	$resultad  = $pdo->query("SELECT telefone FROM clientes where data_cadastro >= '$data_semana' and telefone != ''");
} else {
	// Caso não seja especificado, seleciona telefones de todos os clientes
	$resultad  = $pdo->query("SELECT telefone FROM clientes where telefone != ''");
}

// Armazena os resultados em um array e conta o número total de registros obtidos
$resultado = $resultad->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

echo $total_registro;
