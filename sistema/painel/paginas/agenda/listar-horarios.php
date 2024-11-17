<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
@session_start(); // Inicia a sessão para acessar variáveis de sessão
$usuario = @$_SESSION['id']; // Obtém o ID do usuário logado a partir da sessão.

$funcionario = @$_SESSION['id']; // Define o funcionário como o usuário logado.
$data = @$_POST['data']; // Recebe a data do agendamento através do POST.
$hora_rec = ''; // Inicializa uma variável para hora recebida (sem valor inicial).
$hora_atual = date('H:i:s'); // Define a hora atual.
$hoje = date('Y-m-d'); // Define a data de hoje.

// Verifica se a data está bloqueada para todos os funcionários.
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '0' and data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Não estaremos funcionando nesta Data!'; // Mensagem de indisponibilidade.
	exit();
}

// Verifica se a data está bloqueada para o funcionário específico.
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '$funcionario' and data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Este Profissional não irá trabalhar nesta Data, selecione outra data ou escolhar outro Profissional!';
	exit();
}

// Define o dia da semana correspondente à data.
$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', @strtotime($data)); // Número do dia da semana.
$dia_procurado = $diasemana[$diasemana_numero]; // Nome do dia da semana.

// Verifica se o funcionário trabalha no dia da semana especificado.
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Funcionário não trabalha neste Dia!';
	exit();
} else {
	// Define o horário de início e fim do expediente e o intervalo de almoço.
	$inicio = $resultado[0]['inicio'];
	$final = $resultado[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}

// Consulta o intervalo de tempo entre atendimentos do funcionário.
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo']; // Intervalo entre atendimentos.

?>
<div class="row">

	<?php
	$i = 0;
	while (@strtotime($inicio) <= @strtotime($final)) {
		// Itera pelos horários dentro do expediente.
		// Verifica se o horário coincide com o horário de almoço.
		if (@strtotime($inicio) >= @strtotime($inicio_almoco) and @strtotime($inicio) < @strtotime($final_almoco)) {
			$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos); // Avança o horário para depois do almoço.
		} else {

			$hora = $inicio; // Define a hora atual.
			$horaF = date("H:i", @strtotime($hora));  // Formata a hora para exibição.
			$dataH = '';

			// Verifica se o horário já está ocupado pelo funcionário.
			$query2 = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
			$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$total_registro2 = @count($resultado2);

			// Define classes para desativar horários ocupados.
			if (@strtotime(@$resultado2[0]['hora']) == @strtotime($inicio)) {
				$esconder = 'text-danger';
				$checado = 'disabled';
			} else {
				$esconder = '';
				$checado = '';
			}

			// Desativa horários passados para agendamentos no dia atual.
			if (@strtotime($hora) < @strtotime($hora_atual) and @strtotime($data) == @strtotime($hoje)) {
				$esconder2 = 'text-danger';
				$checado2 = 'disabled';
				$ocultar = 'ocultar';
			} else {
				$ocultar = '';
				$esconder2 = '';
				$checado2 = '';
				$i += 1; // Incrementa o contador de horários disponíveis.

				// Verifica se o horário está ocupado em outra tabela (horarios_agd).
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

			// Verifica a data e mostra somente horários para o dia selecionado.
			if (@strtotime($dataH) != @strtotime($data) and $dataH != "" and $dataH != "null") {
				continue;
			}
	?>
			<!-- Exibe um horário disponível como opção de agendamento -->
			<div class="col-md-2 <?php echo $ocultar ?>">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="hora" value="<?php echo $hora ?>" <?php echo $checado ?> <?php echo $checado2 ?> <?php echo $checado3 ?>>
					<label class="form-check-label <?php echo $esconder ?> <?php echo $esconder2 ?> <?php echo $esconder3 ?>" for="flexRadioDefault1">
						<?php echo $horaF ?>
					</label>
				</div>
			</div>
	<?php

			$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos);
		}
	}
	// Exibe mensagem se não houver horários disponíveis para a data.
	if ($i == 0) {
		echo 'Não temos mais horários disponíveis com este funcionário para essa data!';
	}
	?>

</div>