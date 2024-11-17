<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'fornecedores'; // Define o nome da tabela no banco de dado

// Consulta todos os registros da tabela 'fornecedores', ordenados do mais recente ao mais antigo.
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o número total de registros retornados. O "@" suprime erros caso $resultado seja nulo.
// Verifica se há registros para exibir.
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Telefone</th> 	
	<th class="esc">Cadastro</th> 	
	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];

		$data_cadastro = $resultado[$i]['data_cadastro'];
		$telefone = $resultado[$i]['telefone'];
		$endereco = $resultado[$i]['endereco'];
		$tipo_chave = $resultado[$i]['tipo_chave'];
		$chave_pix = $resultado[$i]['chave_pix'];



		$data_cadastroF = implode('/', array_reverse(explode('-', $data_cadastro)));

		// Formata o número de telefone para o formato do WhatsApp removendo espaços e símbolos e adicionando o código do país (55).
		$whats = '55' . preg_replace('/[ ()-]+/', '', $telefone);




		echo <<<HTML
<tr class="">
<td>{$nome}</td>
<td class="esc">{$telefone}</td>
<td class="esc">{$data_cadastroF}</td>

<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$telefone}', '{$endereco}', '{$tipo_chave}', '{$chave_pix}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$telefone}', '{$data_cadastroF}', '{$endereco}', '{$tipo_chave}', '{$chave_pix}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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


		<big><a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" title="Abrir Whatsapp"><i class="fa fa-whatsapp verde"></i></a></big>

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
	function editar(id, nome, telefone, endereco, tipo_chave, chave_pix) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#telefone').val(telefone);
		$('#endereco').val(endereco);
		$('#chave_pix').val(chave_pix);
		$('#tipo_chave').val(tipo_chave).change();

		$('#titulo_inserir').text('Editar Registro'); // Atualiza o título do modal para "Editar Registro".
		$('#modalForm').modal('show'); // Exibe o modal de edição.

	}

	function limparCampos() {
		$('#id').val(''); // Limpa o campo de ID do produto.
		$('#nome').val(''); // Limpa o campo de nome.
		$('#telefone').val(''); // Limpa o campo de telefone.
		$('#endereco').val(''); // Limpa o campo do endereco.
		$('#chave_pix').val(''); // Limpa o campo da chave_pix.

	}
</script>



<script type="text/javascript">
	function mostrar(nome, telefone, data_cadastro, endereco, tipo_chave, chave_pix) {

		$('#nome_dados').text(nome); // Exibe o nome do fornecedor.
		$('#data_cadastro_dados').text(data_cadastro); // Exibe a data_cadastro.

		$('#telefone_dados').text(telefone); // Exibe o telefone do fornecedor.
		$('#endereco_dados').text(endereco); // Exibe o endereço do fornecedor.
		$('#tipo_chave_dados').text(tipo_chave); // Exibe o tipo_chave do fornecedor.
		$('#chave_pix_dados').text(chave_pix); // Exibe a chave_pix do fornecedor.

		$('#modalDados').modal('show');
	}
</script>