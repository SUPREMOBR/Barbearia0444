<?php
require_once("../../sistema/conexao.php");
$url_img = $_POST['url_img'];
$query = $pdo->query("SELECT * FROM clientes order by id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$data_nascimento = $resultado[$i]['data_nascimento'];
		$data_cadastro = $resultado[$i]['data_cadastro'];
		$telefone = $resultado[$i]['telefone'];
		$endereco = $resultado[$i]['endereco'];
		$ultimo_servico = $resultado[$i]['ultimo_servico'];
		$cpf = $resultado[$i]['cpf'];

		$data_cadastroF = implode('/', array_reverse(explode('-', $data_cadastro)));
		$data_nascimentoF = implode('/', array_reverse(explode('-', $data_nascimento)));

		if ($data_nascimentoF == '00/00/0000') {
			$data_nascimentoF = 'Sem Lançamento';
		}


		$whats = '55' . preg_replace('/[ ()-]+/', '', $telefone);



		echo '<li>';
		echo '<a href="#" class="item-link item-content" onclick="editarCliente(' . $id . ', \'' . $nome . '\', \'' . $telefone . '\', \'' . $data_cadastroF . '\', \'' . $whats . '\', \'' . $cartoes . '\', \'' . $data_retornoF . '\')">';

		echo ' <div class="item-inner">';
		echo ' <div class="item-title" style="font-size:11px">';
		echo '<div class="item-footer" style="font-size:9px">' . $telefone . '</div>';
		echo '</div>';
		echo ' <div class="item-after" style="font-size:9px">' . $data_cadastroF . '</div>';
		echo '</div>';
		echo '</a>';
		echo '</li>';
	}
} else {
	echo '<br><small><small><div align="center">Não encontramos nenhum registro!</div></small></small>';
}
