<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'categoria_servicos'; // Define o nome da tabela no banco de dado

// Executa uma consulta para obter todos os registros da tabela 'categoria_servicos', ordenados pelo ID em ordem decrescente.
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Verifica se a consulta retornou registros.
if ($total_registro > 0) {
	// Inicia a construção de uma tabela HTML para exibir os dados.
	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th>Serviços</th>		
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

		// Executa uma consulta para contar quantos serviços estão associados a essa categoria
		$query2 = $pdo->query("SELECT * FROM servicos where categoria = '$id'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_servicos = @count($resultado2);


		echo <<<HTML
<tr class="">
<td>{$nome}</td>
<td>{$total_servicos}</td>
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

<!-- Função JavaScript para editar o registro -->
<script type="text/javascript">
	function editar(id, nome) {
		// Preenche os campos do formulário de edição com os valores recebidos.
		$('#id').val(id);
		$('#nome').val(nome);
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
	}

	function limparCampos() {
		// Função para limpar os campos do formulário de edição.
		$('#nome').val('');
		$('#id').val('');
	}
</script>