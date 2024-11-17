<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
// Define o nome da tabela no banco de dados
$tabela = 'acessos';
// Obtém o ID enviado via POST para a exclusão do registro
$id = $_POST['id'];
// Executa a consulta para excluir o registro da tabela com o ID especificado
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
