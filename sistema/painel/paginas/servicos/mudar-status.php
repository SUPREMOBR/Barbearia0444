<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'servicos'; // Define o nome da tabela no banco de dados

// Recebe os dados passados via POST (id e ação)
$id = $_POST['id']; // ID do serviço a ser atualizado
$acao = $_POST['acao']; // Ação a ser realizada (ex: Ativar ou Desativar)

// consulta SQL para atualizar o campo 'ativo' do serviço especificado
$pdo->query("UPDATE $tabela SET ativo = '$acao' where id = '$id'");
echo 'Alterado com Sucesso';
