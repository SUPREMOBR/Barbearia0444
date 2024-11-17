<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'fornecedores'; // Define o nome da tabela no banco de dado

// Recebe os dados enviados via POST pelo formulário
$id = $_POST['id']; // Recebe o ID do fornecedor
$nome = $_POST['nome']; // Recebe o nome do fornecedor
$telefone = $_POST['telefone']; // Recebe o telefone do fornecedor
$endereco = $_POST['endereco']; // Recebe o endereço do fornecedor
$tipo_chave = $_POST['tipo_chave']; // Recebe o tipo da chave do fornecedor (ex: CPF, CNPJ)
$chave_pix = $_POST['chave_pix']; // Recebe a chave PIX do fornecedor


// Validar se o telefone já existe
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Telefone já Cadastrado, escolha outro!!';
	exit();
}


if ($id == "") {
	// Se o ID estiver vazio, será um novo registro
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), endereco = :endereco, 
	tipo_chave = '$tipo_chave', chave_pix = :chave_pix");
} else {
	// Caso contrário, atualizará o registro existente
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, telefone = :telefone,  endereco = :endereco, tipo_chave = '$tipo_chave', 
	chave_pix = :chave_pix WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":chave_pix", "$chave_pix");
$query->execute();

echo 'Salvo com Sucesso';
