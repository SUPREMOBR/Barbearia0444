<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'dias'; // Define o nome da tabela no banco de dado

$id_func = $_POST['func']; // ID do funcionário a partir do formulário

// Realiza a consulta no banco de dados para pegar os dias de trabalho do funcionário
$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_func' ORDER BY id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

// Verifica se foram encontrados registros para o funcionário
if ($total_registro > 0) {

	echo <<<HTML
	<small><small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th>Dia</th>	
	<th>Jornada</th>	
	<th>Almoço</th>		
	<th>Excluir</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Percorre os registros de dias de trabalho do funcionário
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Atribui variáveis para facilitar a leitura dos dados
		$id = $resultado[$i]['id'];  // ID do registro
		$dia = $resultado[$i]['dia'];  // Dia da semana ou data
		$inicio = $resultado[$i]['inicio'];  // Hora de início da jornada
		$final = $resultado[$i]['final'];  // Hora de término da jornada
		$inicio_almoco = $resultado[$i]['inicio_almoco'];  // Hora de início do almoço
		$final_almoco = $resultado[$i]['final_almoco'];  // Hora de término do almoço

		// Verifica se o horário de início do almoço não foi lançado (caso esteja como '00:00:00')
		if ($inicio_almoco == '00:00:00') {
			$inicio_almoco = 'Não Lançado';  // Se não foi registrado, exibe 'Não Lançado'
		}

		// Verifica se o horário de término do almoço não foi lançado (caso esteja como '00:00:00')
		if ($final_almoco == '00:00:00') {
			$final_almoco = 'Não Lançado';  // Se não foi registrado, exibe 'Não Lançado'
		}

		echo <<<HTML
<tr class="">
<td class="">{$dia}</td>
<td class="">{$inicio} / {$final}</td>
<td class="">{$inicio_almoco} / {$final_almoco}</td>

<td>


		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirDias('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

		<big><a href="#" onclick="editarDias('{$id}','{$dia}', '{$inicio}', '{$final}', '{$inicio_almoco}', '{$final_almoco}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		</td>

</tr>
HTML;
	}

	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-dias-excluir"></div></small>
</table>
</small></small>
HTML;
} else {
	echo '<small>Não possui nenhum Dia Cadastrado!</small>';
}

?>


<script type="text/javascript">
	function excluirDias(id) {
		$.ajax({
			url: 'paginas/' + pag + "/excluir-dias.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(mensagem) {
				if (mensagem.trim() == "Excluído com Sucesso") {
					var func = $("#id_dias").val();
					listarDias(func);
				} else {
					$('#mensagem-dias-excluir').addClass('text-danger')
					$('#mensagem-dias-excluir').text(mensagem)
				}

			},

		});
	}


	function editarDias(id, dia, inicio, final, inicio_almoco, final_almoco) {
		$('#id_d').val(id);
		$('#dias').val(dia).change();
		$('#inicio').val(inicio);
		$('#final').val(final);
		$('#inicio_almoco').val(inicio_almoco);
		$('#final_almoco').val(final_almoco);
	}


	function limparCampos() {
		$('#id_d').val('');

	}
</script>