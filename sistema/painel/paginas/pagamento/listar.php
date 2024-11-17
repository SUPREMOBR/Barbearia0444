<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'formas_pagamento'; // Define o nome da tabela no banco de dados

// Consulta os registros da tabela 'formas_pagamento' e ordena por ID decrescente
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>		
	<th>Taxa</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Loop para exibir cada registro da tabela 'formas_pagamento'
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Atribui os valores de cada campo do registro a variáveis locais
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$taxa = $resultado[$i]['taxa'];

		// Se o valor da taxa estiver vazio, substitui por 0
		if ($taxa == "") {
			$taxa = 0;
		}
		// Exibe os dados do registro na tabela HTML
		echo <<<HTML
<tr class="">
<td>{$nome}</td> <!-- Exibe o nome da forma de pagamento -->
<td>{$taxa}%</td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}','{$taxa}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		
		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
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
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
</small>
HTML;
} else {
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>


<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>


<script type="text/javascript">
	function editar(id, nome, taxa) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#taxa').val(taxa);
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
	}

	function limparCampos() {
		$('#nome').val('');
		$('#taxa').val('');
	}
</script>