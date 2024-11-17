<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'formas_pagamento'; // Define o nome da tabela no banco de dados

$id = $_POST['id'];       // Obtém o valor do ID (se presente) do formulário
$nome = $_POST['nome'];   // Obtém o valor do nome da forma de pagamento
$taxa = $_POST['taxa'];   // Obtém o valor da taxa associada à forma de pagamento

// Valida o nome para garantir que não haja duplicidade no banco de dados
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Nome já Cadastrado, escolha outro!!';
	exit(); // Se o nome já estiver cadastrado, exibe uma mensagem e interrompe a execução
}

// Se o ID for vazio, é um cadastro (inserção)
if ($id == "") {
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, taxa = '$taxa'");
} else {
	// Caso contrário, realiza uma atualização no banco de dados
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, taxa = '$taxa' WHERE id = '$id'");
}

// Prepara a execução da query substituindo o marcador :nome pelo valor do campo
$query->bindValue(":nome", "$nome");
// Executa a query
$query->execute();

echo 'Salvo com Sucesso';
