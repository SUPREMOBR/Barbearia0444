<?php 
require_once("../../../conexao.php");
$tabela = 'clientes';

$id = $_POST['id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$data_nascimento = $_POST['data_nascimento'];
$endereco = $_POST['endereco'];

//validar telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0 and $id != $resultado[0]['id']){
	echo 'Telefone já Cadastrado, escolha outro';
	exit();
}


if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), data_nascimento = '$data_nascimento', endereco = :endereco");
}else{
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, telefone = :telefone, data_nascimento = '$data_nascimento', endereco = :endereco WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->execute();

echo 'Salvo com Sucesso';
 ?>