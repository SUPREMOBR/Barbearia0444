<?php
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.
$funcionario = $_POST['func']; // Obtém o ID do funcionário

// Consulta no banco de dados para buscar informações do funcionário com base no ID fornecido
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se o resultado da consulta retornou algum registro
if (@count($resultado) > 0) {
	$nome = $resultado[0]['nome']; // Obtém o nome do funcionário encontrado no banco de dados
}

echo $nome; // Retorna o nome do funcionário como resposta
