<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'horarios'; // Define o nome da tabela no banco de dado

$id_func = $_POST['func']; // ID do funcionário a partir do formulário

// Realiza a consulta no banco de dados para pegar os horários do funcionário
$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_func' ORDER BY horario asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

// Verifica se foram encontrados registros para o funcionário
if ($total_registro > 0) {

	echo <<<HTML
	<small><small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th>Horário</th>		
	<th>Excluir</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Percorre os registros de horários do funcionário
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Atribui variáveis para facilitar a leitura dos dados
		$id = $resultado[$i]['id'];  // ID do registro de horário
		$horario = $resultado[$i]['horario'];  // Horário do funcionário
		$horarioF = date("H:i", strtotime($horario));  // Formata o horário para exibir em HH:MM
		$data = $resultado[$i]['data'];  // Data associada ao horário
		$dataF = implode('/', array_reverse(explode('-', $data)));  // Formata a data para o formato brasileiro 

		// Verifica se a data foi fornecida e cria um texto temporário
		if ($data != "") {
			$temp = '<span class="text-danger"><small>(Temporário Data: ' . $dataF . '</small></span>';  // Se tiver data, mostra "Temporário"
		} else {
			$temp = '';  // Caso contrário, não exibe nada
		}

		echo <<<HTML
<tr class="">
<td class="">{$horarioF} {$temp}</td>
<td>


		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirHorarios('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>



		</td>
</tr>
HTML;
	}

	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-horario-excluir"></div></small>
</table>
</small></small>
HTML;
} else {
	echo '<small>Não possui nenhum Horário Cadastrado!</small>';
}

?>


<script type="text/javascript">
	// Função para excluir um horário do funcionário
	function excluirHorarios(id) {
		$.ajax({
			url: 'paginas/' + pag + "/excluir-horarios.php",
			method: 'POST',
			data: {
				// Envia o ID do horário a ser excluído
				id
			},
			dataType: "text",

			success: function(mensagem) {
				// Se a exclusão for bem-sucedida, reexibe os horários atualizados
				if (mensagem.trim() == "Excluído com Sucesso") {
					var func = $("#id_horarios").val(); // Obtém o ID do funcionário
					listarHorarios(func); // Chama uma função para listar novamente os horários atualizados
				} else {
					$('#mensagem-horario-excluir').addClass('text-danger')
					$('#mensagem-horario-excluir').text(mensagem)
				}

			},

		});
	}
</script>