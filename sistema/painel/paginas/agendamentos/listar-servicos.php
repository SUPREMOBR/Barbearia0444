<?php 
require_once("../../../conexao.php");

$funcionario = $_POST['funcionario']; //func

$query = $pdo->query("SELECT * FROM servicos_funcionarios where funcionario = '$funcionario' ");  //func
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0){
	for($i=0; $i < @count($resultado); $i++){
		$serv = $resultado[$i]['servico'];

		$query2 = $pdo->query("SELECT * FROM servicos where id = '$serv' and ativo = 'Sim' ");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);	
		$nome_funcionario = $resultado2[0]['nome'];

		echo '<option value="'.$serv.'">'.$nome_funcionario.'</option>';
	}		
}else{
	echo '<option value="">Nenhum Serviço</option>';
}


?>

