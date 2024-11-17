<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'categoria_produtos';  // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o ID do registro a ser excluído.

// Verifica se existem produtos associados à categoria a ser excluída.
$query2 = $pdo->query("SELECT * FROM produtos where categoria = '$id'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);
// Se existir algum produto relacionado à categoria, exibe uma mensagem de erro.
if ($total_registro2 > 0) {
	echo 'Não é possível excluir o registro, pois existem produtos relacionados a ela primeiro exclua os produtos e depois exclua essa categoria!';
	exit();
}
// Se não houver produtos associados, executa a exclusão da categoria.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
