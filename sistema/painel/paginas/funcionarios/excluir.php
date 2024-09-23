<?php 
require_once("../../../conexao.php");
$tabela = 'usuarios01';

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$foto = $resultado[0]['foto'];

if($foto != "sem-foto.jpg"){
	@unlink('../../img/perfil/'.$foto);
}

$pdo->query("DELETE from $tabela where id = '$id'");

$pdo->query("DELETE from servicos_funcionarios where funcionario = '$id'");

echo 'Excluído com Sucesso';
 ?>