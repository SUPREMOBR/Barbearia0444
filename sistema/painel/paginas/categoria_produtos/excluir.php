<?php 
require_once("../../../conexao.php");
$tabela = 'categoria_produtos';

$id = $_POST['id'];

$query2 = $pdo->query("SELECT * FROM produtos where categoria = '$id'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);
if($total_registro2 > 0){
	echo 'Não é possível excluir o registro, pois existem produtos relacionados a ela. Primeiro exclua os produtos e depois exclua essa categoria!';
	exit();
}

$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
 ?>