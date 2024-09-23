<?php 
require_once("../sistema/conexao.php");

$serv = $_POST['serv'];

$query = $pdo->query("SELECT * FROM servicos_funcionario where servico = '$serv' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
echo '<option value="">'.$texto_agendamento.'</option>';
if(@count($resultado) > 0){
	for($i=0; $i < @count($resultado); $i++){
		$funcionario = $resultado[$i]['funcionario']; 

		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario' and ativo = 'Sim' ");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);	
		$nome_funcionario = $resultado2[0]['nome'];

		echo '<option value="'.$funcionario.'">'.$nome_funcionario.'</option>';
	}		
}


?>

