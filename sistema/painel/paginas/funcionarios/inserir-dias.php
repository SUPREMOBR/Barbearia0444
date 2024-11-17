<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'dias'; // Define o nome da tabela no banco de dado

// Obtém os valores enviados pelo formulário via POST
$id = $_POST['id'];  // ID do funcionário
$id_dias = $_POST['id_d'];  // ID do registro que será atualizado (se existir)
$dias = $_POST['dias'];  // Dias da semana (ex: Segunda-feira, Terça-feira, etc.)
$inicio = $_POST['inicio'];  // Horário de início do expediente
$final = $_POST['final'];  // Horário de término do expediente
$inicio_almoco = $_POST['inicio_almoco'];  // Horário de início do almoço
$final_almoco = $_POST['final_almoco'];  // Horário de término do almoço

// Verifica se o ID do registro (id_dias) está vazio
if ($id_dias == '') {
	// Se o ID do registro não foi informado, faz uma inserção na tabela 'dias'
	$pdo->query("INSERT INTO $tabela SET dia = '$dias', inicio = '$inicio', final = '$final', 
        funcionario = '$id', inicio_almoco = '$inicio_almoco', final_almoco = '$final_almoco'");
} else {
	// Caso contrário, realiza uma atualização no registro existente
	$pdo->query("UPDATE $tabela SET dia = '$dias', inicio = '$inicio', final = '$final', 
        funcionario = '$id', inicio_almoco = '$inicio_almoco', final_almoco = '$final_almoco' 
        WHERE id = '$id_dias'");
}

echo 'Salvo com Sucesso';
