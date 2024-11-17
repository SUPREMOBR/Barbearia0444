<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'clientes'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Recebe o ID do cliente a ser excluído via POST.

// consulta para excluir o registro da tabela 'clientes' com o ID fornecido.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
