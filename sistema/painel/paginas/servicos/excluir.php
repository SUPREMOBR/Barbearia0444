<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'servicos'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; //Recebe o ID passado via POST (Formulário)

//  consulta para buscar os dados do serviço com o ID fornecido
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto']; // Obtém o nome da foto associada ao serviço

// Verifica se a foto não é o valor padrão 'sem-foto.jpg'
if ($foto != "sem-foto.jpg") {
	// Se não for, exclui a foto do servidor
	@unlink('../../img/servicos/' . $foto);
}
// Executa a consulta SQL para excluir o registro do serviço na tabela
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
