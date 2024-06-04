<?php 
require_once("../../../conexao.php");
@session_start();
$usuario = @$_SESSION['id'];

$funcionario = @$_POST['funcionario'];

?>

<select class="form-control" id="hora" name="hora" required> 
<option value="">Selecionar</option>
	<?php 
	$query = $pdo->query("SELECT * FROM horarios where funcionario = '$funcionario' ORDER BY horario asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	if($total_registro > 0){
		for($i=0; $i < $total_registro; $i++){
			foreach ($resultado[$i] as $key => $value){}
				$hora = $resultado[$i]['horario'];
				$horaFormatada = date("H:i", strtotime($hora));
				echo '<option value="'.$resultado[$i]['horario'].'">'.$horaFormatada.'</option>';
		}
	}
	?>


</select>    