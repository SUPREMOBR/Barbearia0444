<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'comentarios'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Recebe o ID do comentário a ser alterado via POST.
$acao = $_POST['acao']; // Recebe a ação (Ativar/Desativar) via POST.

// Executa a consulta para atualizar o campo 'ativo' do comentário com o novo valor (Sim/Não) baseado no ID.
$pdo->query("UPDATE $tabela SET ativo = '$acao' where id = '$id'");
echo 'Alterado com Sucesso';
