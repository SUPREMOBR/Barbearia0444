<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'acessos'; // Define o nome da tabela no banco de dados

// Recebe os dados enviados pelo formulário via POST
$id = $_POST['id'];
$nome = $_POST['nome'];
$chave = $_POST['chave'];
$grupo = $_POST['grupo'];

// Valida se já existe um registro com o mesmo nome no mesmo grupo
$query = $pdo->query("SELECT * from $tabela where nome = '$nome' and grupo = '$grupo'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se o nome já existe e não é o registro que está sendo atualizado
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Nome já Cadastrado, escolha outro!!';
	exit();
}

// Valida se já existe uma chave igual no mesmo grupo
$query = $pdo->query("SELECT * from $tabela where chave = '$chave' and grupo = '$grupo'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se a chave já existe e não é o registro que está sendo atualizado
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Chave já Cadastrada, escolha outra!!';
	exit();
}
// Verifica se é uma inserção (ID vazio) ou uma atualização (ID preenchido)
if ($id == "") {
	// Prepara a consulta para inserir um novo registro na tabela
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, grupo = '$grupo', chave = :chave");
} else {
	// Prepara a consulta para atualizar um registro existente na tabela
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, grupo = '$grupo', chave = :chave WHERE id = '$id'");
}

// Define os valores para os parâmetros nome e chave usando bindValue
$query->bindValue(":nome", "$nome");
$query->bindValue(":chave", "$chave");
$query->execute(); // Executa a consulta preparada

echo 'Salvo com Sucesso'; // Retorna uma mensagem de sucesso
