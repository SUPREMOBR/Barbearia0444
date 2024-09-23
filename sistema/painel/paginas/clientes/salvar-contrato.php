<?php 
$tabela = 'contratos';
require_once("../../../conexao.php");

$contrato = $_POST['contrato'];
$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where cliente = '$id' order by id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro == 0){
$query = $pdo->prepare("INSERT into $tabela SET cliente = '$id', texto = :texto, data = curDate()"); 	

}else{
	$query = $pdo->prepare("UPDATE $tabela SET texto = :texto WHERE cliente = '$id' ");
	
}

$query->bindValue(":texto", "$contrato");
$query->execute();

echo 'Salvo com Sucesso';
 ?>