<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dado

$id = $_POST['id']; // Recebe o ID do registro a ser excluído via POST.

// A consulta deleta o registro da tabela 'pagar' com o ID fornecido.
$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
