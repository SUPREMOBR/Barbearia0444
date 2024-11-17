<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'textos_index'; // Define o nome da tabela no banco de dados

$id = $_POST['id']; //Recebe o ID passado via POST (Formulário)

// Faz uma consulta ao banco de dados para buscar todos os dados do registro que corresponde ao ID fornecido.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
