<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'produtos'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; // Obtém o valor do ID do produto a ser excluído, que foi passado pelo formulário

// Faz uma consulta no banco de dados para buscar o produto pelo ID
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto'];  // Obtém o nome da foto associada ao produto

// Verifica se a foto não é a foto padrão (
if ($foto != "sem-foto.jpg") {
	@unlink('../../img/produtos/' . $foto);
}
// Exclui o registro do produto na tabela 'produtos'
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
