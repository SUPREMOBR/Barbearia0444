<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'grupo_acessos'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o ID do grupo que será excluído, passado pelo formulário via POST

// Verifica se existem acessos relacionados a este grupo
$query2 = $pdo->query("SELECT * FROM acessos where grupo = '$id'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);

// Se o grupo possui registros de acessos relacionados, impede a exclusão
if ($total_registro2 > 0) {
	echo 'Não é possível excluir o registro, pois existem acessos relacionados a ele primeiro exclua os acessos e depois exclua esse grupo!';
	exit();
}

// Se não há acessos relacionados, exclui o grupo da tabela 'grupo_acessos'
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
