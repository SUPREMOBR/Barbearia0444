<?php 
require_once("../../../conexao.php");
$tabela = 'produtos';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

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

	


for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$nome = $resultado[$i]['nome'];	
	$descricao = $resultado[$i]['descricao'];
	$categoria = $resultado[$i]['categoria'];
	$valor_compra = $resultado[$i]['valor_compra'];
	$valor_venda = $resultado[$i]['valor_venda'];
	$foto = $resultado[$i]['foto'];
	$estoque = $resultado[$i]['estoque'];
	$nivel_estoque = $resultado[$i]['nivel_estoque'];

	$valor_vendaFormatada = number_format($valor_venda, 2, ',', '.');
	$valor_compraFormatada = number_format($valor_compra, 2, ',', '.');


	$query2 = $pdo->query("SELECT * FROM categoria_produtos where id = '$categoria'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$nome_categoria = $resultado2[0]['nome'];
		}else{
			$nome_categoria = 'Sem Referência!';
		}


       if($nivel_estoque >= $estoque){
			

echo <<<HTML
<tr class="">
<td>
<img src="img/produtos/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$nome_categoria}</td>
<td class="esc">R$ {$valor_compraFormatada}</td>
<td class="esc">R$ {$valor_vendaFormatada}</td>
<td class="esc">{$estoque}</td>

<td>

		<big><a href="#" onclick="mostrar('{$nome}', '{$nome_categoria}', '{$descricao}', '{$valor_compraFormatada}',  '{$valor_vendaFormatada}', '{$estoque}', '{$foto}', '{$nivel_estoque}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>


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


}else{
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>

<script type="text/javascript">
	$(document).ready( function () {
    $('#tabela').DataTable({
    		"ordering": false,
			"stateSave": true
    	});
    $('#tabela_filter label input').focus();
} );
</script>


<script type="text/javascript">
	function editar(id, nome, categoria, descricao, valor_compra, valor_venda, foto, nivel_estoque){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#valor_venda').val(valor_venda);
		$('#valor_compra').val(valor_compra);
		$('#categoria').val(categoria).change();
		$('#descricao').val(descricao);
		$('#nivel_estoque').val(nivel_estoque);
				
		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');

		$('#target').attr('src','img/produtos/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#valor_compra').val('');
		$('#valor_venda').val('');
		$('#descricao').val('');		
		$('#foto').val('');
		$('#target').attr('src','img/produtos/sem-foto.jpg');
	}
</script>


<script type="text/javascript">
	function mostrar(nome, categoria, descricao, valor_compra, valor_venda, estoque, foto, nivel_estoque){

       $('#nome_dados').text(nome);
       $('#valor_compra_dados').text(valor_compra);
       $('#categoria_dados').text(categoria);
       $('#valor_venda_dados').text(valor_venda);
       $('#descricao_dados').text(descricao);
       $('#estoque_dados').text(estoque);
       $('#nivel_estoque_dados').text(nivel_estoque);

       $('#target_mostrar').attr('src','img/produtos/' + foto);

       $('#modalDados').modal('show');
    }
</script>


<script type="text/javascript">
	function saida(id, nome, estoque){

		$('#nome_saida').text(nome);
		$('#estoque_saida').val(estoque);
		$('#id_saida').val(id);		

		$('#modalSaida').modal('show');
	}
</script>


<script type="text/javascript">
	function entrada(id, nome, estoque){

		$('#nome_entrada').text(nome);
		$('#estoque_entrada').val(estoque);
		$('#id_entrada').val(id);		

		$('#modalEntrada').modal('show');
	}
</script>