<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'fornecedores'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

# Consulta para excluir o registro correspondente ao ID fornecido na tabela fornecedores
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
