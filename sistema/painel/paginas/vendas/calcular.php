<?php 
require_once("../../../conexao.php");

$quantidade = $_POST['quantidade'];
$produto = $_POST['produto'];

$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$valor = $resultado[0]['valor_venda'];

echo $valor * $quantidade;
 ?>