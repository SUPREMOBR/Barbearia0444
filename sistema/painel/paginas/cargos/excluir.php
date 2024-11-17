<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'cargos'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o ID do registro a ser excluído.

// Executa a consulta para excluir o registro com o ID especificado na tabela definida.
// A consulta usa o valor da variável $tabela para especificar a tabela e a variável $id para identificar o registro a ser removido.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
