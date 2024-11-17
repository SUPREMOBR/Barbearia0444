<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
// Define o nome da tabela no banco de dados
$tabela = 'acessos';

// Executa a consulta para selecionar todos os registros da tabela, ordenados em ordem decrescente pelo campo 'id'
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o total de registros encontrados
// Verifica se há algum registro
if ($total_registro > 0) {
	// Inicia a tabela HTML
	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Chave</th> 	
	<th class="esc">Grupo</th> 		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Loop para exibir cada registro encontrado
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Armazena os valores das colunas para cada registro
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$chave = $resultado[$i]['chave'];
		$grupo = $resultado[$i]['grupo'];

		// Executa uma nova consulta para obter o nome do grupo baseado no ID do grupo
		$query2 = $pdo->query("SELECT * FROM grupo_acessos where id = '$grupo'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		// Verifica se encontrou algum grupo
		if ($total_registro2 > 0) {
			$nome_categoria = $resultado2[0]['nome'];
		} else {
			$nome_categoria = 'Nenhum!';
		}
		// Exibe uma linha na tabela HTML com os dados do registro
		echo <<<HTML
<tr class="">
<td>
{$nome}
</td>
<td class="esc">{$chave}</td>
<td class="esc">{$nome_categoria}</td>
<td>
	    <!-- Ícone para editar o registro -->
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$chave}', '{$grupo}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>
        <!-- Ícone para excluir o registro com dropdown de confirmação -->
		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>
        <!-- Menu de confirmação para exclusão -->
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
	// Fecha a tabela HTML
	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
</small>
HTML;
} else {
	// Exibe uma mensagem caso não haja registros
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>
<!-- Inicializa a tabela com DataTables para funcionalidades como pesquisa e paginação -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false, // Desativa a ordenação
			"stateSave": true // Salva o estado da tabela
		});
		$('#tabela_filter label input').focus(); // Define o foco no campo de pesquisa da tabela
	});
</script>

<!-- Função para abrir o modal de edição com os dados do registro selecionado -->
<script type="text/javascript">
	function editar(id, nome, chave, grupo) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#chave').val(chave);
		$('#grupo').val(grupo).change();

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');

	}
	// Função para limpar os campos do formulário
	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#chave').val('');
	}
</script>