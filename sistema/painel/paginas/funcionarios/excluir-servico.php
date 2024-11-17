<?php
require_once("../../../conexao.php");  // Conecta ao banco de dados.
$tabela = 'servicos_funcionarios'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Executa a query para excluir o registro com o ID informado
$pdo->query("DELETE from $tabela where id = '$id'"); // Deleta o serviço/funcionário da tabela
echo 'Excluído com Sucesso';
