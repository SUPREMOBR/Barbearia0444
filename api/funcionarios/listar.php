<?php
require_once("../../sistema/conexao.php");
$url_img = $_POST['url_img'];
$query = $pdo->query("SELECT * FROM usuarios01 order by id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$email = $resultado[$i]['email'];
		$cpf = $resultado[$i]['cpf'];
		$senha = $resultado[$i]['senha'];
		$nivel = $resultado[$i]['nivel'];
		$data = $resultado[$i]['data'];
		$ativo = $resultado[$i]['ativo'];
		$telefone = $resultado[$i]['telefone'];
		$endereco = $resultado[$i]['endereco'];
		$foto = $resultado[$i]['foto'];
		$atendimento = $resultado[$i]['atendimento'];

		$whatsapp = '55' . preg_replace('/[ ()-]+/', '', $telefone);

		$dataF = implode('/', array_reverse(explode('-', $data)));

		if ($nivel == 'Administrador') {
			$senhaF = '******';
		} else {
			$senhaF = $senha;
		}

		if ($ativo != "Sim") {
			$classe_ativo = 'item-footer';
			$valor_op = '0.3';
		} else {
			$classe_ativo = '';
			$valor_op = '1';
		}



		echo '<li>';
		echo '<a href="#" class="item-link item-content" onclick="editarFunc(' . $id . ', \'' . $nome . '\', \'' . $email . '\', \'' . $telefone . '\', \'' . $nivel . '\', \'' . $dataF . '\', \'' . $foto . '\', \'' . $ativo . '\', \'' . $endereco . '\', \'' . $whatsapp . '\')">';
		echo ' <div class="item-media"><img src="' . $url_img . 'perfil/' . $foto . '" width="40px" height="40px" style="object-fit: cover; opacity:' . $valor_op . '"></div>';
		echo ' <div class="item-inner">';
		echo ' <div class="item-title ' . $classe_ativo . '" style="font-size:11px">';
		echo ' <div class="item-header " style="font-size:9px">Cargo: ' . $nivel . '</div>' . $nome;
		echo '<div class="item-footer" style="font-size:9px">' . $email . '</div>';
		echo '</div>';
		echo ' <div class="item-after" style="font-size:9px">' . $dataF . '</div>';
		echo '</div>';
		echo '</a>';
		echo '</li>';
	}
}
