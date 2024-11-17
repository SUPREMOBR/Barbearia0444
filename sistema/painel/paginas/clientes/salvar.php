<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'clientes'; // Define o nome da tabela no banco de dado

// Recebe dados do cliente via POST
$id = $_POST['id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$data_nascimento = $_POST['data_nascimento'];
$endereco = $_POST['endereco'];
$cpf = $_POST['cpf'];

// Verifica se o telefone já existe no banco de dados, exceto para o cliente atual
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Telefone já Cadastrado, escolha outro!!';
	exit();
}

// Condicional para inserção ou atualização do registro do cliente
if ($id == "") {
	// Insere um novo cliente se o ID estiver vazio
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), data_nascimento = '$data_nascimento', 
	 endereco = :endereco, alertado = 'Não', cpf = :cpf");
} else {
	// Atualiza o cliente existente
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, telefone = :telefone, data_nascimento = '$data_nascimento', endereco = :endereco, 
	cpf = :cpf WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":cpf", "$cpf");
$query->execute();

echo 'Salvo com Sucesso';
