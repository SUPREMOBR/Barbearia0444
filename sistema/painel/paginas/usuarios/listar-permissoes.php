<?php 
require_once("../../../conexao.php");


$id_usuario = $_POST['id'];

$checked = '';
$query = $pdo->query("SELECT * FROM acessos where grupo = 0 order by id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

if($total_registro > 0){
	echo '<span class="titulo-grupo"><b>Sem Grupo</b></span><br><div class="row">';
	for($i=0; $i < $total_registro; $i++){
		foreach ($resultado[$i] as $key => $value){}
			$nome = $resultado[$i]['nome'];
		$chave = $resultado[$i]['chave'];
		$id = $resultado[$i]['id'];


		$query2 = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' and permissao = '$id'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$checked = 'checked';
		}else{
			$checked = '';
		}


		echo '
		<div class="form-check col-md-3">
		<input class="form-check-input" type="checkbox" value="" id="" '.$checked.' onclick="adicionarPermissao('.$id.','.$id_usuario.')">
		<label class="labelcheck" >
		'.$nome.'
		</label>
		</div>
		';
	}

	echo '</div><hr>';	
}



$query = $pdo->query("SELECT * FROM grupo_acessos ORDER BY id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$nome = $resultado[$i]['nome'];

	echo '<span class="titulo-grupo"><b>'.$nome.'</b></span><br><div class="row">';

	

$checked = '';
$query3 = $pdo->query("SELECT * FROM acessos where grupo = '$id' order by id asc");
$resultado3 = $query3->fetchAll(PDO::FETCH_ASSOC);
$total_registro3 = @count($resultado3);

if($total_registro3 > 0){	
	for($i3=0; $i3 < $total_registro3; $i3++){
		foreach ($resultado3[$i3] as $key => $value){}
		$nome = $resultado3[$i3]['nome'];
		$chave = $resultado3[$i3]['chave'];
		$id = $resultado3[$i3]['id'];


		$query2 = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario' and permissao = '$id'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$checked = 'checked';
		}else{
			$checked = '';
		}


		echo '
		<div class="form-check col-md-3">
		<input class="form-check-input" type="checkbox" value="" id="" '.$checked.' onclick="adicionarPermissao('.$id.','.$id_usuario.')">
		<label class="labelcheck" >
		'.$nome.'
		</label>
		</div>
		';
	}

	echo '</div><hr>';	
}


}

}

?>