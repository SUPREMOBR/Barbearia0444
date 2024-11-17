<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
@session_start(); // Inicia a sessão para acessar variáveis de sessão
$usuario = @$_SESSION['id']; // Obtém o ID do usuário logado a partir da sessão.

$checado3 = '';
$esconder3 = '';

// Recebe o ID do funcionário e a data desejada via POST
$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];
$hora_atual = date('H:i:s'); // Hora atual no formato HH:MM:SS
$hoje = date('Y-m-d'); // Data atual no formato AAAA-MM-DD

// Verifica se o estabelecimento estará fechado nesta data (bloqueio geral)
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '0' and data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Não estaremos funcionando nesta Data!';
	exit();
}

// Verifica se o funcionário selecionado estará ausente nesta data
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '$funcionario'  and data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Este Profissional não irá trabalhar nesta Data, selecione outra data ou escolhar outro Profissional!';
	exit();
}

// Define os dias da semana
$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
// Obtém o dia da semana para a data fornecida
$diasemana_numero = date('w', @strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

// Verifica se o funcionário trabalha no dia da semana selecionado
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Funcionário não trabalha neste Dia!';
	exit();
} else {
	// Armazena o horário de trabalho e de intervalo do funcionário
	$inicio = $resultado[0]['inicio'];
	$final = $resultado[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}

// Obtém o intervalo de tempo entre cada atendimento do funcionário
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo'];

?>
<div class="row">

	<?php
	$i = 0; // Contador de horários disponíveis
	// Itera pelos horários, incrementando de acordo com o intervalo
	while (@strtotime($inicio) <= @strtotime($final)) {
		// Pula o horário se estiver no período de almoço
		if (@strtotime($inicio) >= @strtotime($inicio_almoco) and @strtotime($inicio) < @strtotime($final_almoco)) {
			$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos);
		} else {

			$hora = $inicio;
			$horaF = date("H:i", @strtotime($hora)); // Formata para exibir como HH:MM
			$dataH = '';

			// Verifica se o horário está ocupado
			$query2 = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
			$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$total_registro2 = @count($resultado2);

			// Se o horário já está agendado, desabilita a opção
			if (@strtotime(@$resultado2[0]['hora']) == @strtotime($inicio)) {
				$esconder = 'text-danger';
				$checado = 'disabled';
			} else {
				$esconder = '';
				$checado = '';
			}
			// Desabilita horários no passado para o mesmo dia
			if (@strtotime($hora) < @strtotime($hora_atual) and @strtotime($data) == @strtotime($hoje)) {
				$esconder2 = 'text-danger';
				$checado2 = 'disabled';
				$ocultar = 'ocultar';
			} else {
				$ocultar = '';
				$esconder2 = '';
				$checado2 = '';
				$i += 1;

				// Verifica se o horário já está reservado em outra tabela
				$query_agd = $pdo->query("SELECT * FROM horarios_agd where data = '$data' and funcionario = '$funcionario' and horario = '$hora'");
				$resultado_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
				if (@count($resultado_agd) > 0) {
					$esconder3 = 'text-danger';
					$checado3 = 'disabled';
				} else {
					$esconder3 = '';
					$checado3 = '';
				}
			}

			if (@strtotime($dataH) != @strtotime($data) and $dataH != "" and $dataH != "null") {
				continue;
			}
	?>

			<div class="col-md-2 <?php echo $ocultar ?>">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="hora" value="<?php echo $hora ?>" <?php echo $checado ?> <?php echo $checado2 ?> <?php echo $checado3 ?>>
					<label class="form-check-label <?php echo $esconder ?> <?php echo $esconder2 ?> <?php echo $esconder3 ?>" for="flexRadioDefault1">
						<?php echo $horaF ?>
					</label>
				</div>
			</div>
	<?php

			// Incrementa o horário de início conforme o intervalo
			$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos);
		}
	}
	// Mensagem caso não haja horários disponíveis
	if ($i == 0) {
		echo 'Não temos mais horários disponíveis com este funcionário para essa data!';
	}
	?>



</div>