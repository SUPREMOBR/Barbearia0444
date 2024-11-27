<?php
require_once("../../sistema/conexao.php");

$usuario = @$_POST['id_usuario'];
$funcionario = @$_POST['id_usuario'];
$data = @$_POST['dataInicial'];


$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo '<span style="font-size:12px">Este Funcionário não trabalha neste Dia!</span>';
	exit();
}

?>
<div class="row">

	<?php
	$query = $pdo->query("SELECT * FROM horarios where funcionario = '$funcionario' ORDER BY horario asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	if ($total_registro > 0) {
		for ($i = 0; $i < $total_registro; $i++) {
			foreach ($resultado[$i] as $key => $value) {
			}
			$hora = $resultado[$i]['horario'];
			$horaF = date("H:i", strtotime($hora));
			$dataH = $resultado[$i]['data'];

			//validar horario
			$query2 = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
			$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$total_registro2 = @count($resultado2);
			if ($total_registro2 > 0) {
				$hora_agendada = 'disabled';
				$texto_hora = 'red';
			} else {
				$hora_agendada = '';
				$texto_hora = '';
			}


			if (strtotime($dataH) != strtotime($data) and $dataH != "" and $dataH != "null") {
				continue;
			}
	?>

			<div class="col-33">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="hora" value="<?php echo $hora ?>" <?php echo $hora_agendada ?>>
					<label style="font-size:12px; color:<?php echo $texto_hora ?>" class="form-check-label" for="flexRadioDefault1">
						<?php echo $horaF ?>
					</label>
				</div>
			</div>

	<?php

		}
	}
	?>


</div>