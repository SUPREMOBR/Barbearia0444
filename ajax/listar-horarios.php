<?php
require_once("../sistema/conexao.php"); // Conecta ao banco de dados.
@session_start(); // Inicia a sessão para acessar variáveis de sessão
$usuario = @$_SESSION['id']; // Obtém o ID do usuário logado da sessão

// Obtém os dados enviados via POST (Formulário)
$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];
$hora_rec = @$_POST['hora'];

// Obtém a data e hora atual
$hoje = date('Y-m-d');
$hora_atual = date('H:i:s');

// Verifica se a data selecionada é anterior à data atual
if (strtotime($data) < strtotime($hoje)) {
	echo '000';
	exit();
}
// Verifica se o funcionário foi selecionado
if ($funcionario == "") {

	exit();
}

// Verifica se a data está bloqueada para todos os funcionários
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '0' and data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Não estaremos funcionando nesta Data!';
	exit();
}

// Verifica se a data está bloqueada para o funcionário específico
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '$funcionario'  and data = '$data'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0) {
	echo 'Este Profissional não irá trabalhar nesta Data, selecione outra data ou escolhar outro Profissional!';
	exit();
}

// Determina o dia da semana correspondente à data selecionada
$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

// Verifica se o funcionário trabalha no dia selecionado
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) == 0) {
	echo 'Este Funcionário não trabalha neste Dia!';
	exit();
} else {
	// Obtém os horários de trabalho e intervalo do funcionário
	$inicio = $resultado[0]['inicio'];
	$final = $resultado[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}

// Obtém o intervalo de tempo entre os horários de agendamento do funcionário
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo'];


?>
<div class="row">

	<?php
	$i = 0; // Contador de horários disponíveis
	// Percorre os horários do início ao fim do expediente
	while (strtotime($inicio) <= strtotime($final)) {
		// Verifica se o horário está dentro do intervalo de almoço
		if (strtotime($inicio) >= strtotime($inicio_almoco) and strtotime($inicio) < strtotime($final_almoco)) {
			// Incrementa o horário para após o intervalo
			$hora_minutos = strtotime("+$intervalo minutes", strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos);
		} else {
			$hora = $inicio;  // Define o horário atual
			$horaF = date("H:i", strtotime($hora));  // Formata o horário
			$dataH = '';

			// Verifica se o horário já está agendado
			$query2 = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
			$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$total_registro2 = @count($resultado2);

			if ($total_registro2 == 0 || strtotime($hora_rec) == strtotime($hora)) {
				$hora_agendada = '';
				$texto_hora = '';
				// Marca o horário como selecionado, se for igual ao recebido
				if (strtotime($hora_rec) == strtotime($hora)) {
					$checado = 'checked';
				} else {
					$checado = '';
				}
				// Verifica se o horário é anterior à hora atual para a data de hoje
				if (strtotime($hora) < strtotime($hora_atual) and strtotime($data) == strtotime($hoje)) {
					$esconder = 'none';
				} else {
					$esconder = '';

					// Verifica se o horário está na tabela de horários bloqueados
					$query_agd = $pdo->query("SELECT * FROM horarios_agd where data = '$data' and funcionario = '$funcionario' and horario = '$hora'");
					$resultado_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado_agd) > 0) {
						$esconder = 'none';
					} else {
						$esconder = '';
						$i += 1; // Incrementa o contador de horários disponíveis
					}
				}



				if (strtotime($dataH) != strtotime($data) and $dataH != "" and $dataH != "null") {
					continue;
				}
				// Exibe o horário disponível no formulário
	?>

				<div class="col-3" style='display: <?php echo $esconder ?>'>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="hora" value="<?php echo $hora ?>" <?php echo $hora_agendada ?> style="width:17px; height: 17px; " required <?php echo $checado ?>>
						<label class="form-check-label <?php echo $texto_hora ?>" for="flexRadioDefault1">
							<?php echo $horaF ?>
						</label>
					</div>
				</div>

	<?php

			}

			// Incrementa o horário para o próximo intervalo
			$hora_minutos = strtotime("+$intervalo minutes", strtotime($inicio));
			$inicio = date('H:i:s', $hora_minutos);
		}
	}
	// Caso nenhum horário esteja disponível
	if ($i == 0) {
		echo '<div align="center"> <small>Não temos mais horários disponíveis com este funcionário para essa data!</small></div>';
	}
	?>


</div>