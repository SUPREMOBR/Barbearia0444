<?php
require_once("../../../conexao.php");
@session_start();
$usuario = @$_SESSION['id'];

$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];
$hora_rec = '';
$hora_atual = date('H:i:s');
$hoje = date('Y-m-d');

$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Funcionário não trabalha neste Dia!';
	exit();
} else {
	$inicio = $resultado[0]['inicio'];
	$final = $resultado[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo'];


?>
<div class="row">

	<?php
	$i = 0;
	while (strtotime($inicio) <= strtotime($final)) {

		if (strtotime($inicio) >= strtotime($inicio_almoco) and strtotime($inicio) < strtotime($final_almoco)) {
			$hora_minutos = strtotime("+$intervalo minutes", strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos);
		} else {

			$hora = $inicio;
			$horaFormatada = date("H:i", strtotime($hora));
			$dataHora = '';

			//validar horario
			$query2 = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
			$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);

			$total_registro2 = @count($resultado2);

			if (strtotime(@$resultado2[0]['hora']) == strtotime($inicio)) {
				$esconder = 'text-danger';
				$checado = 'disabled';
			} else {
				$esconder = '';
				$checado = '';
			}

			if (strtotime($hora) < strtotime($hora_atual) and strtotime($data) == strtotime($hoje)) {
				$esconder2 = 'text-danger';
				$checado2 = 'disabled';
				$ocultar = 'ocultar';
			} else {
				$ocultar = '';
				$esconder2 = '';
				$checado2 = '';
				$i += 1;

				//VERIFICAR NA TABELA HORARIOS AGD SE TEM O HORARIO NESSA DATA
				$query_agd = $pdo->query("SELECT * FROM horarios_agd where data = '$data' and funcionario = '$funcionario' and horario = '$hora'");
				$resultado_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
				if (@count($resultado_agd) > 0) {
					$esconder3 = 'text-danger';
					$checado3 = 'disabled';
					$ocultar = 'ocultar'; //opcional
				} else {
					$esconder3 = '';
					$checado3 = '';
				}
			}



			if (strtotime($dataHora) != strtotime($data) and $dataHora != "" and $dataHora != "null") {
				continue;
			}
	?>

			<div class="col-md-2 <?php echo $ocultar ?>">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="hora" value="<?php echo $hora ?>" <?php echo $checado ?> <?php echo $checado2 ?> <?php echo $checado3 ?>>
					<label class="form-check-label <?php echo $esconder ?> <?php echo $esconder2 ?> <?php echo $esconder3 ?>" for="flexRadioDefault1">
						<?php echo $horaFormatada ?>
					</label>
				</div>
			</div>
	<?php



			$hora_minutos = strtotime("+$intervalo minutes", strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos);
		}
	}

	if ($i == 0) {
		echo 'Não temos mais horários disponíveis com este funcionário para essa data!';
	}
	?>



</div>