<?php 
require_once("../../../conexao.php");
$tabela = 'servicos_funcionarios';

$id = $_POST['id'];
$servico = $_POST['servico'];
$funcionario = $_POST['id']; //func

$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$funcionario' and servico = '$servico'"); //  var func
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
	echo 'Serviço já adicionado ao Funcionário!';
	exit();
}

$pdo->query("INSERT INTO $tabela SET servico = '$servico', funcionario = '$funcionario'");  //func

echo 'Salvo com Sucesso';
 ?>