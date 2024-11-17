<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'produtos'; // Define o nome da tabela no banco de dado

// Executa uma consulta para obter todos os produtos da tabela `produtos`, ordenados pelo ID de forma decrescente.
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Verifica se há registros para exibir.
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Categoria</th> 	
	<th class="esc">Valor Compra</th> 	
	<th class="esc">Valor Venda</th> 
	<th class="esc">Estoque</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Cabeçalho da tabela com colunas: Nome, Categoria, Valor de Compra, Valor de Venda, Estoque e Ações.
	// Loop para percorrer todos os registros.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$descricao = $resultado[$i]['descricao'];
		$categoria = $resultado[$i]['categoria'];
		$valor_compra = $resultado[$i]['valor_compra'];
		$valor_venda = $resultado[$i]['valor_venda'];
		$foto = $resultado[$i]['foto'];
		$estoque = $resultado[$i]['estoque'];
		$nivel_estoque = $resultado[$i]['nivel_estoque'];

		// Formata os valores de compra e venda para o formato brasileiro
		$valor_vendaF = number_format($valor_venda, 2, ',', '.');
		$valor_compraF = number_format($valor_compra, 2, ',', '.');


		// Consulta para obter o nome da categoria do produto.
		$query2 = $pdo->query("SELECT * FROM categoria_produtos where id = '$categoria'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_categoria = $resultado2[0]['nome'];
		} else {
			$nome_categoria = 'Sem Referência!';
		}

		// Verifica se o estoque está abaixo do nível mínimo especificado.
		if ($nivel_estoque >= $estoque) {




			echo <<<HTML
<tr class="">
<td>
<img src="img/produtos/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$nome_categoria}</td>
<td class="esc">R$ {$valor_compraF}</td>
<td class="esc">R$ {$valor_vendaF}</td>
<td class="esc">{$estoque}</td>

<td>
		
		<big><a href="#" onclick="mostrar('{$nome}', '{$nome_categoria}', '{$descricao}', '{$valor_compraF}',  '{$valor_vendaF}', '{$estoque}', '{$foto}', '{$nivel_estoque}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>

		</td>
</tr>
HTML;
		}
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
	// Inicializa o DataTable e define configurações básicas para a tabela.
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>

<script type="text/javascript">
	function editar(id, nome, categoria, descricao, valor_compra, valor_venda, foto, nivel_estoque) {
		$('#id').val(id); // Define o ID do produto no campo de formulário oculto.
		$('#nome').val(nome); // Preenche o campo de nome com o valor do produto.
		$('#valor_venda').val(valor_venda); // Define o valor de venda.
		$('#valor_compra').val(valor_compra); // Define o valor de compra.
		$('#categoria').val(categoria).change(); // Seleciona a categoria no campo dropdown.
		$('#descricao').val(descricao); // Define a descrição do produto.
		$('#nivel_estoque').val(nivel_estoque); // Define o nível mínimo de estoque.

		$('#titulo_inserir').text('Editar Registro'); // Atualiza o título do modal para "Editar Registro".
		$('#modalForm').modal('show'); // Exibe o modal de edição.

		$('#target').attr('src', 'img/produtos/' + foto); // Exibe a imagem do produto no modal.
	}

	function limparCampos() {
		$('#id').val(''); // Limpa o campo de ID do produto.
		$('#nome').val(''); // Limpa o campo de nome.
		$('#valor_compra').val(''); // Limpa o campo de valor de compra.
		$('#valor_venda').val(''); // Limpa o campo de valor de venda.
		$('#descricao').val(''); // Limpa o campo de descrição.
		$('#foto').val(''); // Limpa o campo de foto.
		$('#target').attr('src', 'img/produtos/sem-foto.jpg'); // Define a imagem padrão "sem foto".
	}
</script>

<script type="text/javascript">
	function mostrar(nome, categoria, descricao, valor_compra, valor_venda, estoque, foto, nivel_estoque) {

		$('#nome_dados').text(nome); // Exibe o nome do produto.
		$('#valor_compra_dados').text(valor_compra); // Exibe o valor de compra.
		$('#categoria_dados').text(categoria); // Exibe a categoria.
		$('#valor_venda_dados').text(valor_venda); // Exibe o valor de venda.
		$('#descricao_dados').text(descricao); // Exibe a descrição.
		$('#estoque_dados').text(estoque); // Exibe a quantidade em estoque.
		$('#nivel_estoque_dados').text(nivel_estoque); // Exibe o nível mínimo de estoque.

		$('#target_mostrar').attr('src', 'img/produtos/' + foto); // Exibe a imagem do produto no modal de visualização.

		$('#modalDados').modal('show'); // Abre o modal de visualização.
	}
</script>

<script type="text/javascript">
	function saida(id, nome, estoque) {

		$('#nome_saida').text(nome); // Exibe o nome do produto no modal de saída.
		$('#estoque_saida').val(estoque); // Exibe a quantidade atual de estoque.
		$('#id_saida').val(id); // Define o ID do produto no modal de saída.

		$('#modalSaida').modal('show'); // Abre o modal para registrar saída de estoque.
	}
</script>

<script type="text/javascript">
	$('#nome_entrada').text(nome); // Exibe o nome do produto no modal de entrada.
	$('#estoque_entrada').val(estoque); // Exibe a quantidade atual de estoque.
	$('#id_entrada').val(id); // Define o ID do produto no modal de entrada.

	$('#modalEntrada').modal('show'); // Abre o modal para registrar entrada de estoque.
</script>