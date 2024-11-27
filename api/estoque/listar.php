<?php
require_once("../../sistema/conexao.php");
$url_img = $_POST['url_img'];
$query = $pdo->query("SELECT * FROM produtos order by id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$descricao = $resultado[$i]['descricao'];
		$categoria = $resultado[$i]['categoria'];
		$valor_compra = $resultado[$i]['valor_compra'];
		$valor_venda = $resultado[$i]['valor_venda'];
		$foto = $resultado[$i]['foto'];
		$estoque = $resultado[$i]['estoque'];
		$nivel_estoque = $resultado[$i]['nivel_estoque'];

		$valor_vendaF = number_format($valor_venda, 2, ',', '.');
		$valor_compraF = number_format($valor_compra, 2, ',', '.');

		$query2 = $pdo->query("SELECT * FROM categoria_produtos where id = '$categoria'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_categoria = $resultado2[0]['nome'];
		} else {
			$nome_categoria = 'Sem Referência!';
		}


		if ($nivel_estoque >= $estoque) {

			echo '<li>';
			echo '<a href="#" class="item-link item-content" onclick="editarEstoque(' . $id . ', \'' . $nome . '\', \'' . $descricao . '\', \'' . $nome_categoria . '\', \'' . $valor_compraF . '\', \'' . $valor_vendaF . '\', \'' . $foto . '\', \'' . $estoque . '\', \'' . $nivel_estoque . '\')">';
			echo ' <div class="item-media"><img src="' . $url_img . 'produtos/' . $foto . '" width="40px" height="40px" style="object-fit: cover; "></div>';
			echo ' <div class="item-inner">';
			echo ' <div class="item-title" style="font-size:11px">';
			echo ' <div class="item-header " style="font-size:9px">Nível Mínimo: ' . $nivel_estoque . ' </div>' . $nome;
			echo '<div class="item-footer" style="font-size:9px">Categoria: ' . $nome_categoria . '</div>';
			echo '</div>';
			echo ' <div class="item-after" style="font-size:10px">Estoque <b> : ' . $estoque . '</b></div>';
			echo '</div>';
			echo '</a>';
			echo '</li>';
		}
	}
}
