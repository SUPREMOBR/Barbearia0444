<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'produtos'; // Define o nome da tabela no banco de dados

// Faz uma consulta no banco de dados para buscar todos os produtos, ordenados por ID de forma decrescente
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

// Verifica se existem produtos no banco de dados
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
	// Loop para percorrer todos os produtos encontrados no banco de dados
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Extrai os dados do produto atual
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$descricao = $resultado[$i]['descricao'];
		$categoria = $resultado[$i]['categoria'];
		$valor_compra = $resultado[$i]['valor_compra'];
		$valor_venda = $resultado[$i]['valor_venda'];
		$foto = $resultado[$i]['foto'];
		$estoque = $resultado[$i]['estoque'];
		$nivel_estoque = $resultado[$i]['nivel_estoque'];

		// Formata os valores de compra e venda para exibição (ex: R$ 10,00)
		$valor_vendaF = number_format($valor_venda, 2, ',', '.');
		$valor_compraF = number_format($valor_compra, 2, ',', '.');

		// Consulta a tabela de categorias para obter o nome da categoria do produto
		$query2 = $pdo->query("SELECT * FROM categoria_produtos where id = '$categoria'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);

		// Verifica se a categoria foi encontrada e atribui o nome da categoria
		if ($total_registro2 > 0) {
			$nome_categoria = $resultado2[0]['nome'];
		} else {
			$nome_categoria = 'Sem Referência!';
		}

		// Verifica se o estoque do produto está abaixo do nível mínimo, para adicionar um alerta
		if ($nivel_estoque >= $estoque) {
			$alerta_estoque = 'text-danger';
		} else {
			$alerta_estoque = '';
		}



		echo <<<HTML
<tr class="{$alerta_estoque}">
<td>
<img src="img/produtos/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$nome_categoria}</td>
<td class="esc">R$ {$valor_compraF}</td>
<td class="esc">R$ {$valor_vendaF}</td>
<td class="esc">{$estoque}</td>

<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$categoria}', '{$descricao}', '{$valor_compra}', '{$valor_venda}', '{$foto}', '{$nivel_estoque}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$nome_categoria}', '{$descricao}', '{$valor_compraF}',  '{$valor_vendaF}', '{$estoque}', '{$foto}', '{$nivel_estoque}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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


		<big><a href="#" onclick="saida('{$id}','{$nome}', '{$estoque}')" title="Saída de Produto"><i class="fa fa-sign-out text-danger"></i></a></big>

		<big><a href="#" onclick="entrada('{$id}','{$nome}', '{$estoque}')" title="Entrada de Produto"><i class="fa fa-sign-in verde"></i></a></big>

	
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
	function editar(id, nome, categoria, descricao, valor_compra, valor_venda, foto, nivel_estoque) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#valor_venda').val(valor_venda);
		$('#valor_compra').val(valor_compra);
		$('#categoria').val(categoria).change();
		$('#descricao').val(descricao);
		$('#nivel_estoque').val(nivel_estoque);

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src', 'img/produtos/' + foto);
	}

	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#valor_compra').val('');
		$('#valor_venda').val('');
		$('#descricao').val('');
		$('#foto').val('');
		$('#target').attr('src', 'img/produtos/sem-foto.jpg');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, categoria, descricao, valor_compra, valor_venda, estoque, foto, nivel_estoque) {

		$('#nome_dados').text(nome);
		$('#valor_compra_dados').text(valor_compra);
		$('#categoria_dados').text(categoria);
		$('#valor_venda_dados').text(valor_venda);
		$('#descricao_dados').text(descricao);
		$('#estoque_dados').text(estoque);
		$('#nivel_estoque_dados').text(nivel_estoque);

		$('#target_mostrar').attr('src', 'img/produtos/' + foto);

		$('#modalDados').modal('show');
	}
</script>




<script type="text/javascript">
	function saida(id, nome, estoque) {

		$('#nome_saida').text(nome);
		$('#estoque_saida').val(estoque);
		$('#id_saida').val(id);

		$('#modalSaida').modal('show');
	}
</script>


<script type="text/javascript">
	function entrada(id, nome, estoque) {

		$('#nome_entrada').text(nome);
		$('#estoque_entrada').val(estoque);
		$('#id_entrada').val(id);

		$('#modalEntrada').modal('show');
	}
</script>