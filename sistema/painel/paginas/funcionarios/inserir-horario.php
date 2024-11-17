<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'horarios'; // Define o nome da tabela no banco de dado

// Obtém os valores enviados pelo formulário via POST
$id = $_POST['id'];  // ID do funcionário
$horario = $_POST['horario'];  // Horário específico (ex: 09:00 AM)
$data = @$_POST['data'];  // Data (opcional) associada ao horário. O uso de "@" previne erros caso a variável não seja definida

// Verifica se a data foi informada. Se não, insere apenas o horário e o ID do funcionário
if ($data == "") {
	// Se a data não foi fornecida, insere o horário e o ID do funcionário na tabela
	$pdo->query("INSERT INTO $tabela SET horario = '$horario', funcionario = '$id'");
} else {
	// Caso contrário, insere também a data junto com o horário e o ID do funcionário
	$pdo->query("INSERT INTO $tabela SET horario = '$horario', funcionario = '$id', data = '$data'");
}

echo 'Salvo com Sucesso';
