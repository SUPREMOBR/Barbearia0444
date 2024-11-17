<?php
require_once("../../../conexao.php");
$tabela = 'comandas';
$id = $_POST['id'];

$query2 = $pdo->query("SELECT * FROM receber where comanda = '$id'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);
if ($total_registro2 > 0) {
	echo 'Primeiro exclua produtos e serviços desta comanda para depois excluir a comanda!';
	exit();
}

$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
