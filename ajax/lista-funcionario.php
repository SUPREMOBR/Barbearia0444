<?php 
require_once("../sistema/conexao.php");

$funcionario = $_POST['funcionario']; 

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0){
	$nome = $resultado[0]['nome'];
		
}

echo $nome;

?>

