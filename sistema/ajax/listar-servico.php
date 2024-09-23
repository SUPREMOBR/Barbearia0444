<?php 
require_once("../sistema/conexao.php");

$servico = $_POST['serv'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0){
	$nome = $resultado[0]['nome'];
		
}

echo $nome;

?>

