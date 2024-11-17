<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'grupo_acessos'; // Define o nome da tabela no banco de dado

// Obtém os valores enviados pelo formulário via POST
$id = $_POST['id'];
$nome = $_POST['nome'];

// Verifica se o nome do grupo já existe
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Caso exista um grupo com o mesmo nome e o ID seja diferente, retorna uma mensagem de erro
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Nome já Cadastrado, escolha outro!!';
	exit();
}

// Se o ID estiver vazio, é uma nova inserção; caso contrário, é uma atualização
if ($id == "") {
	// Inserção
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome");
} else {
	// Atualização
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome WHERE id = '$id'");
}
// Associa o valor do nome ao parâmetro e executa a consulta
$query->bindValue(":nome", "$nome");
$query->execute();

echo 'Salvo com Sucesso';
