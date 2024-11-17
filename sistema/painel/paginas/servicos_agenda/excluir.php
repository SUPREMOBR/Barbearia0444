<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; //Recebe o ID passado via POST (Formulário)

// Faz uma consulta ao banco de dados para buscar todos os dados do registro que corresponde ao ID fornecido.
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC); // Armazena o resultado da consulta em um array associativo.
$total_registro = @count($resultado); // Conta quantos registros foram encontrados, mas ignora erros com o operador @.
$foto = $resultado[0]['foto']; // Armazena o nome da foto associada ao registro.
$produto = $resultado[0]['produto']; // Armazena o ID do produto associado ao registro.
$quantidade = $resultado[0]['quantidade']; // Armazena a quantidade associada ao registro.

if ($foto != "sem-foto.jpg") {
	@unlink('../../img/contas/' . $foto);
}

// Faz uma consulta para obter os dados do produto, utilizando o ID do produto.
$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC); // Armazena o resultado da consulta em um array associativo.
$total_registro = @count($resultado); // Conta quantos registros foram encontrados, mas ignora erros com o operador @.
$estoque = @$resultado[0]['estoque']; // Obtém o estoque atual do produto.

// Atualiza o estoque do produto, somando a quantidade do registro excluído.
$total_estoque = $estoque + $quantidade;

// Atualiza o estoque no banco de dados.
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");

// Exclui o registro da tabela 'receber'.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
