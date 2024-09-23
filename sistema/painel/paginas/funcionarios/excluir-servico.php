<?php 
require_once("../../../conexao.php");
$tabela = 'servicos_funcionarios';

$id = $_POST['id'];


$pdo->query("DELETE from $tabela where id = '$id'");
echo 'Excluído com Sucesso';
 ?>