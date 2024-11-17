<?php
require_once("../../../conexao.php"); # Conecta ao banco de dados.
$tabela = 'dias_bloqueio'; # Define o nome da tabela no banco de dado

$id_func = $_POST['func']; # Recupera o ID do funcionário via POST(Formulário)

$pdo->query("DELETE FROM $tabela where data < curDate()");

# 1-Exclui todos os registros na tabela 'dias_bloqueio' onde a data é anterior à data atual.
# 2-Realiza uma consulta para pegar todos os registros da tabela 'dias_bloqueio' onde o campo 'funcionario' é igual a 0.
# 3-A consulta ordena os registros pela data de forma crescente.
$query = $pdo->query("SELECT * FROM $tabela where funcionario = 0 order by data asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
# Verifica se existem registros retornados pela consulta
if ($total_registro > 0) {
	// Exibe o início de uma tabela HTML.
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
	// Loop que percorre os registros retornados da consulta.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Armazena o 'id', 'data' e 'usuario' do registro atual.
		$id = $resultado[$i]['id'];
		$data = $resultado[$i]['data'];
		$usuario = $resultado[$i]['usuario'];

		// Realiza uma nova consulta para pegar os dados do usuário que lançou o bloqueio.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome_servico = @$resultado2[0]['nome']; // Armazena o nome do usuário

		$dataF = implode('/', array_reverse(@explode('-', $data)));

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

					listarServicos(func);
				} else {
					$('#mensagem-servico-excluir').addClass('text-danger')
					$('#mensagem-servico-excluir').text(mensagem)
				}

			},

		});
	}
</script>