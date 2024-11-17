<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'categoria_servicos'; // Define o nome da tabela no banco de dado

// Recebe os dados enviados pelo formulário via POST
$id = $_POST['id']; // ID do registro
$nome = $_POST['nome']; // Nome do cargo a ser inserido ou atualizado

// Verifica se o nome já está cadastrado no banco de dados
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Nome já Cadastrado, escolha outro!!';
	exit(); // Encerra a execução do script aqui se o nome já estiver cadastrado
}

// Verifica se é uma nova inserção ou atualização de um registro existente
if ($id == "") {
	// Caso o ID seja vazio, é uma inserção de um novo registro.
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome");
} else {
	// Caso contrário, é uma atualização de um registro existente.
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome"); // Liga o valor da variável $nome ao parâmetro de consulta :nome
$query->execute(); // Executa a consulta (inserção ou atualização)

echo 'Salvo com Sucesso';
