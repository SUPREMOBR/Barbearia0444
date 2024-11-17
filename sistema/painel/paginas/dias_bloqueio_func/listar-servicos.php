<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'dias_bloqueio'; // Define o nome da tabela no banco de dado

@session_start(); // Inicia a sessão ou retoma a sessão ativa
$id_usuario = $_SESSION['id'];  // Obtém o ID do usuário logado na sessão.

$id_func = $_POST['func']; // Obtém o ID do funcionário enviado via POST (formulário).

// Exclui todas as entradas da tabela `dias_bloqueio` com datas anteriores à data atual.
$pdo->query("DELETE FROM $tabela where data < curDate()");

// Consulta para buscar todos os dias de bloqueio para o usuário logado, ordenados por data.
$query = $pdo->query("SELECT * FROM $tabela where funcionario = '$id_usuario' order by data asc");
$resultadoultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o total de registros encontrados.
if ($total_registro > 0) {

	echo <<<HTML
	<small><small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th>Data</th>	
	<th>Lançado Por</th>	
	<th>Excluir</th>
	
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Loop para exibir cada registro encontrado
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id'];
		$data = $resultado[$i]['data'];
		$usuario = $resultado[$i]['usuario'];

		// Consulta para buscar o nome do usuário que lançou o bloqueio
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome_servico = @$resultado2[0]['nome']; // Obtém o nome do usuário que lançou.

		$dataF = implode('/', array_reverse(@explode('-', $data)));

		// Exibe os dados do registro em uma linha da tabela
		echo <<<HTML
<tr class="">
<td class="">{$dataF}</td>
<td class="">{$nome_servico}</td>
<td>


		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirServico('{$id}', '{$id_func}')"><span class="text-danger">Sim</span></a></p>
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
<small><div align="center" id="mensagem-servico-excluir"></div></small>
</table>
</small></small>
HTML;
} else {
	echo '<small>Não possui nenhuma data Cadastrada!</small>';
}

?>


<script type="text/javascript">
	function excluirServico(id, func) {
		$.ajax({
			url: 'paginas/' + pag + "/excluir-servico.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(mensagem) {
				if (mensagem.trim() == "Excluído com Sucesso") {

					listarServicos(func); // Atualiza a lista após a exclusão.
				} else {
					$('#mensagem-servico-excluir').addClass('text-danger')
					$('#mensagem-servico-excluir').text(mensagem)
				}

			},

		});
	}
</script>