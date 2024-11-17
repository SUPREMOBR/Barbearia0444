<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'comentarios'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Recebe o ID do comentário a ser excluído via POST.

// Consulta o banco de dados para obter o comentário com o ID especificado.
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto']; // Obtém o nome do arquivo de foto associado ao comentário.

// Verifica se a foto não é a padrão "sem-foto.jpg" (indica que uma foto foi enviada).
if ($foto != "sem-foto.jpg") {
	@unlink('../../img/comentarios/' . $foto); // Exclui o arquivo de foto do diretório, se não for a padrão.
}
// Executa a exclusão do registro no banco de dados usando o ID fornecido.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
