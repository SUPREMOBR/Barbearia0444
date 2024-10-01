<?php 

// cadastro feito no site

require_once("../sistema/conexao.php");
$tabela = 'clientes';

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];

//validar telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0){
	echo 'Telefone já Cadastrado, você já está cadastrado!!';
	exit();
}

$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), alertado = 'Não'");

$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->execute();

echo 'Salvo com Sucesso';

 ?>