<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'formas_pagamento';  // Define o nome da tabela no banco de dados

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Executa a consulta de exclusão do registro
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
