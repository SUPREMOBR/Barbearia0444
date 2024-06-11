<?php 
require_once("../../../conexao.php");

$id_usuario = $_POST['id'];

$pdo->query("DELETE FROM usuarios_permissoes where usuario = '$id_usuario'");

$query = $pdo->query("SELECT * FROM acessos order by id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
	for($i=0; $i < $total_registro; $i++){
		foreach ($resultado[$i] as $key => $value){}
			$nome = $resultado[$i]['nome'];
		$chave = $resultado[$i]['chave'];
		$id = $resultado[$i]['id'];

		$query = $pdo->query("INSERT INTO usuarios_permissoes SET permissao = '$id', usuario = '$id_usuario'");

	}
}

?>