<?php 
require_once("../../../conexao.php");
$tabela = 'receber';

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto'];
$produto = $resultado[0]['produto'];
$quantidade = $resultado[0]['quantidade'];


if($foto != "sem-foto.jpg"){
	@unlink('../../img/contas/'.$foto);
}

$query = $pdo->query("SELECT * FROM produtos where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$estoque = $resultado[0]['estoque'];

$total_estoque = $estoque + $quantidade;
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");


$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';

 ?>