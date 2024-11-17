<?php
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.
$tabela = 'clientes'; // Define o nome da tabela no banco de dado

// Obtém os valores enviados via formulário
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];

// Verifica se o telefone já está cadastrado no banco de dados.
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Telefone já Cadastrado, você já está cadastrado!!';
	exit();
}
// Insere os dados na tabela.
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), alertado = 'Não'");

$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->execute();

echo 'Salvo com Sucesso';
