<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'usuarios01'; // Define o nome da tabela no banco de dado

// Recebe os dados enviados pelo formulário via POST
$id = $_POST['id']; // ID do usuário que será alterado
$acao = $_POST['acao']; // Ação que será realizada (ativar/desativar)

// Executa a atualização no banco de dados, alterando o campo 'ativo' para o valor da variável $acao
$pdo->query("UPDATE $tabela SET ativo = '$acao' where id = '$id'");
echo 'Alterado com Sucesso';
