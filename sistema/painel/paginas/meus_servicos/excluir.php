<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Consulta para obter as informações do registro a ser excluído na tabela 'receber'
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto']; // Obtém o nome do arquivo de foto associado ao registro
$produto = $resultado[0]['produto'];  // Obtém o ID do produto associado
$quantidade = $resultado[0]['quantidade']; // Obtém a quantidade associada ao registro

// Exclui o arquivo de foto do servidor, se não for o arquivo padrão
if ($foto != "sem-foto.jpg") {
	@unlink('../../img/contas/' . $foto);
}

// Consulta para obter o estoque atual do produto correspondente
$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$estoque = @$resultado[0]['estoque']; // Obtém o valor atual do estoque do produto

// Calcula o novo estoque somando a quantidade do registro a ser excluído
$total_estoque = $estoque + $quantidade;

// Atualiza o estoque do produto
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");

// Exclui o registro da tabela 'receber' com o ID especificado
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
