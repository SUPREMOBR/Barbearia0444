<?php
require_once("../../sistema/conexao.php");
$url_img = $_POST['url_img'];
$query = $pdo->query("SELECT * FROM fornecedores order by id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$data_cadastro = $resultado[$i]['data_cadastro'];
		$telefone = $resultado[$i]['telefone'];
		$endereco = $resultado[$i]['endereco'];
		$tipo_chave = $resultado[$i]['tipo_chave'];
		$chave_pix = $resultado[$i]['chave_pix'];



		$data_cadastroF = implode('/', array_reverse(explode('-', $data_cadastro)));

		$whats = '55' . preg_replace('/[ ()-]+/', '', $telefone);



		echo '<li>';
		echo '<a href="#" class="item-link item-content" onclick="editarForn(' . $id . ', \'' . $nome . '\', \'' . $telefone . '\', \'' . $data_cadastroF . '\', \'' . $whats . '\', \'' . $tipo_chave . '\', \'' . $chave_pix . '\', \'' . $endereco . '\')">';

		echo ' <div class="item-inner">';
		echo ' <div class="item-title" style="font-size:11px">';
		echo ' <div class="item-header " style="font-size:9px">Endereço: ' . $endereco . '</div>' . $nome;
		echo '<div class="item-footer" style="font-size:9px">' . $telefone . '</div>';
		echo '</div>';
		echo ' <div class="item-after" style="font-size:9px">' . $data_cadastroF . '</div>';
		echo '</div>';
		echo '</a>';
		echo '</li>';
	}
}
