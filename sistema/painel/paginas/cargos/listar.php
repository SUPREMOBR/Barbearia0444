<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'cargos'; // Define o nome da tabela no banco de dado

// Executa a consulta para obter todos os registros da tabela, ordenados em ordem decrescente de ID.
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Verifica se há registros no resultado da consulta.
if ($total_registro > 0) {
	// Inicia a construção de uma tabela HTML para exibir os dados.
	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Loop pelos registros retornados para construir as linhas da tabela.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Obtém os valores de cada registro para preencher as colunas da tabela.
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];

		// Gera uma linha de tabela para cada registro, com opções de edição e exclusão.
		echo <<<HTML
<tr class="">
<td>{$nome}</td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		
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
	// Finaliza a tabela HTML e adiciona um elemento para exibir mensagens de exclusão.
	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
</small>
HTML;
} else {
	// Exibe uma mensagem caso não haja registros na tabela.
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>


<script type="text/javascript">
	// Inicializa a tabela com DataTables, desativa ordenação e salva o estado da tabela entre recarregamentos.
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>


<script type="text/javascript">
	// Função para preencher o formulário com os dados do registro ao clicar em "editar".
	function editar(id, nome) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
	}
	// Função para limpar o campo "nome" ao finalizar edição.
	function limparCampos() {
		$('#nome').val('');
	}
</script>